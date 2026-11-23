<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Accession;
use App\Models\Inventory;
use App\Models\InventoryLine;
use App\Services\QueryService;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->when($filters['search'], fn ($i, $s) => $i->where('inventory_code', 'LIKE', "%$s%"))->with(['librarian.user', 'section'])
            ->when($filters['library_id'] ?? null,
                fn ($q, $libraryId) => $q->whereHas('librarian', fn ($qq) => $qq->where('library_id', $libraryId)),
                fn ($q) => $q->whereHas('librarian', fn ($qq) => $qq->where('library_id', $this->user()?->librarian?->library_id))
            )
            ->orderBy($filters['sort'], $filters['order'])
            ->paginate();

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
        $accessions = Accession::query()
            ->whereHas('item', fn ($query) => $query->where('library_id', $user->library_id))
            ->with(['item.itemType', 'section'])
            ->join('items', 'accessions.item_id', '=', 'items.id')
            ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
            ->select('accessions.*')
            ->orderBy('item_types.name')
            ->orderBy('items.title')
            ->orderBy('accessions.accession_number')
            ->get();

        if ($accessions->isEmpty()) {
            return $this->response('error',             'No catalog copies are available for inventory.', null, 422);
        }

        $inventory = DB::transaction(function () use ($user, $accessions) {

            $lastInventory = Inventory::latest('id')->first();
            $nextNumber = $lastInventory ? $lastInventory->id + 1 : 1;
            $inventoryCode = 'INV-'.now()->year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $inventory = Inventory::create([
                'inventory_code' => $inventoryCode,
                'status' => 'completed',
                'closed_at' => now(),
                'librarian_id' => $user->id,
            ]);

            foreach ($accessions as $accession) {
                InventoryLine::create([
                    'inventory_id' => $inventory->id,
                    'accession_id' => $accession->id,
                    'current_status' => $accession->status,
                    'current_section_id' => $accession->section_id,
                    'new_status' => $accession->status,
                    'new_section_id' => $accession->section_id,
                ]);
            }

            return $inventory;
        });

        return $this->response(
            'success',
            'Inventory created successfully',
            $inventory->load([
                'librarian.user',
                'lines.accession.item.itemType',
                'lines.accession.section',
                'lines.currentSection',
            ]),
            201
        );
    }

    public function downloadPdf(Inventory $inventory)
    {
        $user = $this->user();
        if ($user->isAdmin() !== $user->isLibrarian()) {
            return $this->response('error', 'You are not authorized to print this inventory.', null, 403);
        }

        $inventory->load([
            'librarian.user',
            'lines.accession.item.itemType',
            'lines.accession.section',
            'lines.currentSection',
        ]);

        $inventory->setRelation('lines', $inventory->lines->sort(function ($a, $b) {
            $aType = $a->accession?->item?->itemType?->name ?? 'Unclassified';
            $bType = $b->accession?->item?->itemType?->name ?? 'Unclassified';
            $aTitle = $a->accession?->item?->title ?? 'Untitled';
            $bTitle = $b->accession?->item?->title ?? 'Untitled';
            $aAccession = $a->accession?->accession_number ?? '';
            $bAccession = $b->accession?->accession_number ?? '';
            return strcasecmp($aType, $bType)
                ?: strcasecmp($aTitle, $bTitle)
                ?: strcasecmp($aAccession, $bAccession);
        }));

        $pdf = Pdf::loadView('inventory_sheet', [
            'inventory' => $inventory,
            'lines' => $inventory->lines,
        ])->setPaper('folio', 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$inventory->inventory_code.'.pdf"',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $user = $this->user();
        if ($inventory->librarian_id !== $user->librarian?->id) {
            return $this->response('error', 'You are not authorized to view this inventory.', null, 403);
        }

        return $this->response(
            'success',
            'Inventory retrieved successfully',
            $inventory->load([
                'librarian.user',
                'lines.accession.item.itemType',
                'lines.accession.section',
                'lines.currentSection',
            ]),
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

        if ($inventory->librarian_id !== $user->librarian?->id) {
            return $this->response(
                'error',
                'You are not authorized to delete this inventory',
                null,
                403
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
