<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\AcquisitionPermission;
use Illuminate\Database\Seeder;

class AcquisitionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AcquisitionPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
