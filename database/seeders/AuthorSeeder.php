<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {

        for ($i = 0; $i < rand(10, 25); $i++) {
            Author::firstOrCreate(
                [
                    'first_name' => fake()->firstName(),
                    'middle_name' => fake()->randomLetter(),
                    'last_name' => fake()->lastName(),
                    'suffix' => fake()->optional()->randomElement(['Jr.', 'Sr.']),
                ]
            );
        }
    }
}
