<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PatronType;

class PatronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $types = [
                [
                    'key' => 'student',
                    'name' => 'Student'
                ],
                [
                    'key' => 'faculty',
                    'name' => 'Faculty'
                ],
                [
                    'key' => 'external',
                    'name' => 'External'
                ]
            ];
    
            foreach ($types as $type) {
                PatronType::updateOrCreate(
                    ['key' => $type['key']],
                    $type
                );
            }
    }
}
