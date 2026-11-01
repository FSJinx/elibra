<?php

namespace Database\Factories;

use App\Models\Academic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Academic>
 */
class AcademicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topics = [
            'Machine Learning' => ['Student Performance', 'Predictive Accuracy'],
            'Cybersecurity' => ['Safe Practices', 'Data Privacy'],
            'IoT' => ['Environmental Monitoring', 'Automation'],
            'Disaster Preparedness' => ['Risk Reduction', 'Community Resilience'],
            'Financial Literacy' => ['Mobile Payments', 'Savings Behavior'],
            'Criminal Justice' => ['Case Documentation', 'Legal Research'],
            'E-Learning' => ['Student Engagement', 'Learning Outcomes'],
            'Digital Records' => ['Records Management', 'Document Retrieval'],
        ];
        $topic = fake()->randomElement(array_keys($topics));
        $focus = fake()->randomElement($topics[$topic]);
        $method = fake()->randomElement(['Development of', 'Assessment of', 'Evaluation of', 'Factors Affecting', 'Impact of', 'Adoption of', 'Perception on', 'Framework for']);
        $where = fake()->randomElement(['University Students', 'Rural Communities', 'Local Government Units', 'Public Schools', 'Small Businesses', 'Barangay Offices', 'Teachers', 'Online Sellers']);

        return [
            'title' => "{$method} {$topic} Among {$where}",
            'description' => "This study examines {$topic} in relation to {$focus} among {$where}, using survey and documentary analysis.",
            'call_number' => fake()->numerify('###.##').' '.fake()->bothify('?##'),
            'keywords' => [$topic, $focus, $where],
        ];
    }
}
