<?php

namespace Database\Seeders;

use App\Models\Inscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inscription::insert([
            ['student_id' => 1, 'activity_id' => 1],
            ['student_id' => 1, 'activity_id' => 2],
            ['student_id' => 2, 'activity_id' => 3],
            ['student_id' => 3, 'activity_id' => 1],
            ['student_id' => 4, 'activity_id' => 4],
        ]);
    }
}
