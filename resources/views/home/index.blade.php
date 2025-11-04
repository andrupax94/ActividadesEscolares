@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<h1 class="mb-4">Panel Principal</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-journal-check text-primary"></i> Inscripciones
                </h5>
                <p class="card-text">Total: <strong>{{ $totalInscriptions }}</strong></p>
                <p class="card-text">
                    Última: {{ $lastInscriptionDate ? $lastInscriptionDate->format('d/m/Y H:i') : 'No hay inscripciones aún' }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-success">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-people-fill text-success"></i> Alumnos
                </h5>
                <p class="card-text">Total: <strong>{{ $totalStudents }}</strong></p>
                <p class="card-text">Edad promedio: {{ $averageAge }} años</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-calendar-event text-warning"></i> Actividades
                </h5>
                <p class="card-text">Total: <strong>{{ $totalActivities }}</strong></p>
                <p class="card-text">
                    Más popular: {{ $mostPopularActivity ? $mostPopularActivity->name : 'Sin inscripciones aún' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection