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
        $campuses = [
            [
                'name' => 'Echague Campus',
                'code' => 'ISU-E',
                'address' => 'San Fabian, Echague, Isabela',
            ],
            [
                'name' => 'Santiago Campus',
                'code' => 'ISU-AC',
                'address' => 'Santiago, Isabela',
            ],
        ];
        foreach ($campuses as $campus) {
            Campus::create($campus);
        }
    }
}
