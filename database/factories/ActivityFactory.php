<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'day' => fake()->randomElement(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']),
            'hour' => fake()->time('H:i') . ' - ' . fake()->time('H:i'),
        ];
    }
}
