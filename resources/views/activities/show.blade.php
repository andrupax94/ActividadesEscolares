 @extends('layouts.app')

 @section('title', 'Detalle de Actividad')

 @section('content')
 <h1><i class="bi bi-calendar-event-fill"></i> {{ $activity->name }}</h1>

 <div class="card mb-4">
     <div class="card-body">
         <p><strong>Descripción:</strong> {{ $activity->description ?? 'Sin descripción' }}</p>
         <p><strong>Día:</strong> {{ $activity->day }}</p>
         <p><strong>Horario:</strong> {{ $activity->hour }}</p>
     </div>
 </div>

 <h4><i class="bi bi-people-fill"></i> Alumnos inscritos</h4>

 @if($activity->students->isEmpty())
 <div class="alert alert-warning">No hay alumnos inscritos en esta actividad.</div>
 @else
 <ul class="list-group mb-4">
     @foreach($activity->students as $student)
     <li class="list-group-item d-flex justify-content-between align-items-center">
         <div>
             <strong>{{ $student->full_name }}</strong><br>
             <small class="text-muted">Grado: {{ $student->grade }} | Edad: {{ $student->age }} años</small>
         </div>
         <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary">
             <i class="bi bi-eye-fill"></i> Ver alumno
         </a>
     </li>
     @endforeach
 </ul>
 @endif

 <a href="{{ route('activities.index') }}" class="btn btn-secondary">
     <i class="bi bi-arrow-left-circle"></i> Volver al listado
 </a>
 @endsection