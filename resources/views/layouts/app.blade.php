<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Actividades Escolares')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand {{ Route::is('home') ? 'text-primary fw-bold nav-selected' : '' }}" href="{{ route('home') }}">
                <i class="bi bi-house-door-fill"></i> Inicio
            </a>
            <a class="nav-link {{ Route::is('activities.*') ? 'text-primary fw-bold' : '' }}" href="{{ route('activities.index') }}">
                <i class="bi bi-calendar-event-fill"></i> Actividades
            </a>
            <a class="nav-link {{ Route::is('students.*') ? 'text-primary fw-bold' : '' }}" href="{{ route('students.index') }}">
                <i class="bi bi-people-fill"></i> Alumnos
            </a>
            <a class="nav-link {{ Route::is('inscriptions.*') ? 'text-primary fw-bold' : '' }}" href="{{ route('inscriptions.index') }}">
                <i class="bi bi-journal-check"></i> Inscripciones
            </a>
        </div>
    </nav>



    <div class="container">
        @yield('content')
    </div>
</body>

</html>