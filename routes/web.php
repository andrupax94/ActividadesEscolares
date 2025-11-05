<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PdfController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Actividades – resource
Route::resource('activities', ActivityController::class);

// Alumnos – resource
Route::resource('students', StudentController::class);

// Inscripciones – vistas (parcial)
Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::get('/inscriptions/create', [InscriptionController::class, 'create'])->name('inscriptions.create');
Route::post('/inscriptions', [InscriptionController::class, 'store'])->name('inscriptions.store');
Route::delete('/inscriptions{inscription}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');

Route::get('/pdf/students', [PdfController::class, 'students'])->name('pdf.students');
Route::get('/pdf/activities', [PdfController::class, 'activities'])->name('pdf.activities');
Route::get('/pdf/inscriptions', [PdfController::class, 'inscriptions'])->name('pdf.inscriptions');
