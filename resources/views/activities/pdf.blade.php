@extends('layouts.pdfBase')
@section('content')
<h2>Listado de Actividades</h2>
@include('activities._table', ['activities' => $activities,'print' => true])
@endsection
