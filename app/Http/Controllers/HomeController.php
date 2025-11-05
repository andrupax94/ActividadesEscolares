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
        $lastInscription = Inscription::latest()->with(['student', 'activity'])->first();

        $totalStudents = Student::count();
        $averageAge = round(Student::avg('age'), 1);
        $minAge = Student::min('age');
        $maxAge = Student::max('age');

        $totalActivities = Activity::count();
        $mostPopularActivity = Activity::withCount('students')
            ->orderByDesc('students_count')
            ->first();

        $lastActivityInscribed = $lastInscription?->activity?->name;
        $mostActiveStudent = Student::withCount('inscriptions')
            ->orderByDesc('inscriptions_count')
            ->first();

        $averageInscriptionsPerActivity = round(
            $totalActivities ? $totalInscriptions / $totalActivities : 0,
            1
        );

        $recentInscriptions = Inscription::where('created_at', '>=', now()->subMonth())->count();

        return view('home.index', [
            'totalInscriptions' => $totalInscriptions,
            'lastInscriptionDate' => $lastInscription?->created_at,
            'lastActivityInscribed' => $lastActivityInscribed,
            'totalStudents' => $totalStudents,
            'averageAge' => $averageAge,
            'minAge' => $minAge,
            'maxAge' => $maxAge,
            'totalActivities' => $totalActivities,
            'mostPopularActivity' => $mostPopularActivity,
            'mostActiveStudent' => $mostActiveStudent,
            'averageInscriptionsPerActivity' => $averageInscriptionsPerActivity,
            'recentInscriptions' => $recentInscriptions,
        ]);
    }
}
