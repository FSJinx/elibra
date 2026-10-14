<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'Echague Campus (Main)',
                'code' => 'ISU-E',
                'address' => 'San Fabian, Echague, Isabela',
            ],
            [
                'name' => 'Angadanan Campus',
                'code' => 'ISU-AC',
                'address' => 'Angadanan, Isabela',
            ],
        ])->each(fn ($campus) => Campus::create($campus));
    }
}
