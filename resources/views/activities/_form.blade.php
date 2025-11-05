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
        <div class="mb-3">
            <label class="form-label d-block">Días</label>
            <div class="d-flex gap-2">
                @php
                $dias = ['Lunes' => 'L', 'Martes' => 'M', 'Miércoles' => 'X', 'Jueves' => 'J', 'Viernes' => 'V'];
                $seleccionados = explode(',', old('days', $activity->days ?? ''));
                @endphp

                @foreach($dias as $nombre => $letra)
                <label class="btn btn-outline-primary day-toggle {{ in_array($nombre, $seleccionados) ? 'active' : '' }}"
                    style="width: 40px; height: 40px; text-align: center; padding: 0.5rem; line-height: 1.2;"
                    data-day="{{ $nombre }}">
                    <input type="checkbox" name="days[]" value="{{ $nombre }}"
                        class="btn-check day-checkbox"
                        autocomplete="off"
                        {{ in_array($nombre, $seleccionados) ? 'checked' : '' }}>
                    {{ $letra }}
                </label>
                @endforeach

            </div>

            <input type="hidden" name="days_string" id="days_string" value="{{ old('days_string', $activity->days_string ?? '') }}">

            @error('days_string')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>


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
    const dayToggles = document.querySelectorAll('.day-toggle');
    const checkboxes = document.querySelectorAll('.day-checkbox');
    const daysStringField = document.getElementById('days_string');
    console.log('dyas:' + daysStringField.value);

    // Activar visualmente los botones según los checkboxes marcados
    function syncDayButtons() {
        const daysStringFieldArray = daysStringField.value.split(',').map(d => d.trim());

        dayToggles.forEach(label => {
            const day = label.getAttribute('data-day');
            const checkbox = label.querySelector('input[type="checkbox"]');

            if (daysStringFieldArray.includes(day)) {
                label.classList.add('active');
                checkbox.checked = true;
            } else {
                label.classList.remove('active');
                checkbox.checked = false;
            }
        });
    }

    // Ejecutar al cargar


    // Construir el days_string desde los checkboxes seleccionados
    function updateDaysString() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        daysStringField.value = selected.join(',');
    }

    // Al hacer clic en un botón de día
    dayToggles.forEach(label => {
        label.addEventListener('click', () => {
            const checkbox = label.querySelector('input[type="checkbox"]');
            setTimeout(() => {
                label.classList.toggle('active', checkbox.checked);
                updateDaysString();
            }, 0);
        });
    });

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
        endInput.min = start;
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

        updateDaysString(); // asegurar que days_string esté actualizado
    });

    // Inicializar al cargar (modo edición)
    syncDayButtons();
    updateDaysString();
</script>
@endpush
