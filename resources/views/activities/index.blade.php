@extends('layouts.app')

@section('title', 'Listado de Actividades')

@section('content')
<div class="d-flex  align-items-center mb-4">
    <h1><i class="bi bi-calendar-event-fill"></i> Actividades</h1>
    <a href="{{ route('activities.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle-fill"></i> Nueva Actividad
    </a>
    @include('partials._pdfButtom', [
    'route' => route('pdf.activities', ['q' => request('q')])
    ])
    @include('partials._searchBar', ['action' => route('activities.index')])

</div>

@if($activities->isEmpty())
<div class="alert alert-info">No hay actividades registradas aún.</div>
@else
@include('activities._table', ['activities' => $activities])
{{ $activities->onEachSide(1)->links('pagination::bootstrap-5') }}

@endif
@endsection
