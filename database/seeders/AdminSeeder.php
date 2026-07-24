<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'uuid' => Str::uuid()->toString(),
            'last_name' => 'Echague',
            'first_name' => 'Campus Admin',
            'sex' => 'male',
            'role' => 'admin',
            'username' => 'admin',
            'email' => 'isue@isu.edu.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('elibra2026'),
        ]);

        $permissions = [
            'page.admin.all',
            'manage.create',
            'manage.update',
            'manage.delete',
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
