<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InscriptionController;

// Actividades – API CRUD
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');

// Alumnos – API CRUD
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

// Inscripciones – solo creación y listado
Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::post('/inscriptions', [InscriptionController::class, 'store'])->name('inscriptions.store');
