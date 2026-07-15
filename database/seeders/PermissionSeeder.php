<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (glob(database_path('seeders/permissions/*PermissionSeeder.php')) as $file) {
            $class = 'Database\\Seeders\\Permissions\\'.basename($file, '.php');
            $this->call($class);
        }
    }
}
