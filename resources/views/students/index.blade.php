 @extends('layouts.app')

 @section('title', 'Listado de Alumnos')

 @section('content')
 <div class="d-flex justify-content-between align-items-center mb-4">
     <h1><i class="bi bi-people-fill"></i> Alumnos</h1>
     <a href="{{ route('students.create') }}" class="btn btn-success">
         <i class="bi bi-person-plus-fill"></i> Nuevo Alumno
     </a>
 </div>

 @if($students->isEmpty())
 <div class="alert alert-info">No hay alumnos registrados aún.</div>
 @else
 <div class="table-responsive">
     <table class="table table-bordered table-hover align-middle">
         <thead class="table-dark">
             <tr>
                 <th>#</th>
                 <th>Nombre completo</th>
                 <th>Grado</th>
                 <th>Edad</th>
                 <th>Acciones</th>
             </tr>
         </thead>
         <tbody>
             @foreach($students as $student)
             <tr>
                 <td>{{ $student->id }}</td>
                 <td>{{ $student->full_name }}</td>
                 <td>{{ $student->grade }}</td>
                 <td>{{ $student->age }} años</td>
                 <td class="text-center">
                     <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-primary me-1" title="Ver">
                         <i class="bi bi-eye-fill"></i>
                     </a>
                     <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                         <i class="bi bi-pencil-fill"></i>
                     </a>
                     <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este alumno?')">
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