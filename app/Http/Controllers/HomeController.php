<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalInscriptions = Inscription::count();
        $lastInscription = Inscription::latest()->first();

        $totalStudents = Student::count();
        $averageAge = Student::avg('age');

        $totalActivities = Activity::count();
        $mostPopularActivity = Activity::withCount('students')
            ->orderByDesc('students_count')
            ->first();

        return view('home.index', [
            'totalInscriptions' => $totalInscriptions,
            'lastInscriptionDate' => $lastInscription?->created_at,
            'totalStudents' => $totalStudents,
            'averageAge' => round($averageAge, 1),
            'totalActivities' => $totalActivities,
            'mostPopularActivity' => $mostPopularActivity,
        ]);
    }
}
