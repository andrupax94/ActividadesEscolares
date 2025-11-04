# 📚 Gestor de Actividades Escolares – Proyecto Final Laravel

Este proyecto consiste en desarrollar una aplicación web con Laravel que simula un gestor de actividades escolares. El objetivo es administrar un catálogo de actividades extraescolares (como robótica, ajedrez, pintura o inglés) y gestionar qué alumnos están inscritos.

## 🎯 Objetivos

- Administrar actividades extraescolares y alumnos inscritos.
- Implementar funcionalidades básicas: registro, vistas Blade, controladores, rutas RESTful, validaciones y exportación.
- Aplicar conocimientos de PHP, POO, MVC, Laravel y Eloquent ORM.
- Prepararse para desarrollar proyectos reales y defender el trabajo técnico profesionalmente.

---

## 🛠️ Módulos del Proyecto

### 1. Modelo de Datos

- Crear 3 tablas con migraciones:
  - **Actividades**: nombre, descripción, día de la semana, horario.
  - **Alumnos**: nombre completo, curso, edad.
  - **Inscripciones**: relación entre alumno y actividad.
- Relaciones:
  - Un alumno puede estar en varias actividades.
  - Una actividad puede tener varios alumnos.

### 2. CRUD con Laravel

- Implementar operaciones CRUD completas para:
  - Actividades
  - Alumnos
  - Inscripciones (solo creación, sin edición)
- Usar controladores `resource` y rutas RESTful.

### 3. Vistas Blade y Formularios

- Crear vistas con Blade:
  - Listado
  - Formulario de alta
  - Formulario de edición
- Utilizar:
  - `@extends`, `@section`, `@csrf`, `@error`
- Estilos con Bootstrap 5.

### 4. Validación y Seguridad

- Validaciones desde el controlador o con `FormRequest`.
- Mostrar errores en el formulario.
- Validar datos antes de guardarlos.

### 5. API Pública

- Ruta sin autenticación:
  - `GET /api/actividades` – Lista de actividades en formato JSON.

### 6. Exportación a PDF (Opcional)

- Exportar lista de alumnos inscritos por actividad en PDF.
- Usar la librería `barryvdh/laravel-dompdf`.

### 7. Búsqueda (Extra)

- Implementar barra de búsqueda por nombre en el listado de actividades.

### 8. Documentación

- Este archivo `README.md` incluye:
  - Instrucciones de instalación
  - Detalles de la base de datos (con seeders si se usan)
  - Capturas de pantalla (opcional)

---

## 🚀 Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/gestor-actividades.git
   cd gestor-actividades