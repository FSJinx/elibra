<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
            ItemTypeSeeder::class,
            ItemTypeCategorySeeder::class,
            LanguageSeeder::class,

            // Dev Seeds
            CampusSeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            ProgramsSeeder::class,
            LibrarianSeeder::class,
            PatronSeeder::class,
            UsersSeeder::class,
            AuthorSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
