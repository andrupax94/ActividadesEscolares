 <div class="table-responsive">
     <table class="table table-bordered table-hover align-middle">
         <thead class="table-dark">
             <tr>
                 <th>#</th>
                 <th>Nombre completo</th>
                 <th>Grado</th>
                 <th>Edad</th>
                 @if(empty($print))
                 <th>Acciones</th>
                 @endif

             </tr>
         </thead>
         <tbody>
             @foreach($students as $student)
             <tr>
                 <td>{{ $student->id }}</td>
                 <td>{{ $student->full_name }}</td>
                 <td>{{ $student->grade }}</td>
                 <td>{{ $student->age }} años</td>
                 @if(empty($print))


                 <td class="text-center">
                     <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-primary me-1" title="Ver">
                         <i class="bi bi-eye-fill"></i>
                     </a>
                     <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                         <i class="bi bi-pencil-fill"></i>
                     </a>
                     <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline-block" onsubmit="return window.ModalesService.confirmar('¿Eliminar este alumno?', this)">


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