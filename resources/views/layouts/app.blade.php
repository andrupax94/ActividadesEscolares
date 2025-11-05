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
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand {{ Route::is('home') ? 'nav-selected' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door-fill"></i> Inicio
                </a>
                <a class="nav-link {{ Route::is('activities.*') ? 'nav-selected' : '' }}" href="{{ route('activities.index') }}">
                    <i class="bi bi-calendar-event-fill"></i> Actividades
                </a>
                <a class="nav-link {{ Route::is('students.*') ? 'nav-selected' : '' }}" href="{{ route('students.index') }}">
                    <i class="bi bi-people-fill"></i> Alumnos
                </a>
                <a class="nav-link {{ Route::is('inscriptions.*') ? 'nav-selected' : '' }}" href="{{ route('inscriptions.index') }}">
                    <i class="bi bi-journal-check"></i> Inscripciones
                </a>
            </div>
        </nav>
    </header>


    <main class="container">
        @yield('content')
    </main>
    <footer class="footer mt-5 py-4 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-1">
                &copy; {{ date('Y') }} Todos los derechos Y Zurdos reservados
            </p>
            <p class="mb-1">
                Desarrollado con ayuda de
                <i class="bi bi-robot text-info"></i> Copilot Porque Soy Un Poco Vago
            </p>
            <p class="mb-0">
                <a href="https://github.com/andrupax94" target="_blank" class="text-decoration-none text-white me-3">
                    <i class="bi bi-github"></i> Mi GitHub
                </a>
                <a href="https://github.com/andrupax94/ActividadesEscolares" target="_blank" class="text-decoration-none text-white">
                    <i class="bi bi-folder-symlink"></i> Repositorio del Proyecto
                </a>
            </p>
        </div>
    </footer>
    @stack('scripts')

</body>

</html>
