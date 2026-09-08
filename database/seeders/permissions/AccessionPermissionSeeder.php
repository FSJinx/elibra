<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\AccessionPermission;
use Illuminate\Database\Seeder;

class AccessionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AccessionPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
