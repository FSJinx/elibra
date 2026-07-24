<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
         $user = User::create([
            'uuid' => Str::uuid()->toString(),
            'first_name' => 'System Administrator',
            'sex' => 'male',
            'role' => 'super admin',
            'username' => 'super',
            'email' => 'elibra@isu.edu.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('elibra2026'),
        ]);
    }
}
