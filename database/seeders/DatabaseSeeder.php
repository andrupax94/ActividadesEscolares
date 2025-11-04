<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            ActivitySeeder::class,
            StudentSeeder::class,
            InscriptionSeeder::class,
        ]);
        Activity::factory(5)->create();
        Student::factory(10)->create();

        // Crear inscripciones aleatorias entre estudiantes y actividades
        Inscription::factory(20)->create();
    }
}
