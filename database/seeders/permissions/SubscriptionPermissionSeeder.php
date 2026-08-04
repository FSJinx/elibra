<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\SubscriptionPermission;
use Illuminate\Database\Seeder;

class SubscriptionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SubscriptionPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
