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
            for ($i = 0; $i < rand(1, 8); $i++) {
                $a = $lines->create([
                    'unit_price' => 0,
                    'item_id' => $items->random(),
                    'acquisition_id' => $acq->id,
                ]);

                $accession->generateCopies(rand(1, 10), $a->item, $a);
            }
        }

    }
}

// App\Models\Acquisition::query()->whereHas('receiver', fn ($q) => $q->where('id', 1))->pluck('id')
