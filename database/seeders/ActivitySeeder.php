<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::insert([
            [
                'name' => 'Robótica',
                'description' => 'Aprende a construir y programar robots.',
                'day' => 'Lunes',
                'hour' => '16:00 - 17:30'
            ],
            [
                'name' => 'Ajedrez',
                'description' => 'Mejora tu estrategia y tácticas de juego.',
                'day' => 'Martes',
                'hour' => '17:00 - 18:30'
            ],
            [
                'name' => 'Pintura',
                'description' => 'Explora tu creatividad con los colores.',
                'day' => 'Miércoles',
                'hour' => '15:30 - 17:00'
            ],
            [
                'name' => 'Inglés',
                'description' => 'Practica inglés conversacional de forma divertida.',
                'day' => 'Jueves',
                'hour' => '16:30 - 18:00'
            ],
        ]);
    }
}
