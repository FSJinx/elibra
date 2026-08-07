<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\SerialPermission;
use Illuminate\Database\Seeder;

class SerialPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SerialPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
