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
            ['key' => 'student', 'name' => 'Student', 'description' => 'A student patron type.'],
            ['key' => 'faculty', 'name' => 'Faculty', 'description' => 'A faculty patron type.'],
            ['key' => 'staff', 'name' => 'Staff', 'description' => 'A staff patron type.'],
            ['key' => 'alumni', 'name' => 'Alumni', 'description' => 'An alumni patron type.'],
            ['key' => 'guest', 'name' => 'Guest', 'description' => 'A guest patron type.'],
        ];

        foreach ($patron_types as $type) {
            PatronType::create($type);
        }
    }
}
