<?php

namespace Database\Seeders;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Database\Seeder;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'last_name' => 'dela cruz',
                'first_name' => 'mark angelo',
                'middle_initial' => 'd',
                'username' => 'angelo',
                'role' => 'librarian',
                'email' => 'angelo@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'last_name' => 'user',
                'first_name' => 'test',
                'middle_initial' => 'd',
                'username' => 'test1',
                'role' => 'librarian',
                'email' => 'test1@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'last_name' => 'user 2',
                'first_name' => 'test',
                'middle_initial' => 'd',
                'username' => 'test2',
                'role' => 'librarian',
                'email' => 'test2@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
        ];

        foreach ($users as $user) {
            $librarian = User::create($user);

            Librarian::create([
                'user_id' => $librarian->id,
                'branch_id' => 1,
                'primary_role_id' => 1,
            ]);
        }

    }
}
