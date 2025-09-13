<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['lecturer', 'student']);
        
        return [
            'user_id' => User::factory(),
            'type' => $type,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'department' => fake()->randomElement([
                'Computer Science',
                'Information Systems',
                'Data Science',
                'Software Engineering',
                'Mathematics',
                'Statistics',
                'Business Administration',
                'Engineering'
            ]),
            'faculty' => fake()->randomElement([
                'Faculty of Engineering and Computer Science',
                'Faculty of Mathematics and Natural Sciences',
                'Faculty of Economics and Business',
                'Faculty of Medicine'
            ]),
            'student_id' => $type === 'student' ? fake()->numerify('A11.####.#####') : fake()->numerify('NIP########'),
            'orcid' => fake()->optional(0.3)->regexify('[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}'),
            'bio' => fake()->optional(0.7)->paragraph(2),
            'phone' => fake()->optional(0.8)->phoneNumber(),
            'website' => fake()->optional(0.2)->url(),
        ];
    }

    /**
     * Indicate that the profile is for a lecturer.
     */
    public function lecturer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'lecturer',
            'student_id' => fake()->numerify('NIP########'),
            'orcid' => fake()->optional(0.6)->regexify('[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}'),
            'website' => fake()->optional(0.4)->url(),
        ]);
    }

    /**
     * Indicate that the profile is for a student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'student',
            'student_id' => fake()->numerify('A11.####.#####'),
            'orcid' => fake()->optional(0.1)->regexify('[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}'),
            'website' => fake()->optional(0.1)->url(),
        ]);
    }
}