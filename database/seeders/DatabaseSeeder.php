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

            AcquisitionModeSeeder::class,
            ItemTypeSeeder::class,
            ItemTypeCategorySeeder::class,
            PatronTypeSeeder::class,
            LanguageSeeder::class,
            LoanModeSeeder::class,
            AuthorshipSeeder::class,

            // ========= DEVELOPMENT SEEDS =========

            CampusSeeder::class,
            LibrarySeeder::class,
            SectionsSeeder::class,
            DepartmentSeeder::class,
            ProgramsSeeder::class,
            UsersSeeder::class,
            
            PermissionSeeder::class,
            SystemSeeder::class,
            PublisherSeeder::class,
            AuthorSeeder::class,
            ItemSeeder::class,
            AcquisitionSeeder::class,
            AcquisitionLinesSeeder::class,
            HoldingSeeder::class,
        ]);
    }
}
