<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\UserPermission;
use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
