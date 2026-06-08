<?php

namespace Database\Seeders;

use App\Models\Programs;
use Illuminate\Database\Seeder;

class ProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programs::create([
            'name' => 'bachelor of science in information technology',
            'code' => 'bsit',
            'department_id' => 1,
        ]);
    }
}
