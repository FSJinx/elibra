<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::all();

        return $this->response(
            'success',
            'Inventories retrieved successfully',
            $inventories->toArray(),
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
    public function store(StoreInventoryRequest $request)
    {
        $user = $this->user();

        $inventory = DB::transaction(function () use ($user) {

            $lastInventory = Inventory::latest('id')->first();

            $nextNumber = $lastInventory
                ? $lastInventory->id + 1
                : 1;

            $inventoryCode = 'INV-' . now()->year . '-' . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );

            return Inventory::create([
                'inventory_code' => $inventoryCode,
                'librarian_id'   => $user->id,
                'is_completed'   => false,
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
        $user = $this->user();

        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to view this inventory',
                null,
                403
            );
        }

        $lines = $inventory->lines()
            ->whereHas('accession', function ($query) use ($user) {
                $query->where('library_id', $user->library_id);
            })
            ->with([
                'accession.item',
                'accession.section',
                'verifiedBy',
            ])
            ->get();

        return $this->response(
            'success',
            'Inventory retrieved successfully',
            [
                'inventory' => $inventory,
                'lines' => $lines,
            ],
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
