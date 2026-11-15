<?php

namespace Database\Seeders;

use App\Models\Acquisition;
use App\Models\Campus;
use App\Models\Item;
use App\Models\Library;
use App\Services\AccessionService;
use App\Services\AcquisitionLinesService;
use Illuminate\Database\Seeder;

class AcquisitionLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcquisitionLinesService $lines, AccessionService $accession): void
    {
        $campus = Campus::where('code', 'ISU-E')->firstOrFail();
        $library = Library::where('campus_id', $campus->id)->firstOrFail();

        $items = Item::where('library_id', $library->id)->pluck('id');
        $acquisitions = Acquisition::query()->whereHas('receiver', fn ($q) => $q->where('library_id', $library->id))->get();

        foreach ($acquisitions as $acq) {
            for ($i = 0; $i < rand(3, 8); $i++) {
                $line = $lines->create([
                    'unit_price' => $acq->acquisition_mode->has_price ? rand(500, 1500) : null,
                    'item_id' => $items->random(),
                    'acquisition_id' => $acq->id,
                    'quantity' => 0,
                ]);

                for ($a = 0; $a < rand(3, 5); $a++) {
                    $accession->create([
                        'accession_number' => $line->id . str_pad($a, 5, '0', STR_PAD_LEFT),
                        'item_id' => $line->item_id,
                        'acquisition_line_id' => $line->id,
                    ]);
                }
            }
        }

    }
}
