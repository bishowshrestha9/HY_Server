<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'profession' => fake()->jobTitle(),
            'experience' => fake()->numberBetween(1, 40),
            'bio' => fake()->paragraph(),
            'specialities' => fake()->randomElement(['Hatha Yoga', 'Vinyasa Flow', 'Ashtanga', 'Meditation', 'Pranayama']),
            'certifications' => [fake()->sentence(), fake()->sentence()],
            'image' => fake()->imageUrl(640, 480, 'people', true),
        ];
    }
}
