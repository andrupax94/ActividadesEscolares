<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::insert([
            ['full_name' => 'Ana López', 'grade' => '5A', 'age' => 10],
            ['full_name' => 'Carlos Pérez', 'grade' => '6B', 'age' => 11],
            ['full_name' => 'Lucía Gómez', 'grade' => '4C', 'age' => 9],
            ['full_name' => 'Miguel Torres', 'grade' => '5A', 'age' => 10],
        ]);
    }
}
