<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['name' => 'University Library', 'campus_id' => 1],
            ['name' => 'University Library', 'campus_id' => 10],
            ['name' => 'University Library', 'campus_id' => 5],
            ['name' => 'University Library', 'campus_id' => 3],
            ['name' => 'University Library', 'campus_id' => 4],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
