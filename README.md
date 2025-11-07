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

# 💻 Requisitos recomendados


## 🧠 Procesador
- **AMD Ryzen Threadripper PRO 7995WX**
- 96 núcleos / 192 hilos
- Frecuencia base: 2.5 GHz, boost hasta 5.1 GHz

## 🧊 Refrigeración
- Sistema de refrigeración líquida custom
- Doble radiador de 420mm
- Bomba silenciosa con control digital

## 🧠 Memoria RAM
- 512 GB DDR5 ECC
- Velocidad: 6400 MHz
- Quad-channel

## 🎮 Tarjeta gráfica
- NVIDIA RTX 6000 Ada Generation (48 GB GDDR6 ECC)
- Alternativa: 2x RTX 4090 en SLI

## 💾 Almacenamiento
- 4 TB NVMe Gen5 SSD (sistema)
- 8 TB SSD SATA (proyectos)
- 20 TB HDD (respaldo)

## 🖥️ Pantalla
- Monitor 8K de 32"
- HDR1000, 240Hz, G-Sync/FreeSync

## 🔌 Fuente de poder
- 2000W Platinum modular
- Protección contra sobrecargas y picos de energía

## 🧱 Caja
- Torre XL con aislamiento acústico
- Iluminación RGB sincronizada
- Soporte para refrigeración criogénica

## 🌐 Conectividad
- Wi-Fi 7
- Ethernet 10GbE
- Bluetooth 5.4

## 🎧 Audio
- DAC externo de alta fidelidad
- Sistema de sonido envolvente 7.2

## 🧪 Extras
- Botón físico “Turbo Overclock”
- Lector de huellas
- Soporte para IA local y virtualización avanzada

---



---

🚀 INSTALACIÓN DEL PROYECTO – VERSIÓN ALFA

Este proyecto incluye dos métodos de instalación: uno automático (versión alfa) y otro manual. El script automatizado facilita la configuración inicial, pero puedes optar por hacerlo manualmente si lo prefieres.

---

🧪 MÉTODO A: INSTALACIÓN AUTOMÁTICA (VERSIÓN ALFA)

Este método utiliza dos archivos incluidos en el proyecto:

- setup-laravel.ps1 → Script PowerShell que configura el entorno paso a paso
- run-laravel-setup.bat → Script CMD que ejecuta el anterior como administrador

🔧 Pasos:

1. Haz doble clic en run-laravel-setup.bat
2. Acepta el aviso de Control de cuentas de usuario (UAC)
3. El script instalará dependencias, generará la clave de aplicación, verificará la base de datos y ejecutará migraciones si es posible
4. Si no se detecta conexión a MySQL, se omitirán las migraciones y se mostrará un aviso al final

⚠️ Este script está en fase ALFA. Úsalo bajo tu responsabilidad y revisa los pasos si algo falla.

---

🧰 MÉTODO B: INSTALACIÓN MANUAL

Si prefieres configurar el proyecto tú mismo, sigue estos pasos desde la raíz del proyecto:

1. Instala dependencias PHP y JS:
   composer install
   npm install

2. Crea el archivo de entorno:
   cp .env.example .env

3. Genera la clave de aplicación:
   php artisan key:generate

4. Verifica que MySQL esté activo y configurado en tu archivo .env

5. Ejecuta migraciones y seeders:
   php artisan migrate
   php artisan db:seed

6. Crea el enlace simbólico de storage:
   php artisan storage:link
7. (Opcional) Ejecuta Vite:
    npm run build

---

📌 NOTAS ADICIONALES

- El script automatizado omite las migraciones si no detecta conexión a MySQL.
- La compilación de assets con Webpack ha sido desactivada en esta versión.
- Puedes iniciar el servidor manualmente con:
   php artisan serve
