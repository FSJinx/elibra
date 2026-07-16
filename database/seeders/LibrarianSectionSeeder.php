<?php

namespace Database\Seeders;

use App\Models\LibrarianSection;
use Illuminate\Database\Seeder;

class LibrarianSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $librarianSections = [
            ['name' => 'branch head'],
            ['name' => 'library admin'],
            ['name' => 'general'],
            ['name' => 'circulation'],
            ['name' => 'technical'],
            ['name' => 'reference'],
            ['name' => 'e-resources'],
            ['name' => 'filipiniana'],
            ['name' => 'academics'],
            ['name' => 'periodical'],
            ['name' => 'museum'],
            ['name' => 'serials'],
            ['name' => 'staff'],
        ];

        foreach ($librarianSections as $section) {
            LibrarianSection::create($section);
        }
    }
}
