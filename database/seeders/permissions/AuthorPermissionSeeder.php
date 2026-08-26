<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\AuthorPermission;
use Illuminate\Database\Seeder;

class AuthorPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AuthorPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
