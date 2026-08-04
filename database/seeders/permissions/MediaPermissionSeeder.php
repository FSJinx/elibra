<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\MediaPermission;
use Illuminate\Database\Seeder;

class MediaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (MediaPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
