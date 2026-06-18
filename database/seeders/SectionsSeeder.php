<?php

namespace Database\Seeders;

use App\Models\Sections;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => 'serials'],
            ['name' => 'general collections'],
            ['name' => 'reference'],
            ['name' => 'filipiniana'],
            ['name' => 'academic researches'],
            ['name' => 'e-library'],
            ['name' => 'audio-visual'],
            ['name' => 'discussion area'],
            ['name' => 'learning resource management center'],
            ['name' => 'mini-museum'],
        ];

        foreach ($sections as $section) {
            Sections::create($section);
        }
    }
}
