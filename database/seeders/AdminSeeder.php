<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'System Administrator',
            'sex' => 'male',
            'role' => 'admin',
            'username' => 'admin',
            'email' => 'isue@isu.edu.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('elibra2026'),
        ]);

        $permissions = [
            'page.admin.all',
            'user.all',
            'campus.all',
            'branch.all',
        ];

        foreach ($permissions as $permission) {
            $permit = Permission::query()->where('permission', $permission)->first();

            if ($permit) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_id' => $permit->id,
                ]);
            }
        }
    }
}
