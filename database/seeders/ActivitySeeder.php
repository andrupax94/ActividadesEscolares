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
                'days_string' => 'Lunes',
                'hour' => '16:00 - 17:30'
            ],
            [
                'name' => 'Ajedrez',
                'description' => 'Mejora tu estrategia y tácticas de juego.',
                'days_string' => 'Martes',
                'hour' => '17:00 - 18:30'
            ],
            [
                'name' => 'Pintura',
                'description' => 'Explora tu creatividad con los colores.',
                'days_string' => 'Miércoles',
                'hour' => '15:30 - 17:00'
            ],
            [
                'name' => 'Inglés',
                'description' => 'Practica inglés conversacional de forma divertida.',
                'days_string' => 'Jueves',
                'hour' => '16:30 - 18:00'
            ],
        ]);
        Activity::create([
            'name' => 'Yoga',
            'description' => 'Sesión de relajación y estiramiento',
            'days_string' => 'Lunes,Miércoles,Viernes',
            'hour' => '08:00 - 09:00',
        ]);

        Activity::create([
            'name' => 'Fútbol',
            'description' => 'Entrenamiento físico y táctico',
            'days_string' => 'Martes,Jueves',
            'hour' => '17:00 - 18:30',
        ]);
    }
}
