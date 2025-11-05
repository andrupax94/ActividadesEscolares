<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Inscription;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function students(Request $request)
    {
        $query = $request->input('q');
        $students = Student::query();

        if ($query) {
            foreach ((new Student)->getFillable() as $field) {
                $students->orWhere($field, 'LIKE', "%{$query}%");
            }
        }

        $pdf = Pdf::loadView('students.pdf', [
            'students' => $students->get()
        ]);

        return $pdf->download('listado_alumnos.pdf');
    }

    public function activities(Request $request)
    {
        $query = $request->input('q');
        $activities = Activity::query();

        if ($query) {
            foreach ((new Activity)->getFillable() as $field) {
                $activities->orWhere($field, 'LIKE', "%{$query}%");
            }
        }

        $pdf = Pdf::loadView('activities.pdf', [
            'activities' => $activities->get()
        ]);

        return $pdf->download('listado_actividades.pdf');
    }

    public function inscriptions(Request $request)
    {
        $query = $request->input('q');
        $inscriptions = Inscription::with(['student', 'activity']);

        if ($query) {
            $inscriptions->where(function ($q) use ($query) {
                foreach ((new Inscription)->getFillable() as $field) {
                    $q->orWhere($field, 'LIKE', "%{$query}%");
                }

                $q->orWhereHas('student', fn($s) => $s->where('full_name', 'LIKE', "%{$query}%"));
                $q->orWhereHas('activity', fn($a) => $a->where('name', 'LIKE', "%{$query}%"));
            });
        }

        $pdf = Pdf::loadView('inscriptions.pdf', [
            'inscriptions' => $inscriptions->get()
        ]);

        return $pdf->download('listado_inscripciones.pdf');
    }
}
