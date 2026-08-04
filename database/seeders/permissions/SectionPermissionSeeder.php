<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\SectionPermission;
use Illuminate\Database\Seeder;

class SectionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SectionPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
