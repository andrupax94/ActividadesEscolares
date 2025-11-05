<form action="{{ $route }}" method="POST" class="mt-4" validate>
    @csrf
    @if($method !== 'POST')
    @method($method)
    @endif
    @include('layouts.errors')
    <div class="mb-3">
        <label for="name" class="form-label">Nombre de la actividad</label>
        <input type="text" name="name" minlength="4" maxlength="255" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $activity->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea name="description" id="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="3">{{ old('description', $activity->description ?? '') }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="day" class="form-label">Día</label>
        <select name="day" id="day" class="form-select @error('day') is-invalid @enderror" required>
            <option value="">Selecciona un día</option>
            @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes'] as $dia)
            <option value="{{ $dia }}" {{ old('day', $activity->day ?? '') === $dia ? 'selected' : '' }}>
                {{ $dia }}
            </option>
            @endforeach
        </select>
        @error('day')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="start_time" class="form-label">Hora de inicio</label>
        <input type="time" id="start_time" class="form-control">
    </div>

    <div class="mb-3">
        <label for="end_time" class="form-label">Hora de fin</label>
        <input type="time" id="end_time" class="form-control">
    </div>

    <input type="hidden" name="hour" id="hour" value="{{ old('hour', $activity->hour ?? '') }}">

    @error('hour')
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror


    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save-fill"></i> {{ $button }}
    </button>


</form>


@push('scripts')
<script>
    const form = document.querySelector('form');
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const hourField = document.getElementById('hour');

    // Establecer el mínimo dinámico en end_time cuando cambia start_time
    startInput.addEventListener('change', () => {
        if (startInput.value) {
            endInput.min = startInput.value;
        }
    });

    // Rellenar los inputs si ya hay un valor en hour (modo edición)
    if (hourField.value.includes(' - ')) {
        const [start, end] = hourField.value.split(' - ');
        startInput.value = start;
        endInput.value = end;
        endInput.min = start; // también establecer min al cargar
    }

    // Validar al enviar el formulario
    form.addEventListener('submit', function(e) {
        const start = startInput.value;
        const end = endInput.value;

        if (start && end) {
            if (start >= end) {
                e.preventDefault();
                alert('La hora de fin debe ser mayor que la de inicio.');
                return;
            }

            hourField.value = `${start} - ${end}`;
        }
    });
</script>
@endpush
