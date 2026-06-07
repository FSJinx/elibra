<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['name' => 'Echague Campus Library', 'campus_id' => 1],
            ['name' => 'Santiago Campus Library', 'campus_id' => 10],
            ['name' => 'Angadanan Campus Library', 'campus_id' => 5],
            ['name' => 'Cauayan Campus Library', 'campus_id' => 3],
            ['name' => 'Ilagan Campus Library', 'campus_id' => 4],
        ];

        foreach ($branches as $branch) {
            \App\Models\Branch::create($branch);
        }
    }
}

