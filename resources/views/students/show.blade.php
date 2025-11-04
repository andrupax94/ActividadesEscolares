@extends('layouts.app')

@section('title', 'Detalle del Alumno')

@section('content')
<h1><i class="bi bi-person-fill"></i> {{ $student->full_name }}</h1>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Grado:</strong> {{ $student->grade }}</p>
        <p><strong>Edad:</strong> {{ $student->age }} años</p>
    </div>
</div>

<h4><i class="bi bi-calendar-event-fill"></i> Actividades inscritas</h4>

@if($student->activities->isEmpty())
<div class="alert alert-warning">Este alumno no está inscrito en ninguna actividad.</div>
@else
<ul class="list-group mb-4">
    @foreach($student->activities as $activity)
    <li class="list-group-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $activity->name }}</strong><br>
                <small class="text-muted">{{ $activity->description }}</small>
            </div>
            <div class="text-end">
                <span class="badge bg-info">{{ $activity->day }}</span><br>
                <span class="badge bg-secondary">{{ $activity->hour }}</span>
            </div>
        </div>
    </li>
    @endforeach
</ul>
@endif

<a href="{{ route('students.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left-circle"></i> Volver al listado
</a>
@endsection