<?php

namespace Database\Seeders;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'uuid' => Str::uuid()->toString(),
                'last_name' => 'dela cruz',
                'first_name' => 'mark angelo',
                'middle_initial' => 'd',
                'username' => 'angelo',
                'role' => 'librarian',
                'email' => 'angelo@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'last_name' => 'tobias',
                'first_name' => 'eugene_librarian',
                'middle_initial' => 'd',
                'username' => 'eugene_pogi',
                'role' => 'librarian',
                'email' => 'librarian_eugene@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'last_name' => 'christian',
                'first_name' => 'christian',
                'middle_initial' => 'd',
                'username' => 'christian',
                'role' => 'librarian',
                'email' => 'christian@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
        ];

        foreach ($users as $user) {
            $librarian = User::create(Arr::except($user, ['librarian']));

            Librarian::create([
                'user_id' => $librarian->id,
                'branch_id' => 1,
            ]);
        }

    }
}
