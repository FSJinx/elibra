<?php

namespace Database\Seeders;

use App\Models\LoanMode;
use Illuminate\Database\Seeder;

class LoanModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['slug' => 'in_house', 'name' => 'In House'],
            ['slug' => 'take_home', 'name' => 'Take Home'],
            ['slug' => 'reserve', 'name' => 'Reservation'],
        ];

        foreach ($data as $d) {
            LoanMode::create($d);
        }
    }
}
