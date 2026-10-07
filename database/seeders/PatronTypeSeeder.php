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
            ['key' => 'sp', 'name' => 'Student', 'description' => 'A student patron type.'],
            ['key' => 'fp', 'name' => 'Faculty', 'description' => 'A faculty patron type.'],
            ['key' => 'gp', 'name' => 'Guest', 'description' => 'A guest patron type.'],
        ];

        foreach ($patron_types as $type) {
            PatronType::create($type);
        }
    }
}
