<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\System;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systems = [
            ['key' => 'brand_logo',    'description' => 'The logo of the library or system', 'value' => 'null', 'selections' => null],
            ['key' => 'brand_name',    'description' => 'The name of the library or system', 'value' => 'null', 'selections' => null],
            ['key' => 'brand_email',   'description' => 'The contact email address for the library or system', 'value' => 'elibra@isu.edu.ph', 'selections' => null],

            [
                'key' => 'subscription_visibility',   
                'description' => 'The visibility setting for subscriptions', 
                'value' => 'private', 
                'selections' =>  [
                            'public' => [
                                    'value' => 'public',
                                    'description' => 'Subscriptions are visible to all users, including guests.'
                                ],
                            'private' => [
                                    'value' => 'private',
                                    'description' => 'Subscriptions are only visible to authenticated users.'
                                ],
                            ],
            ],

            ['key' => 'maintenance_mode',   'description' => 'The maintenance mode setting for the system', 'value' => 'false', 'selections' => null],

        ];
        foreach ($systems as $system) {
            System::create($system);
        }
    }
}
