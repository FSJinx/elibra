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
            ['code' => 'in_house', 'name' => 'In House'],
            ['code' => 'take_home', 'name' => 'Take Home'],
            ['code' => 'reserve', 'name' => 'Reservation'],
            ['code' => 'reserve_pickup', 'name' => 'reserve_pickup'],
        ];

        foreach ($data as $d) {
            LoanMode::create($d);
        }
    }
}
