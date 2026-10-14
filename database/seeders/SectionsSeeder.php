<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Library;
use App\Models\Sections;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campus = Campus::where('code', 'ISU-E')->pluck('id')->first();
        $library = Library::where('campus_id', $campus)->pluck('id');

        collect([
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
        ])->each(function ($section) use ($library) {
            Sections::create([
                ...$section,
                'library_id' => $library->random(),
            ]);
        });
    }
}
