<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid()->toString(),
            'last_name' => 'dela cruz',
            'first_name' => 'mark angelo',
            'middle_initial' => 'd',
            'username' => 'angelo',
            'role' => 'admin',
            'email' => 'angelo@isu.edu.ph',
            'password' => bcrypt('elibra2026'),
            'campus_id' => 1,
        ]);
        
        // $permissions = [
        //     'page.admin.all',
        //     'manage.create',
        //     'manage.update',
        //     'manage.delete',
        // ];

        // foreach ($permissions as $permission) {
        //     $permit = Permission::query()->where('permission', $permission)->first();

        //     if ($permit) {
        //         UserPermission::create([
        //             'user_id' => $user->id,
        //             'permission_id' => $permit->id,
        //         ]);
        //     }
        // }
    }
}
