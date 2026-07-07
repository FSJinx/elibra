<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Academic;

class AcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academics = [
            [
                'call_number' => '123456789',
                'language' => 'English',
                'category' => 'undergraduate thesis',
                'publication_year' => 2020,
                'subjects' => ['Computer Science', 'Software Engineering'],
                'item_id' => 1,
                'department_id' => 1,
            ],
            [
                'call_number' => '987654321',
                'language' => 'English',
                'category' => 'graduate thesis',
                'publication_year' => 2021,
                'subjects' => ['Data Science', 'Machine Learning'],
                'item_id' => 2,
                'department_id' => 2,
            ],
        ];
        foreach ($academics as $academic) {
            Academic::create($academic);
        }
    }
}
