<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\AcquisitionLinePermission;
use Illuminate\Database\Seeder;

class AcquisitionLinePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AcquisitionLinePermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
