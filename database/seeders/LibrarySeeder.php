<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Library;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            // Echague Campus Branches
            [
                'name' => 'University Library',
                'campus' => 'ISU-E',
                'phone' => '09000000001',
                'email' => 'isu.e.library@isu.edu.ph',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:30:00',
            ],
            [
                'name' => 'CCSICT Research Room',
                'campus' => 'ISU-E',
                'phone' => '09000000002',
                'email' => 'isu.e.ccsict@isu.edu.ph',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:30:00',
            ],

            // Angadanan Campus Branches
            [
                'name' => 'University Library',
                'campus' => 'ISU-AC',
                'phone' => '09000000003',
                'email' => 'isu.ac.library@isu.edu.ph',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:30:00',
            ],
            [
                'name' => 'CCJE Library',
                'campus' => 'ISU-AC',
                'phone' => '09000000004',
                'email' => 'isu.ac.ccje@isu.edu.ph',
                'opening_hour' => '08:00:00',
                'closing_hour' => '17:30:00',
            ],

        ])->each(function ($lib) {
            $campus = Campus::where('code', $lib['campus'])->first();

            if (! $campus) {
                return;
            }

            Library::create([
                ...Arr::except($lib, ['campus']),
                'campus_id' => $campus->id,
            ]);
        });
    }
}
