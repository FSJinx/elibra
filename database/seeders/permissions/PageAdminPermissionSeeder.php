<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\PageAdminPermission;
use Illuminate\Database\Seeder;

class PageAdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PageAdminPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
