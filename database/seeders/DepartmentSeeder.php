<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'College of Computing Studies, Information, and Communication Technology',
            'code' => 'ccsict',
            'campus_id' => 1,
        ]);
    }
}
