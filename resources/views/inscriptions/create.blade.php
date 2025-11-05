@extends('layouts.app')

@section('title', 'Nueva Inscripción')

@section('content')
<h1><i class="bi bi-journal-plus"></i> Registrar Inscripción</h1>

<form action="{{ route('inscriptions.store') }}" method="POST" class="mt-4">
    @csrf
    @include('layouts.errors')
    <div class="mb-3">
        <label for="student_id" class="form-label">Alumno</label>
        <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror">
            <option value="">Selecciona un alumno</option>
            @foreach($students as $student)
            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                {{ $student->full_name }} (Grado {{ $student->grade }})
            </option>
            @endforeach
        </select>
        @error('student_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="activity_id" class="form-label">Actividad</label>
        <select name="activity_id" id="activity_id" class="form-select @error('activity_id') is-invalid @enderror">
            <option value="">Selecciona una actividad</option>
            @foreach($activities as $activity)
            <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                {{ $activity->name }} ({{ $activity->day }} - {{ $activity->hour }})
            </option>
            @endforeach
        </select>
        @error('activity_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save-fill"></i> Guardar inscripción
    </button>
</form>
@endsection
