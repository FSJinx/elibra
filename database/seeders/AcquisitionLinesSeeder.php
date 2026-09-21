<?php

namespace Database\Seeders;

use App\Services\AcquisitionLinesService;
use Illuminate\Database\Seeder;

class AcquisitionLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcquisitionLinesService $lines): void
    {
        collect([
            [
                'quantity' => 3,
                'unit_price' => 2500,
                'discount' => 250,
                'item_id' => 1,
                'acquisition_id' => 1,
                'branch_section_id' => 1,
            ],
            [
                'quantity' => 7,
                'unit_price' => 750,
                'discount' => 250,
                'item_id' => 10,
                'acquisition_id' => 1,
                'branch_section_id' => 2,
            ],
            [
                'quantity' => 5,
                'unit_price' => 1800,
                'discount' => 500,
                'item_id' => 2,
                'acquisition_id' => 2,
                'branch_section_id' => 1,
            ],
        ])->each(function (array $data) use ($lines): void {
            $data['net_price'] = ($data['quantity'] * $data['unit_price']) - $data['discount'];
            $lines->create($data);
        });
    }
}
