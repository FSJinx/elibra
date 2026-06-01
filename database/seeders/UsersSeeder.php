<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'first_name' => 'System Administrator',
            'sex' => 'male',
            'role' => '0',
            'username' => 'admin',
            'password' => Hash::make('oneisu'),
        ]);
    }
}
