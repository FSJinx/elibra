<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\ManagePermission;
use Illuminate\Database\Seeder;

class ManagePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ManagePermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
