<?php

namespace Database\Factories;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DatasetFile>
 */
class DatasetFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extensions = ['csv', 'json', 'arff'];
        $extension = fake()->randomElement($extensions);
        $originalFilename = fake()->word() . '_data.' . $extension;
        
        $metadata = [
            'rows' => fake()->numberBetween(100, 10000),
            'columns' => fake()->numberBetween(5, 50),
            'encoding' => 'UTF-8',
            'delimiter' => $extension === 'csv' ? ',' : null,
        ];

        return [
            'dataset_id' => Dataset::factory(),
            'filename' => fake()->uuid() . '.' . $extension,
            'original_filename' => $originalFilename,
            'path' => 'datasets/' . fake()->uuid() . '/' . fake()->uuid() . '.' . $extension,
            'size' => fake()->numberBetween(1024, 52428800), // 1KB to 50MB
            'mime_type' => match($extension) {
                'csv' => 'text/csv',
                'json' => 'application/json',
                'arff' => 'text/plain',
                default => 'application/octet-stream',
            },
            'extension' => $extension,
            'metadata' => $metadata,
            'is_primary' => fake()->boolean(70), // 70% chance of being primary
        ];
    }

    /**
     * Indicate that the file is the primary dataset file.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }

    /**
     * Indicate that the file is a CSV file.
     */
    public function csv(): static
    {
        return $this->state(fn (array $attributes) => [
            'extension' => 'csv',
            'mime_type' => 'text/csv',
            'original_filename' => fake()->word() . '_data.csv',
            'filename' => fake()->uuid() . '.csv',
            'metadata' => [
                'rows' => fake()->numberBetween(100, 10000),
                'columns' => fake()->numberBetween(5, 50),
                'encoding' => 'UTF-8',
                'delimiter' => ',',
            ],
        ]);
    }
}