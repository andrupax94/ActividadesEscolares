 @extends('layouts.app')

 @section('title', 'Listado de Inscripciones')

 @section('content')
 <div class="d-flex  align-items-center mb-4">
     <h1><i class="bi bi-journal-check"></i> Inscripciones</h1>
     <a href="{{ route('inscriptions.create') }}" class="btn btn-success">
         <i class="bi bi-plus-circle-fill"></i> Nueva Inscripción
     </a>
     @include('partials._pdfButtom', [
     'route' => route('pdf.inscriptions', ['q' => request('q')])
     ])
     @include('partials._searchBar', ['action' => route('inscriptions.index')])

 </div>

 @if($inscriptions->isEmpty())
 <div class="alert alert-info">No hay inscripciones registradas aún.</div>
 @else
 @include('inscriptions._table', ['inscriptions' => $inscriptions])
 {{ $inscriptions->onEachSide(1)->links('pagination::bootstrap-5') }}
 @endif
 @endsection
