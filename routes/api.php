<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InscriptionController;

// Actividades – API CRUD

Route::post('/api/activities', [ActivityController::class, 'store'])->name('api.activities.store');
Route::put('/api/activities/{activity}', [ActivityController::class, 'update'])->name('api.activities.update');
Route::delete('/api/activities/{activity}', [ActivityController::class, 'destroy'])->name('api.activities.destroy');

// Alumnos – API CRUD

Route::post('/api/students', [StudentController::class, 'store'])->name('api.students.store');
Route::put('/api/students/{student}', [StudentController::class, 'update'])->name('api.students.update');
Route::delete('/api/students/{student}', [StudentController::class, 'destroy'])->name('api.students.destroy');

// Inscripciones – solo creación y listado

Route::post('/api/inscriptions', [InscriptionController::class, 'store'])->name('api.inscriptions.store');
Route::delete('/api/inscriptions{inscription}', [InscriptionController::class, 'destroy'])->name('api.inscriptions.destroy');
