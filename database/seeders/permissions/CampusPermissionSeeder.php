<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\CampusPermission;
use Illuminate\Database\Seeder;

class CampusPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (CampusPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
