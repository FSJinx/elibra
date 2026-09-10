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
            SectionsSeeder::class,
            ItemTypeSeeder::class,
            ItemTypeCategorySeeder::class,
            LanguageSeeder::class,
            AuthorshipSeeder::class,
            
            // Dev Seeds
            CampusSeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            BranchSectionSeeder::class,
            ProgramsSeeder::class,
            UsersSeeder::class,
            AuthorSeeder::class,
            AcquisitionSeeder::class,
            ItemSeeder::class,
            AcquisitionLinesSeeder::class,
        ]);
    }
}
