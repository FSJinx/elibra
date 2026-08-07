<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\AcademicPermission;
use Illuminate\Database\Seeder;

class AcademicPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AcademicPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
