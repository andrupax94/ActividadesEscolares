 @extends('layouts.app')

 @section('title', 'Nuevo Alumno')

 @section('content')
 <h1><i class="bi bi-person-plus-fill"></i> Registrar Alumno</h1>

 @include('students._form', [
 'route' => route('students.store'),
 'method' => 'POST',
 'button' => 'Guardar'
 ])
 @endsection