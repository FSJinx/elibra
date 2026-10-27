<?php

namespace Database\Seeders;

use App\Models\LoanMode;
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
            ['slug' => 'in_house', 'name' => 'In House'],
            ['slug' => 'take_home', 'name' => 'Take Home'],
            ['slug' => 'reserve', 'name' => 'Reservation'],
        ])->each(function ($d) use ($service) {
            $service->create($d);
        });
    }
}
