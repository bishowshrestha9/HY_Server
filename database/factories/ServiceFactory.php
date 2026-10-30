<?php

namespace Database\Factories;

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'benefits' => fake()->randomElements(['Stress Relief', 'Flexibility', 'Strength', 'Mindfulness'], 3),
            'yoga_type' => fake()->randomElement(['basic', 'intermediate', 'advanced']),
            'price' => fake()->randomFloat(2, 10, 500),
            'capacity' => fake()->numberBetween(5, 50),
            'currency' => fake()->randomElement(['USD', 'NPR']),
            'instructor_id' => Instructor::factory(),
            'images' => [fake()->imageUrl(640, 480, 'yoga', true), fake()->imageUrl(640, 480, 'nature', true)],
            'is_active' => true,
            'class_schedule' => [
                'Monday' => ['07:00-08:00', '18:00-19:00'],
                'Wednesday' => ['06:00-07:30']
            ],
            'session_time' => fake()->randomElement(['60 mins', '90 mins', '45 mins']),
        ];
    }
}
