<?php

namespace Database\Seeders;

use App\Models\Patron;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'last_name' => 'balico',
                'first_name' => 'reign chryzel',
                'middle_initial' => null,
                'username' => '22-1513',
                'role' => 'patron',
                'email' => 'reign@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'last_name' => 'mamaril',
                'first_name' => 'jef',
                'middle_initial' => 'a',
                'username' => '22-0858',
                'role' => 'patron',
                'email' => 'jef@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'last_name' => 'tobias',
                'first_name' => 'eugene_patron',
                'middle_initial' => 'g',
                'username' => '22-1188',
                'role' => 'patron',
                'email' => 'eugene@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
        ];

        foreach ($students as $student) {
            $user = User::create($student);

            Patron::create([
                'ebc_number' => '0000000'.$user->id,
                'user_id' => $user->id,
                'patron_type_id' => 1,
                'program_id' => 1,
            ]);
        }
    }
}
