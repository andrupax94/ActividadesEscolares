 @extends('layouts.app')

 @section('title', 'Editar Actividad')

 @section('content')
 <h1><i class="bi bi-pencil-fill"></i> Editar Actividad</h1>

 @include('activities._form', [
 'route' => route('activities.update', $activity),
 'method' => 'PUT',
 'button' => 'Actualizar',
 'activity' => $activity
 ])
 @endsection
