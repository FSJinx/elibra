<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Patron;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(UserService $service): void
    {
        $campus = Campus::where('code', 'ISU-E')->pluck('id')->first();
        collect([
            [
                'uuid' => Str::uuid()->toString(),
                'last_name' => 'balico',
                'first_name' => 'reign chryzel',
                'middle_initial' => null,
                'username' => '22-1513',
                'sex' => 'male',
                'role' => 'patron',
                'email' => 'reign@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'last_name' => 'mamaril',
                'first_name' => 'jef',
                'middle_initial' => 'a',
                'username' => '22-0858',
                'sex' => 'male',
                'role' => 'patron',
                'email' => 'jef@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
                ],
                [
                    'uuid' => Str::uuid()->toString(),
                    'last_name' => 'tobias',
                    'first_name' => 'eugene_patron',
                    'middle_initial' => 'g',
                    'username' => '22-1188',
                    'sex' => 'male',
                'role' => 'patron',
                'email' => 'eugene@isu.edu.ph',
                'password' => bcrypt('elibra2026'),
            ],
        ])->each(function ($student) use ($campus, $service) {
            $service->create([
                ...$student,
                'campus_id' => $campus,
                'patron_type_id' => 1,
                'program_id' => 1,
            ]);
        });
    }
}
