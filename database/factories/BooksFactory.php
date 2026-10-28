<?php

namespace Database\Factories;

use App\Models\Books;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topic = fake()->randomElement(['Machine Learning', 'Cybersecurity', 'Cloud Computing', 'Data Analytics', 'Criminal Law', 'Public Health', 'Renewable Energy', 'Financial Management', 'Educational Psychology', 'Software Engineering']);
        $title = fake()->randomElement(["Introduction to {$topic}", "Fundamentals of {$topic}", "{$topic}: Principles and Practice", "Handbook of {$topic}", "Essentials of {$topic}", "Applied {$topic}"]);

        return [
            'title' => $title,
            'description' => "A comprehensive reference on {$topic}, covering core concepts, practical applications, and current issues.",
            'call_number' => fake()->numerify('###.##').' '.fake()->bothify('?##').' '.fake()->year(),
            'isbn' => fake()->isbn13(),
            'edition' => fake()->randomElement(['1st', '2nd', '3rd', '4th']),
            'publication_year' => fake()->numberBetween(2005, 2025),
            'keywords' => [$topic, 'Textbook'],
        ];
    }
}
