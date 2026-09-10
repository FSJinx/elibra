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
            [
                'branch_id' => 1,
                'section_id' => 5,
            ],
            [
                'branch_id' => 1,
                'section_id' => 2,
            ],
        ])->each(fn ($data) => BranchSection::create($data));
    }
}
