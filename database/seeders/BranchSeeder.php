<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Campus;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            // Echague Campus Branches
            ['name' => 'University Library', 'campus' => 'ISU-E'],
            ['name' => 'CCSICT Research Room', 'campus' => 'ISU-E'],

            // Angadanan Campus Branches
            ['name' => 'Public Library', 'campus' => 'ISU-AC'],
            ['name' => 'CCJE Library', 'campus' => 'ISU-AC'],

        ])->each(function ($branch) {
            $campus_id = Campus::where('code', '=', $branch['campus'])->first();
            Branch::create([
                'name' => $branch['name'],
                'campus_id' => $campus_id['id'],
            ]);
        });
    }
}
