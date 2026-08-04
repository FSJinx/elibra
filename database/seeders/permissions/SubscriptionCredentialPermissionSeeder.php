<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\SubscriptionCredentialPermission;
use Illuminate\Database\Seeder;

class SubscriptionCredentialPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SubscriptionCredentialPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
