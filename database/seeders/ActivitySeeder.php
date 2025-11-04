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
            ['name' => 'Robotics', 'description' => 'Learn to build and program robots.', 'day' => 'Monday', 'hour' => '16:00 - 17:30'],
            ['name' => 'Chess', 'description' => 'Improve your strategy and tactics.', 'day' => 'Tuesday', 'hour' => '17:00 - 18:30'],
            ['name' => 'Painting', 'description' => 'Explore your creativity with colors.', 'day' => 'Wednesday', 'hour' => '15:30 - 17:00'],
            ['name' => 'English', 'description' => 'Practice conversational English.', 'day' => 'Thursday', 'hour' => '16:30 - 18:00'],
        ]);
    }
}
