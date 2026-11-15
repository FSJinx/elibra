<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryLineRequest;
use App\Http\Requests\UpdateInventoryLineRequest;
use App\Models\Inventory;
use App\Models\InventoryLine;
use App\Services\InventoryLineService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class InventoryLineController extends Controller
{
    public function __construct(
        protected InventoryLineService $inventoryLineService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->user();

        $filters = QueryService::filters($request);

        $filters = array_merge($filters, [
            'inventory_id' => $request->input('inventory_id'),
            'verified' => $request->has('verified') ? $request->boolean('verified') : null,
            'status' => $request->input('status') ?? null,
        ]);

        $data = InventoryLine::query()
            ->where('inventory_id', $filters['inventory_id'])
            ->when(! is_null($filters['verified']), fn ($q) => $q->{$filters['verified'] ? 'whereNotNull' : 'whereNull'}('verified_at'))
            ->when($filters['status'], fn ($q, $status) => $q->where('current_status', $status))
            ->with(['accession.item.itemType'])
            ->paginate($filters['per_page']);

        $stats = InventoryLine::where('inventory_id', $filters['inventory_id'])
            ->selectRaw("
            count(*) as total,
            count(verified_at) as verified,
            count(*) - count(verified_at) as pending,
            coalesce(sum(current_status = 'missing'), 0) as missing
        ")->first();

        // $data = [...$data, 'stats' => $stats];
        $data = array_merge($data->toArray(), ['stats' => $stats]);

        return $this->response(
            'success',
            'Inventory lines retrieved successfully',
            $data,
            200
        );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryLineRequest $request)
    {
        $inventory = Inventory::with('librarian')->findOrFail($request->integer('inventory_id'));

        if ($inventory->librarian_id !== $this->user()->librarian?->id) {
            return $this->response(
                'error',
                'You are not authorized to modify this inventory',
                null,
                403
            );
        }

        if ($inventory->status !== 'pending') {
            return $this->response(
                'error',
                'Completed inventory cannot be modified',
                null,
                422
            );
        }

        $lines = $this->inventoryLineService->create($inventory, $request->validated());

        return $this->response(
            'success',
            'Inventory lines created successfully',
            $lines->load(['accession.item', 'verifiedBy'])->toArray(),
            201
        );
    }

    /**
     * Display a specific inventory line.
     */
    public function show(InventoryLine $inventoryLine)
    {
        $user = $this->user();

        $inventoryLine->load('inventory');

        if ($inventoryLine->inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to view this inventory line',
                null,
                403
            );
        }

        $inventoryLine->load([
            'accession',
            'verifiedBy',
        ]);

        return $this->response(
            'success',
            'Inventory line retrieved successfully',
            $inventoryLine->toArray(),
            200
        );
    }

    /**
     * Verify an inventory line.
     */
    public function verify(InventoryLine $inventoryLine, UpdateInventoryLineRequest $request)
    {
        $user = $this->user();
        $data = array_merge($request->validated(), [
            'verified_at' => now(),
            'verifier_id' => $user->librarian->id ?? null,
        ]);
        $this->inventoryLineService->update($inventoryLine, $data);

        return $this->response(
            'success',
            'Inventory line verified successfully',
            $inventoryLine->load([
                'accession',
                'verifiedBy',
                'inventory',
            ])->toArray(),
            200
        );
    }

    /**
     * Unverify an inventory line.
     */
    public function unverify(InventoryLine $inventoryLine)
    {
        $user = $this->user();

        $inventoryLine->load('inventory');

        $inventory = $inventoryLine->inventory;

        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to unverify this inventory line',
                null,
                403
            );
        }

        /*
         * If the inventory is already completed,
         * we don't allow changes.
         */
        if ($inventory->is_completed) {
            return $this->response(
                'error',
                'Completed inventory cannot be modified',
                null,
                422
            );
        }

        $inventoryLine->update([
            'verified_by' => null,
        ]);

        return $this->response(
            'success',
            'Inventory line unverified successfully',
            $inventoryLine->load([
                'accession',
                'verifiedBy',
                'inventory',
            ])->toArray(),
            200
        );
    }

    /**
     * Delete an inventory line.
     */
    public function destroy(InventoryLine $inventoryLine)
    {
        $user = $this->user();

        $inventoryLine->load('inventory');

        $inventory = $inventoryLine->inventory;

        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to delete this inventory line',
                null,
                403
            );
        }

        if ($inventory->is_completed) {
            return $this->response(
                'error',
                'Completed inventory cannot be modified',
                null,
                422
            );
        }

        $inventoryLine->delete();

        return $this->response(
            'success',
            'Inventory line deleted successfully',
            null,
            200
        );
    }
}
