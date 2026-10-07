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
            // ========= PRODUCTION SEEDS =========

            SuperAdminSeeder::class,
            ItemTypeSeeder::class,
            ItemTypeCategorySeeder::class,
            // PermissionSeeder::class,
            PatronTypeSeeder::class,
            // SystemSeeder::class,
            // SectionsSeeder::class,
            LanguageSeeder::class,
            AuthorshipSeeder::class,

            // ========= DEVELOPMENT SEEDS =========

            CampusSeeder::class,
            LibrarySeeder::class,
            DepartmentSeeder::class,
            ProgramsSeeder::class,
            UsersSeeder::class,
            AuthorSeeder::class,
            // AcquisitionSeeder::class,
            // ItemSeeder::class,
            // AcquisitionLinesSeeder::class,
        ]);
    }
}
