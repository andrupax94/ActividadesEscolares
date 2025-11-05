 @extends('layouts.app')

 @section('title', 'Listado de Alumnos')

 @section('content')
 <div class="d-flex  align-items-center mb-4">
     <h1><i class="bi bi-people-fill"></i> Alumnos</h1>
     <a href="{{ route('students.create') }}" class="btn btn-success">
         <i class="bi bi-person-plus-fill"></i> Nuevo Alumno
     </a>
     @include('partials._pdfButtom', [
     'route' => route('pdf.students', ['q' => request('q')])
     ])
     @include('partials._searchBar', ['action' => route('students.index')])

 </div>

 @if($students->isEmpty())
 <div class="alert alert-info">No hay alumnos registrados aún.</div>
 @else
 @include('students._table', ['students' => $students])
 {{ $students->onEachSide(1)->links('pagination::bootstrap-5') }}

 @endif
 @endsection
