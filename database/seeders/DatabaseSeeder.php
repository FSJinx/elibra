<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CampusSeeder::class,
            BranchSeeder::class,
            SectionsSeeder::class,
            LibraryRoleSeeder::class,
            PatronTypeSeeder::class,
            // UsersSeeder::class,

            AdminSeeder::class,
            LibrarianSeeder::class,
        ]);
    }
}
