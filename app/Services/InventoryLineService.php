<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\Inventory;
use App\Models\InventoryLine;
use Illuminate\Support\Facades\DB;

class InventoryLineService
{
    public function create(Inventory $inventory, array $data)
    {
        return DB::transaction(function () use ($inventory, $data) {
            $accessions = Accession::query()
                ->whereHas('item', fn ($query) => $query->where('library_id', $inventory->librarian->library_id))
                ->when(
                    (bool) ($data['shelved'] ?? false),
                    fn ($query) => $query->whereNull('section_id'),
                    fn ($query) => $query->where('section_id', $data['section_id'])
                )
                ->whereDoesntHave('inventoryLines', fn ($query) => $query->where('inventory_id', $inventory->id))
                ->get();

            $lines = $accessions->map(fn (Accession $accession) => InventoryLine::create([
                'inventory_id' => $inventory->id,
                'accession_id' => $accession->id,

                'expected_status' => $accession->status,
                'expected_section_id' => $accession->section_id,

                'current_status' => $accession->status,
            ]));

            return $lines;
        });
    }
}
