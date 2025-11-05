 @extends('layouts.app')

 @section('title', 'Listado de Inscripciones')

 @section('content')
 <div class="d-flex justify-content-between align-items-center mb-4">
     <h1><i class="bi bi-journal-check"></i> Inscripciones</h1>
     <a href="{{ route('inscriptions.create') }}" class="btn btn-success">
         <i class="bi bi-plus-circle-fill"></i> Nueva Inscripción
     </a>
 </div>

 @if($inscriptions->isEmpty())
 <div class="alert alert-info">No hay inscripciones registradas aún.</div>
 @else
 <div class="table-responsive">
     <table class="table table-bordered table-hover align-middle">
         <thead class="table-dark">
             <tr>
                 <th>#</th>
                 <th>Alumno</th>
                 <th>Actividad</th>
                 <th>Día</th>
                 <th>Horario</th>
                 <th>Acciones</th>
             </tr>
         </thead>
         <tbody>
             @foreach($inscriptions as $inscription)
             <tr>
                 <td>{{ $inscription->id }}</td>
                 <td>{{ $inscription->student->full_name }}</td>
                 <td>{{ $inscription->activity->name }}</td>
                 <td>{{ $inscription->activity->day }}</td>
                 <td>{{ $inscription->activity->hour }}</td>
                 <td class="text-center">
                     <form action="{{ route('inscriptions.destroy', $inscription) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar esta inscripción?')">
                         @csrf
                         @method('DELETE')
                         <button class="btn btn-sm btn-danger" title="Eliminar">
                             <i class="bi bi-trash-fill"></i>
                         </button>
                     </form>
                 </td>
             </tr>
             @endforeach
         </tbody>
     </table>
 </div>
 @endif
 @endsection
