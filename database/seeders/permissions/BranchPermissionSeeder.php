<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\BranchPermission;
use Illuminate\Database\Seeder;

class BranchPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (BranchPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
