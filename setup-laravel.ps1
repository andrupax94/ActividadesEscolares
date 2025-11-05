# Detectar si la ejecución de scripts está bloqueada
if ((Get-ExecutionPolicy) -eq "Restricted") {
    Write-Host "❌ La ejecución de scripts está deshabilitada en este sistema."
    Write-Host "ℹ️ Abre PowerShell como administrador y ejecuta:"
    Write-Host "`nSet-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned`n"
    Write-Host "Luego vuelve a ejecutar este script."
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
    @{label="Iniciar MySQL desde XAMPP"; done=$false},
    @{label="Verificar Composer y npm"; done=$false},
    @{label="Copiar archivo .env si falta"; done=$false},
    @{label="Instalar dependencias PHP y JS"; done=$false},
    @{label="Generar clave de aplicación"; done=$false},
    @{label="Verificar conexión a base de datos"; done=$false},
    @{label="Ejecutar migraciones y seeders"; done=$false},
    @{label="Crear enlace de storage"; done=$false},
    @{label="Compilar assets con Webpack"; done=$false},
    @{label="Preguntar si desea iniciar servidor"; done=$false}
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

function Confirm-Step($mensaje) {
    Show-Checklist
    Write-Host "➡️ $mensaje"
    $null = Read-Host "Presiona ENTER para continuar"
    cls
}

# Paso 1: Iniciar MySQL desde XAMPP
Confirm-Step "Paso 1: Iniciar MySQL desde XAMPP"
$xamppPaths = @("C:\xampp", "D:\xampp", "E:\xampp")
$xamppFound = $false
foreach ($path in $xamppPaths) {
    if (Test-Path "$path\mysql_start.bat") {
        Start-Process "$path\mysql_start.bat"
        $xamppFound = $true
        break
    }
}
if ($xamppFound) {
    Write-Host "✅ MySQL iniciado desde XAMPP."
} else {
    Write-Host "⚠️ No se encontró XAMPP. Inicia MySQL manualmente si es necesario."
}
$steps[0].done = $true
Show-Checklist
pause
cls

# Paso 2: Verificar Composer y npm
Confirm-Step "Paso 2: Verificar Composer y npm"
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
$steps[1].done = $true
Show-Checklist
pause
cls

# Paso 3: Copiar .env si falta
Confirm-Step "Paso 3: Verificar si existe .env y copiar si falta"
if (-not (Test-Path "$scriptPath\.env")) {
    Copy-Item "$scriptPath\.env.example" "$scriptPath\.env"
    Write-Host "✅ .env copiado desde .env.example"
} else {
    Write-Host "✅ .env ya existe"
}
$steps[2].done = $true
Show-Checklist
pause
cls

# Paso 4: Instalar dependencias PHP y JS
Confirm-Step "Paso 4: Ejecutar composer install y npm install"
composer install
npm install
Write-Host "✅ Dependencias instaladas"
$steps[3].done = $true
Show-Checklist
pause
cls

# Paso 5: Generar clave de aplicación
Confirm-Step "Paso 5: Generar clave de aplicación"
php artisan key:generate
Write-Host "✅ Clave generada"
$steps[4].done = $true
Show-Checklist
pause
cls

# Paso 6: Verificar conexión a base de datos
Confirm-Step "Paso 6: Verificar conexión a la base de datos"
try {
    php -r "new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo '✅ Conexión exitosa';"
    $steps[5].done = $true
} catch {
    Write-Host "❌ No se pudo conectar a la base de datos. Verifica tus credenciales en .env"
    pause
    exit
}
Show-Checklist
pause
cls

# Paso 7: Ejecutar migraciones y seeders
Confirm-Step "Paso 7: Ejecutar migraciones y seeders"
php artisan migrate
php artisan db:seed
Write-Host "✅ Migraciones y seeders ejecutados"
$steps[6].done = $true
Show-Checklist
pause
cls

# Paso 8: Crear enlace de storage
Confirm-Step "Paso 8: Crear enlace simbólico de storage"
php artisan storage:link
Write-Host "✅ Enlace creado"
$steps[7].done = $true
Show-Checklist
pause
cls

# Paso 9: Compilar assets con Webpack
Confirm-Step "Paso 9: Compilar assets con Webpack (npx mix)"
npx mix
Write-Host "✅ Assets compilados"
$steps[8].done = $true
Show-Checklist
pause
cls

# Paso 10: Preguntar si desea iniciar servidor
Confirm-Step "Paso 10: ¿Deseas iniciar el servidor Laravel?"
$answer = Read-Host "`n¿Deseas ejecutar 'php artisan serve'? (s/n)"
if ($answer -eq "s") {
    $steps[9].done = $true
    Show-Checklist
    Write-Host "`n🖥️ Iniciando servidor Laravel..."
    Write-Host "`nPresiona Ctrl+C para detener el servidor cuando lo desees."
    php artisan serve
} else {
    $steps[9].done = $true
    Show-Checklist
    Write-Host "`n✅ Configuración finalizada. Puedes iniciar el servidor manualmente cuando lo desees."
    Write-Host "`nPresiona cualquier tecla para cerrar..."
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
}
