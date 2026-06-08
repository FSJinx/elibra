<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'last_name' => 'Castillo',
            'first_name' => 'Wendell',
            'middle_initial' => 'M',
            'username' => 'infra',
            'email' => 'infra@gmail.com',
            'password' => bcrypt('elibra2026'),
        ]);
    }
}
