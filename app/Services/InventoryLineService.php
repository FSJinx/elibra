<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\Inventory;
use App\Models\InventoryLine;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InventoryLineService
{
    public function create(Inventory $inventory, array $data)
    {
        return DB::transaction(function () use ($inventory, $data) {
            $accessions = Accession::query()
                ->whereHas('item', fn ($query) => $query->where('library_id', $inventory->librarian->library_id))
                ->where('section_id', ($data['shelved'] ?? false) ? $data['section_id'] : null)
                ->whereDoesntHave('inventoryLines', fn ($query) => $query->where('inventory_id', $inventory->id))
                ->get();

            if (empty($accessions)) {
                return null;
            }

            $lines = $accessions->map(fn (Accession $accession) => InventoryLine::create([
                'inventory_id' => $inventory->id,
                'accession_id' => $accession->id,

                'current_status' => $accession->status,
                'current_section_id' => $accession->section_id,

                'new_status' => $accession->status,
                'new_section_id' => $accession->section_id,
            ]));

            return $lines;
        });
    }

    public function update(InventoryLine $line, array $data)
    {
        return DB::transaction(function () use ($line, $data) {
            $line->accession->update(['status' => $data['new_status']]);

            return $line->update(Arr::only($data, [
                'new_status',
                'new_section_id',

                'verified_at',
                'verifier_id',
            ]));
        });
    }
}
