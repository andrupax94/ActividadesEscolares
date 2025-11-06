# Detectar si la ejecución de scripts está bloqueada
if ((Get-ExecutionPolicy) -eq "Restricted") {
    Write-Host "❌ La ejecución de scripts está deshabilitada en este sistema."
    Write-Host "ℹ️ Abre PowerShell como administrador y ejecuta:"
    Write-Host "`nSet-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned`n"
    pause
    exit
}

# Autoelevar privilegios si no es administrador
if (-not ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Start-Process powershell "-ExecutionPolicy Bypass -File `"$PSCommandPath`"" -Verb RunAs
    exit
}

# Ruta base del script
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $scriptPath

# Inicializar checklist
$steps = @(
    @{label="Verificar Composer y npm"; done=$false},
    @{label="Copiar archivo .env si falta"; done=$false},
    @{label="Instalar dependencias PHP y JS"; done=$false},
    @{label="Generar clave de aplicación"; done=$false},
    @{label="Verificar conexión a base de datos"; done=$false},
    @{label="Ejecutar migraciones y seeders"; done=$false},
    @{label="Crear enlace de storage"; done=$false},
    @{label="Compilar assets con Vite"; done=$false}
)

function Show-Checklist {
    Write-Host "`n🧾 Checklist de configuración Laravel"
    Write-Host "-----------------------------------"
    foreach ($step in $steps) {
        $status = if ($step.done) {"✅"} else {"⬜"}
        Write-Host "$status $($step.label)"
    }
    Write-Host "-----------------------------------`n"
}

function Pause-Step($mensaje) {
    Show-Checklist
    Write-Host "➡️ $mensaje"
    Start-Sleep -Seconds 2
    cls
}

$mysqlConnected = $false

# Paso 1: Verificar Composer y npm
Pause-Step "Paso 1: Verificar Composer y npm"
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Invoke-WebRequest -Uri https://getcomposer.org/installer -OutFile "$scriptPath\composer-setup.php"
    php "$scriptPath\composer-setup.php"
    Remove-Item "$scriptPath\composer-setup.php"
    Write-Host "✅ Composer instalado."
} else {
    Write-Host "✅ Composer ya está instalado."
}
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "❌ npm no está instalado. Instálalo desde https://nodejs.org"
    pause
    exit
} else {
    Write-Host "✅ npm ya está instalado."
}
$steps[0].done = $true
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 2: Copiar .env si falta
Pause-Step "Paso 2: Verificar si existe .env y copiar si falta"
if (-not (Test-Path "$scriptPath\.env")) {
    Copy-Item "$scriptPath\.env.example" "$scriptPath\.env"
    Write-Host "✅ .env copiado desde .env.example"
} else {
    Write-Host "✅ .env ya existe"
}
$steps[1].done = $true
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 3: Instalar dependencias PHP y JS
Pause-Step "Paso 3: Ejecutar composer install y npm install"
composer install
npm install
Write-Host "✅ Dependencias instaladas"
$steps[2].done = $true
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 4: Generar clave de aplicación
Pause-Step "Paso 4: Generar clave de aplicación"
php artisan key:generate
Write-Host "✅ Clave generada"
$steps[3].done = $true
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 5: Verificar conexión a base de datos
Pause-Step "Paso 5: Verificar conexión a la base de datos con php artisan"

try {
    $output = php artisan migrate 2>&1

    if ($output -match "No application encryption key has been specified") {
        Write-Host "⚠️ Laravel necesita una clave de aplicación. Ejecuta php artisan key:generate."
    }

    if ($output -match "could not find driver" -or $output -match "SQLSTATE") {
        Write-Host "❌ No se pudo conectar a la base de datos."
    } elseif ($output -match "Do you want to create it") {
        Write-Host "ℹ️ La base de datos no existe. Se intentará crear automáticamente..."
        cmd /c "echo y | php artisan migrate --force"
        php artisan db:seed --force
        Write-Host "✅ Base de datos creada y seeders ejecutados."
        $mysqlConnected = $true
        $steps[4].done = $true
    } else {
        Write-Host "✅ Base de datos detectada. Se ejecutará migrate:fresh..."
        php artisan migrate:fresh --force
        php artisan db:seed --force
        Write-Host "✅ Migraciones reiniciadas y seeders ejecutados."
        $mysqlConnected = $true
        $steps[4].done = $true
    }
} catch {
    Write-Host "❌ Error al verificar la base de datos."
}
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 6: Ejecutar migraciones y seeders (solo si hay conexión)
if ($mysqlConnected) {
    Pause-Step "Paso 6: Ejecutar migraciones y seeders"
    php artisan migrate
    php artisan db:seed
    Write-Host "✅ Migraciones y seeders ejecutados"
    $steps[5].done = $true
} else {
    Write-Host "⏭️ Migraciones y seeders omitidos por falta de conexión a MySQL"
}
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 7: Crear enlace de storage
# Paso 7: Crear enlace simbólico de storage
Pause-Step "Paso 7: Crear enlace simbólico de storage"

try {
    $storageLink = "$scriptPath\public\storage"

    if (Test-Path $storageLink) {
        Write-Host "✅ El enlace simbólico ya existe: public/storage"
    } else {
        php artisan storage:link
        Write-Host "✅ Enlace simbólico creado correctamente"
    }

    $steps[6].done = $true
} catch {
    Write-Host "⚠️ No se pudo crear el enlace simbólico. Es posible que ya exista o haya un error."
    $steps[6].done = $true
}

Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 8: Compilar assets con Webpack
# Paso 8: Compilar assets con Vite
Pause-Step "Paso 8: Compilar assets con Vite (npm run build)"
npm run build
Write-Host "✅ Assets compilados con Vite"
$steps[7].label = "Compilar assets con Vite"
$steps[7].done = $true
Show-Checklist
Start-Sleep -Seconds 2
cls

# Paso 9: Preguntar si desea iniciar el servidor Laravel
if ($mysqlConnected) {
    Pause-Step "Paso 9: ¿Deseas iniciar el servidor Laravel con php artisan serve?"

    $respuesta = Read-Host "🟢 ¿Iniciar servidor ahora? (s/n)"
    if ($respuesta -eq "s" -or $respuesta -eq "S") {
        Write-Host "`n🚀 Iniciando servidor Laravel en http://localhost:8000 ..."
        php artisan serve
        exit
    } else {
        Write-Host "ℹ️ Puedes iniciar el servidor manualmente cuando lo desees con: php artisan serve"
    }
}


# Final
Write-Host "`n🎉 ¡Configuración completa!"
if (-not $mysqlConnected) {
    Write-Host "⚠️ Las migraciones y el servidor Laravel no se ejecutaron porque no se pudo conectar a MySQL."
}
Write-Host "`nPresiona cualquier tecla para cerrar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
