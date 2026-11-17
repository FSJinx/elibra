<?php

namespace Database\Seeders;

use App\Services\PublisherService;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(PublisherService $service): void
    {
        for ($i = 0; $i < 50; $i++) {
            $service->create([
                'name' => fake()->name(),
                'address' => fake()->address(),
            ]);
        }
    }
}
