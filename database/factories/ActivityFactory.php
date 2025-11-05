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
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Selecciona entre 1 y 3 días aleatorios y los une por comas
        $diasSeleccionados = $this->faker->randomElements($dias, rand(1, 3));
        $diasString = implode(',', $diasSeleccionados);

        // Genera hora de inicio y fin válidas
        $start = $this->faker->time('H:i');
        $end = $this->faker->time('H:i');

        // Asegura que la hora de fin sea mayor que la de inicio
        if ($start >= $end) {
            [$start, $end] = [$end, $start];
        }

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'days_string' => $diasString,
            'hour' => "$start - $end",
        ];
    }
}
