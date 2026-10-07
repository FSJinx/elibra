<?php

namespace Database\Seeders;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
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

        Librarian::create([
            'user_id' => $admin->id,
            'library_id' => 1,
        ]);
    }
}
