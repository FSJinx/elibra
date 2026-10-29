<?php

namespace Database\Factories;

use App\Models\Serial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Serial>
 */
class SerialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topic = fake()->randomElement(['Information Technology', 'Cybersecurity', 'Education', 'Criminal Justice', 'Public Health', 'Agriculture', 'Business Management', 'Engineering', 'Environmental Science', 'Social Sciences']);
        $title = fake()->randomElement(["Journal of {$topic}", "International Review of {$topic}", "{$topic} Quarterly", "Philippine Journal of {$topic}", "{$topic} Research Bulletin"]);

        return [
            'title' => $title,
            'description' => "A periodical publishing research articles and reviews on {$topic}.",
            'call_number' => 'SER '.fake()->numerify('###.##').' '.fake()->bothify('?##'),
            'issn' => fake()->numerify('####-####'),
            'frequency' => fake()->randomElement(['Monthly', 'Quarterly', 'Biannual', 'Annual']),
            'keywords' => [$topic, 'Journal'],
        ];
    }
}
