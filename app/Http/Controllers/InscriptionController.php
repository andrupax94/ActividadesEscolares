<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscriptionRequest;
use App\Models\Activity;
use App\Models\Inscription;
use App\Models\Student;

class InscriptionController extends Controller
{
    public function index()
    {
        $inscriptions = Inscription::with(['student', 'activity'])->get();
        return view('inscriptions.index', compact('inscriptions'));
    }

    public function create()
    {
        $students = Student::all();
        $activities = Activity::all();
        return view('inscriptions.create', compact('students', 'activities'));
    }

    public function store(InscriptionRequest $request)
    {
        Inscription::create($request->validated());
        return redirect()->route('inscriptions.index');
    }
}
