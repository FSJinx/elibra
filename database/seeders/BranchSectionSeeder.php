<?php

namespace Database\Seeders;

use App\Models\BranchSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            // University Library 
            [
                'branch_id' => 1,
                'section_id' => 1,
            ],
            [
                'branch_id' => 1,
                'section_id' => 2,
            ],
            [
                'branch_id' => 1,
                'section_id' => 3,
            ],
            [
                'branch_id' => 1,
                'section_id' => 4,
            ],
            [
                'branch_id' => 1,
                'section_id' => 5,
            ],
            [
                'branch_id' => 1,
                'section_id' => 6,
            ],
            [
                'branch_id' => 1,
                'section_id' => 7,
            ],
            [
                'branch_id' => 1,
                'section_id' => 8,
            ],

            // CCSICT Research Library 
            [
                'branch_id' => 2,
                'section_id' => 9,
            ],

            // Public Library - Angadanan
            [
                'branch_id' => 3,
                'section_id' => 2,
            ],
        ])->each(fn ($data) => BranchSection::create($data));
    }
}
