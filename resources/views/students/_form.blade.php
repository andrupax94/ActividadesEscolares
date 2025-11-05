<form action="{{ $route }}" method="POST" class="mt-4">
    @csrf
    @if($method !== 'POST')
    @method($method)
    @endif
    @include('layouts.errors')
    <div class="mb-3">
        <label for="full_name" class="form-label">Nombre completo</label>
        <input type="text" name="full_name" id="full_name"
            class="form-control @error('full_name') is-invalid @enderror"
            value="{{ old('full_name', $student->full_name ?? '') }}">
        @error('full_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="grade" class="form-label">Grado</label>
        <input type="text" name="grade" id="grade"
            class="form-control @error('grade') is-invalid @enderror"
            value="{{ old('grade', $student->grade ?? '') }}">
        @error('grade')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="age" class="form-label">Edad</label>
        <input type="number" name="age" id="age"
            class="form-control @error('age') is-invalid @enderror"
            value="{{ old('age', $student->age ?? '') }}">
        @error('age')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save-fill"></i> {{ $button }}
    </button>
</form>
