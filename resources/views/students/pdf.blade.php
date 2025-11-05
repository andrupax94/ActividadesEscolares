@extends('layouts.pdfBase')
@section('content')
<h2>Listado de Estudiantes</h2>
@include('students._table', ['students' => $students,'print' => true])
@endsection
