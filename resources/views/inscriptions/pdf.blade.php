@extends('layouts.pdfBase')
@section('content')
<h2>Listado de Incripciones</h2>
@include('inscriptions._table', ['inscriptions' => $inscriptions,'print' => true])
@endsection
