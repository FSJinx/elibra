<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\DepartmentPermission;
use Illuminate\Database\Seeder;

class DepartmentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DepartmentPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
