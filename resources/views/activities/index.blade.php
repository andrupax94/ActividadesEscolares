@extends('layouts.app')

@section('title', 'Listado de Actividades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-event-fill"></i> Actividades</h1>
    <a href="{{ route('activities.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle-fill"></i> Nueva Actividad
    </a>
</div>

@if($activities->isEmpty())
<div class="alert alert-info">No hay actividades registradas aún.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Día</th>
                <th>Horario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td>{{ $activity->id }}</td>
                <td>{{ $activity->name }}</td>
                <td>{{ $activity->description ?? 'Sin descripción' }}</td>
                <td>{{ $activity->days_string }}</td>
                <td>{{ $activity->hour }}</td>
                <td class="text-center">
                    <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-primary me-1" title="Ver">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar esta actividad?')">
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
