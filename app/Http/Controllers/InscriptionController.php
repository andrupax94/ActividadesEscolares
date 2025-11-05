<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscriptionRequest;
use App\Models\Activity;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $inscriptions = Inscription::orderBy('id', 'desc')->with(['student', 'activity'])->get();

        return $request->expectsJson()
            ? response()->json($inscriptions)
            : view('inscriptions.index', compact('inscriptions'));
    }


    public function create()
    {
        $students = Student::all();
        $activities = Activity::all();

        return view('inscriptions.create', compact('students', 'activities'));
    }

    public function store(InscriptionRequest $request)
    {
        $inscription = Inscription::create($request->validated());

        return JsonOrViewChecker($request, 'inscriptions.index', [
            'inscription' => $inscription,
            'message' => 'Inscripción registrada correctamente'
        ], 201);
    }
    public function destroy(Request $request, Inscription $inscription)
    {
        $inscription->delete();

        return JsonOrViewChecker($request, 'inscriptions.index', [
            'message' => 'Inscripcion eliminada correctamente'
        ]);
    }
}
