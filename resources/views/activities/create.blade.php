 @extends('layouts.app')

 @section('title', 'Nueva Actividad')

 @section('content')
 <h1><i class="bi bi-plus-circle-fill"></i> Registrar Actividad</h1>

 @include('activities._form', [
 'route' => route('activities.store'),
 'method' => 'POST',
 'button' => 'Guardar'
 ])
 @endsection
