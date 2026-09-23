<?php

namespace Database\Seeders;

use App\Services\AcquisitionService;
use Illuminate\Database\Seeder;

class AcquisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcquisitionService $acquisition): void
    {
        collect([
            [
                'dealer' => 'National Book Store',
                'acquisition_mode' => 'gift',
                'acquisition_date' => fake()->date(),
                'remarks' => '',
                'receiver_user_id' => 4,
            ],
            [
                'dealer' => 'Amazon',
                'acquisition_mode' => 'purchased',
                'acquisition_date' => fake()->date(),
                'remarks' => '',
                'receiver_user_id' => 2,            ],
        ])->each(fn ($data) => $acquisition->create($data));
    }
}
