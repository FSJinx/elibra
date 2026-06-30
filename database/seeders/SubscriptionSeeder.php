<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            ['name' => 'EBSCO', 'description' => 'EBSCO is a leading provider of research databases, e-journals, magazine subscriptions, e-books and discovery service for academic libraries, public libraries, corporations, schools, government and medical institutions.', 'link' => 'sample-link.com', 'thumbnail_id' => 1],
        ];

        foreach ($subscriptions as $subscription) {
                Subscription::create($subscription);
        }
    }  
          
}
