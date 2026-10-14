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
            LanguageSeeder::class,
            AuthorshipSeeder::class,

            // ========= DEVELOPMENT SEEDS =========

            CampusSeeder::class,
            LibrarySeeder::class,
            SectionsSeeder::class,
            DepartmentSeeder::class,
            ProgramsSeeder::class,
            UsersSeeder::class,
            AuthorSeeder::class,
            ItemSeeder::class,
            AcquisitionSeeder::class,
            AcquisitionLinesSeeder::class,
        ]);
    }
}
