<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::all();

        return $request->expectsJson()
            ? response()->json($students)
            : view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StudentRequest $request)
    {
        $student = Student::create($request->validated());

        return JsonOrViewChecker($request, 'students.index', [
            'student' => $student,
            'message' => 'Alumno creado correctamente'
        ], 201);
    }

    public function show(Request $request, Student $student)
    {
        $student->load('activities');

        return $request->expectsJson()
            ? response()->json($student)
            : view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return JsonOrViewChecker($request, 'students.index', [
            'student' => $student,
            'message' => 'Alumno actualizado correctamente'
        ]);
    }

    public function destroy(Request $request, Student $student)
    {
        $student->delete();

        return JsonOrViewChecker($request, 'students.index', [
            'message' => 'Alumno eliminado correctamente'
        ]);
    }
}
