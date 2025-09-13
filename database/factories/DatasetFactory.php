<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dataset>
 */
class DatasetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domains = [
            'Machine Learning', 'Data Mining', 'Computer Vision', 'Natural Language Processing',
            'Healthcare', 'Finance', 'Education', 'Social Sciences', 'Bioinformatics',
            'Climate Science', 'Economics', 'Psychology'
        ];

        $tasks = [
            'Classification', 'Regression', 'Clustering', 'Anomaly Detection',
            'Time Series Analysis', 'Text Analysis', 'Image Recognition',
            'Sentiment Analysis', 'Recommendation Systems', 'Prediction'
        ];

        $keywords = [
            'machine learning', 'data analysis', 'artificial intelligence', 'statistical analysis',
            'big data', 'predictive modeling', 'deep learning', 'neural networks',
            'data visualization', 'pattern recognition'
        ];

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(random_int(3, 8)),
            'description' => fake()->paragraphs(random_int(2, 4), true),
            'domain' => fake()->randomElement($domains),
            'task' => fake()->randomElement($tasks),
            'license' => fake()->randomElement(['CC BY 4.0', 'CC BY-SA 4.0', 'MIT', 'GPL-3.0', 'Apache 2.0']),
            'doi' => fake()->optional(0.3)->regexify('10\.1000/[0-9]{3}'),
            'access_level' => fake()->randomElement(['public', 'restricted', 'private']),
            'collaboration_type' => fake()->randomElement(['local', 'national', 'international']),
            'status' => fake()->randomElement(['draft', 'submitted', 'under_review', 'approved', 'published']),
            'keywords' => fake()->randomElements($keywords, random_int(3, 6)),
            'contributors' => fake()->optional(0.4)->randomElements([
                fake()->name(), fake()->name(), fake()->name()
            ], random_int(1, 3)),
            'version' => fake()->randomElement(['1.0', '1.1', '1.2', '2.0']),
            'download_count' => fake()->numberBetween(0, 1000),
            'citation_count' => fake()->numberBetween(0, 50),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * Indicate that the dataset is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'access_level' => 'public',
            'published_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'download_count' => fake()->numberBetween(10, 1000),
            'citation_count' => fake()->numberBetween(1, 50),
        ]);
    }

    /**
     * Indicate that the dataset is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
            'download_count' => 0,
            'citation_count' => 0,
        ]);
    }

    /**
     * Indicate that the dataset is international collaboration.
     */
    public function international(): static
    {
        return $this->state(fn (array $attributes) => [
            'collaboration_type' => 'international',
            'contributors' => fake()->randomElements([
                fake()->name() . ' (MIT)', 
                fake()->name() . ' (Oxford)', 
                fake()->name() . ' (Stanford)'
            ], random_int(1, 2)),
        ]);
    }
}