<?php

namespace Database\Seeders\Permissions;

use App\Models\Permission;
use App\Permissions\BookPermission;
use Illuminate\Database\Seeder;

class BookPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (BookPermission::all() as $permission) {
            Permission::create($permission);
        }
    }
}
