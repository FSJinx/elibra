<?php

namespace Database\Seeders;

use App\Models\LibraryRole;
use Illuminate\Database\Seeder;

class LibraryRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libraryRoles = [
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

        foreach ($libraryRoles as $role) {
            LibraryRole::create($role);
        }
    }
}
