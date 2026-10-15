<?php

namespace Database\Seeders;

use App\Models\PatronType;
use Illuminate\Database\Seeder;

class PatronTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patron_types = [
            ['key' => 'sp', 'name' => 'Student', 'description' => 'A student patron type.', 'validity_period' => 4],
            ['key' => 'fp', 'name' => 'Faculty', 'description' => 'A faculty patron type.', 'validity_period' => 10],
            ['key' => 'gp', 'name' => 'Guest', 'description' => 'A guest patron type.', 'validity_period' => 1],
        ];

        foreach ($patron_types as $type) {
            PatronType::create($type);
        }
    }
}
