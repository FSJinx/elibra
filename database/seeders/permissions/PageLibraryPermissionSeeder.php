<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\PageLibraryPermission;
use Illuminate\Database\Seeder;

class PageLibraryPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PageLibraryPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
