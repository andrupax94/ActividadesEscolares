<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>Actividad</th>
                <th>Día</th>
                <th>Horario</th>
                @if(empty($print))
                <th>Acciones</th>
                @endif


            </tr>
        </thead>
        <tbody>
            @foreach($inscriptions as $inscription)
            <tr>
                <td>{{ $inscription->id }}</td>
                <td>{{ $inscription->student->full_name }}</td>
                <td>{{ $inscription->activity->name }}</td>
                <td>{{ $inscription->activity->day }}</td>
                <td>{{ $inscription->activity->hour }}</td>
                @if(empty($print))

                <td class="text-center">
                    <form action="{{ route('inscriptions.destroy', $inscription) }}" method="POST" class="d-inline-block" onsubmit="return window.ModalesService.confirmar('¿Eliminar esta inscripcion?', this)">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>