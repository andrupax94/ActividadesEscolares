 @extends('layouts.app')

 @section('title', 'Editar Alumno')

 @section('content')
 <h1><i class="bi bi-pencil-fill"></i> Editar Alumno</h1>

 @include('students._form', [
 'route' => route('students.update', $student),
 'method' => 'PUT',
 'button' => 'Actualizar',
 'student' => $student
 ])
 @endsection