<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $inventories = Inventory::query()
            ->when($filters['search'], fn ($i, $s) => $i->where('inventory_code', 'LIKE', "%$s%"))->with(['librarian.user', 'section']);

        $inventories = $inventories->paginate(null, ['*']);

        return $this->response('success', 'Inventories retrieved successfully', $inventories, 200);
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
    public function store(StoreInventoryRequest $request)
    {
        $user = $this->user()->librarian;

        if (Inventory::where('section_id', $request->input('section_id'))->where('status', 'pending')->exists()) {
            return $this->response('error', 'This section has an on-going inventory. Select other sections or finish the pending before creating one again', [], 409);
        }

        $inventory = DB::transaction(function () use ($user, $request) {

            $lastInventory = Inventory::latest('id')->first();

            $nextNumber = $lastInventory ? $lastInventory->id + 1 : 1;

            $inventoryCode = 'INV-'.now()->year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            return Inventory::create([
                'inventory_code' => $inventoryCode,
                'section_id' => $request->input('section_id'),
                'librarian_id' => $user->id,

            ]);
        });

        return $this->response(
            'success',
            'Inventory created successfully',
            $inventory->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        // $user = $this->user();

        // $lines = $inventory->lines()
        //     ->whereHas('accession', function ($query) use ($user) {
        //         $query->where('library_id', $user->library_id);
        //     })
        //     ->with([
        //         'accession.item',
        //         'accession.section',
        //         'verifiedBy',
        //     ])
        //     ->get();

        return $this->response(
            'success',
            'Inventory retrieved successfully',
            $inventory->load(['librarian.user', 'section']),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $user = $this->user();

        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to delete this inventory',
                null,
                403
            );
        }

        if ($inventory->is_completed) {
            return $this->response(
                'error',
                'Completed inventory cannot be deleted',
                null,
                422
            );
        }

        $inventory->lines()->delete();
        $inventory->delete();

        return $this->response(
            'success',
            'Inventory deleted successfully',
            null,
            200
        );
    }
}
