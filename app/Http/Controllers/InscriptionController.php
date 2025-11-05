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
        $query = $request->input('q');

        $inscriptions = Inscription::query()->with(['student', 'activity']);

        if ($query) {
            $inscriptions->where(function ($q) use ($query) {
                $fillable = (new Inscription)->getFillable();

                if (!empty($fillable)) {
                    foreach ($fillable as $field) {
                        $q->orWhere($field, 'LIKE', "%{$query}%");
                    }
                }

                $q->orWhereHas('student', function ($s) use ($query) {
                    $s->where('full_name', 'LIKE', "%{$query}%");
                });

                $q->orWhereHas('activity', function ($a) use ($query) {
                    $a->where('name', 'LIKE', "%{$query}%");
                });
            });
        }

        $inscriptions = $inscriptions->paginate(10);

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
