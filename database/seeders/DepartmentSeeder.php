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
        $departments = [
            ['name' => 'College of Computing Studies, Information, and Communication Technology', 'code' => 'ccsict', 'campus_id' => 1],
            ['name' => 'College of Engineering and Technology', 'code' => 'cet', 'campus_id' => 1],
            ['name' => 'College of Education', 'code' => 'ced', 'campus_id' => 1],
            ['name' => 'College of Arts and Sciences', 'code' => 'cas', 'campus_id' => 1],
            ['name' => 'College of Business Accountancy and Public Administration', 'code' => 'cbapa', 'campus_id' => 1],
            ['name' => 'College of Agriculture', 'code' => 'ca', 'campus_id' => 1],
            ['name' => 'Department of Veterinary Medicine', 'code' => 'dvm', 'campus_id' => 1],
        ];
        
        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
