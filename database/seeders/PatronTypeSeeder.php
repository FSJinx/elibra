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
        collect([
            [
                'key' => 'sp',
                'name' => 'Student',
                'description' => 'A student of Isabela State University.',
                'validity_period' => 4,
                'has_campus' => true,
                'has_department' => true,
                'has_program' => true,
            ],
            [
                'key' => 'fp',
                'name' => 'Faculty',
                'description' => 'A faculty of Isabela State University.',
                'validity_period' => 10,
                'has_campus' => true,
                'has_department' => true,
                'has_program' => false,
            ],
            [
                'key' => 'gp',
                'name' => 'Guest',
                'description' => 'A guest patron that is not enrolled in any ISU Campus.',
                'validity_period' => 1,
                'has_campus' => false,
                'has_department' => false,
                'has_program' => false,
            ],
        ])->each(function ($type) {
            PatronType::create(
                $type, [
                    'has_campus' => $type['has_campus'],
                    'has_department' => $type['has_campus'] ? $type['has_department'] : false,
                    'has_program' => $type['has_department'] ? $type['has_program'] : false,
                ]
            );
        });
    }
}
