<?php

namespace Database\Factories;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurationReview>
 */
class CurationReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'approved', 'rejected', 'needs_revision']);
        
        $checklist = [
            'data_quality' => fake()->boolean(80),
            'metadata_complete' => fake()->boolean(90),
            'file_format_valid' => fake()->boolean(95),
            'description_clear' => fake()->boolean(85),
            'license_specified' => fake()->boolean(90),
            'ethical_compliance' => fake()->boolean(95),
        ];

        return [
            'dataset_id' => Dataset::factory(),
            'reviewer_id' => User::factory()->state(['role' => 'curator']),
            'status' => $status,
            'notes' => $status !== 'pending' ? fake()->paragraphs(random_int(1, 3), true) : null,
            'checklist' => $checklist,
            'reviewed_at' => $status !== 'pending' ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'notes' => fake()->sentence() . ' Dataset meets all quality standards.',
            'reviewed_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'checklist' => [
                'data_quality' => true,
                'metadata_complete' => true,
                'file_format_valid' => true,
                'description_clear' => true,
                'license_specified' => true,
                'ethical_compliance' => true,
            ],
        ]);
    }

    /**
     * Indicate that the review is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'notes' => 'Dataset does not meet quality standards. ' . fake()->sentence(),
            'reviewed_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'checklist' => [
                'data_quality' => false,
                'metadata_complete' => fake()->boolean(50),
                'file_format_valid' => fake()->boolean(70),
                'description_clear' => fake()->boolean(60),
                'license_specified' => fake()->boolean(80),
                'ethical_compliance' => fake()->boolean(90),
            ],
        ]);
    }
}