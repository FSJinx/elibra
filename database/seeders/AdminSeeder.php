<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Users::create([
            'last_name' => 'Castillo',
            'first_name' => 'Wendell',
            'middle_initial' => 'M',
            'username' => 'infra',
            'email' => 'infra@gmail.com',
            'password' => bcrypt('infra12345'),
        ]);
    }
}
