<?php

namespace Database\Seeders;

use App\Services\LoanModeService;
use Illuminate\Database\Seeder;

class LoanModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(LoanModeService $service)
    {
        collect([
            ['slug' => 'in_house', 'name' => 'In House', 'renewable' => false, 'charges_fine' => false, 'has_grace_period' => false, 'is_active' => true],
            ['slug' => 'take_home', 'name' => 'Take Home', 'renewable' => true, 'charges_fine' => true, 'has_grace_period' => true, 'is_active' => true],
            ['slug' => 'reserve', 'name' => 'Reservation', 'renewable' => false, 'charges_fine' => false, 'has_grace_period' => false, 'is_active' => true],
        ])->each(function ($d) use ($service) {
            $service->create($d);
        });
    }
}
