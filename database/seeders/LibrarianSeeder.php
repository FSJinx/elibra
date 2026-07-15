<?php

namespace Database\Seeders;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

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
                'librarian' => ['role' => 'admin'],
            ],
            [
                'last_name' => 'tobias',
                'first_name' => 'eugene_librarian',
                'middle_initial' => 'd',
                'username' => 'eugene_pogi',
                'role' => 'librarian',
                'email' => 'librarian_eugene@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
                'librarian' => ['role' => 'staff'],
            ],
            [
                'last_name' => 'christian',
                'first_name' => 'christian',
                'middle_initial' => 'd',
                'username' => 'christian',
                'role' => 'librarian',
                'email' => 'christian@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
                'librarian' => ['role' => 'staff'],
            ],
        ];

        foreach ($users as $user) {
            $librarian = User::create(Arr::except($user, ['librarian']));

            $lib = $user['librarian'];

            Librarian::create([
                'user_id' => $librarian->id,
                'role' => $lib['role'],
                'branch_id' => 1,
            ]);
        }

    }
}
