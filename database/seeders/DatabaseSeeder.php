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
            // Prod Seeds
            PermissionSeeder::class,
            PatronTypeSeeder::class,
            SystemSeeder::class,
            SuperAdminSeeder::class,
            AdminSeeder::class,
            SectionsSeeder::class,

            // Dev Seeds
            CampusSeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            ProgramsSeeder::class,
            LibrarianSeeder::class,
            PatronSeeder::class,
            UsersSeeder::class,
            // ItemSeeder::class,
            // AcademicSeeder::class,
        ]);
    }
}
