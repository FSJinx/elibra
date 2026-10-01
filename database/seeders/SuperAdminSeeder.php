<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        collect([
            [
                'uuid' => Str::uuid()->toString(),
                'first_name' => 'Super Administrator',
                'sex' => 'male',
                'role' => 'super_admin',
                'username' => 'super',
                'email' => 'elibra@isu.edu.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('elibra2026'),
            ],
        ])->each(fn ($user) => User::create($user));
    }
}
