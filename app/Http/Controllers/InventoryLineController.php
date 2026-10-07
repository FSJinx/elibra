<?php

namespace App\Http\Controllers;

use App\Models\Inventory_Line;
use App\Http\Requests\StoreInventory_LineRequest;
use App\Http\Requests\UpdateInventory_LineRequest;
use App\Models\Inventory;

class InventoryLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Inventory $inventory)
    {
        $user = $this->user();

        /*
         * Make sure the inventory belongs to
         * the authenticated librarian.
         */
        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to view this inventory',
                null,
                403
            );
        }

        $lines = $inventory->lines()
            ->with([
                'accession',
                'verifiedBy',
            ])
            ->get();

        return $this->response(
            'success',
            'Inventory lines retrieved successfully',
            $lines->toArray(),
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
    public function store(StoreInventory_LineRequest $request)
    {
        //
    }

    /**
     * Display a specific inventory line.
     */
    public function show(Inventory_Line $inventoryLine)
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
    public function verify(Inventory_Line $inventoryLine)
    {
        $user = $this->user();

        /*
         * Load the parent inventory so we can
         * verify ownership.
         */
        $inventoryLine->load('inventory');

        $inventory = $inventoryLine->inventory;

        if ($inventory->librarian_id !== $user->id) {
            return $this->response(
                'error',
                'You are not authorized to verify this inventory line',
                null,
                403
            );
        }

        /*
         * Do not allow verification after the
         * inventory has already been completed.
         */
        if ($inventory->is_completed) {
            return $this->response(
                'error',
                'Completed inventory cannot be modified',
                null,
                422
            );
        }

        /*
         * Set the authenticated librarian as
         * the person who verified the accession.
         */
        $inventoryLine->update([
            'verified_by' => $user->id,
        ]);

        /*
         * Check whether every inventory line
         * has now been verified.
         */
        $unverifiedCount = $inventory->lines()
            ->whereNull('verified_by')
            ->count();

        if ($unverifiedCount === 0) {
            $inventory->update([
                'is_completed' => true,
            ]);
        }

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
    public function unverify(Inventory_Line $inventoryLine)
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
    public function destroy(Inventory_Line $inventoryLine)
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
