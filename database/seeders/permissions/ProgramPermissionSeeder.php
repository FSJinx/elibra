<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\ProgramPermission;
use Illuminate\Database\Seeder;

class ProgramPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ProgramPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
