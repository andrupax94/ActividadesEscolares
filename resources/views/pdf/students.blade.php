@extends('pdf.base')
<h2>Listado de Actividades</h2>
@include('students._table', ['students' => $students])
