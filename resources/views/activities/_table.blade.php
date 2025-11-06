<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Día</th>
                <th>Horario</th>
                @if(empty($print))
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td>{{ $activity->id }}</td>
                <td>{{ $activity->name }}</td>
                <td>{{ $activity->description ?? 'Sin descripción' }}</td>
                <td>{{ $activity->days_string }}</td>
                <td>{{ $activity->hour }}</td>
                @if(empty($print))
                <td class="text-center">
                    <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-primary me-1" title="Ver">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="d-inline-block" onsubmit="return window.ModalesService.confirmar('¿Eliminar esta Actividad?', this)">
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