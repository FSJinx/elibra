<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\BranchSectionPermission;
use Illuminate\Database\Seeder;

class BranchSectionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (BranchSectionPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
