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
    Start-Sleep -Seconds 1.5
    cls
}

$mysqlConnected = $false

# ----------------------
# Paso 1: Composer y Node/npm (instalación automática si falta)
# ----------------------
Pause-Step "Paso 1: Verificar Composer y npm/Node"

# Helper: ejecutar comando y capturar si falla
function Try-Run($cmd, [switch]$NoThrow) {
    try {
        iex $cmd 2>&1
        return $true
    } catch {
        if (-not $NoThrow) { return $false }
        return $false
    }
}
# --- Composer: instalar composer.phar, mover a carpeta de usuario, añadir al PATH y crear wrapper ---
# Requiere PHP disponible en PATH para funcionar correctamente
$composerCmd = $null
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition

# Verificar PHP
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "❌ PHP no está en PATH. Instala/añade PHP al PATH antes de instalar Composer."
} else {
    # Buscar composer ya disponible
    if ((Get-Command composer -ErrorAction SilentlyContinue) -ne $null) {
        Write-Host "✅ Composer ya disponible en PATH"
        $composerCmd = "composer"
    } else {
        # Descargar composer.phar si no existe
        $localPhar = Join-Path $scriptDir "composer.phar"
        if (-not (Test-Path $localPhar)) {
            Write-Host "ℹ️ Descargando composer.phar..."
            try {
                Invoke-WebRequest -Uri "https://getcomposer.org/composer-stable.phar" -OutFile $localPhar -UseBasicParsing -ErrorAction Stop
                Write-Host "✅ composer.phar descargado a $localPhar"
            } catch {
                Write-Host "❌ Error al descargar composer.phar: $($_.Exception.Message)"
            }
        } else {
            Write-Host "ℹ️ composer.phar ya existe en $localPhar"
        }

        # Si se descargó, mover a carpeta de usuario estándar
        if (Test-Path $localPhar) {
            $userComposerDir = Join-Path $env:USERPROFILE "AppData\Local\Composer"
            try { New-Item -ItemType Directory -Path $userComposerDir -Force | Out-Null } catch {}
            $targetPhar = Join-Path $userComposerDir "composer.phar"
            try {
                Copy-Item -Path $localPhar -Destination $targetPhar -Force
                Remove-Item $localPhar -ErrorAction SilentlyContinue
                Write-Host "✅ composer.phar movido a $targetPhar"
            } catch {
                Write-Host "⚠️ No se pudo mover composer.phar: $($_.Exception.Message)"
                $targetPhar = $localPhar
            }

            # Añadir carpeta al PATH de usuario si no está presente
            $composerBinDir = Split-Path -Parent $targetPhar
            $currentUserPath = [Environment]::GetEnvironmentVariable("Path", "User")
            if (-not ($currentUserPath -split ";" | Where-Object { $_ -eq $composerBinDir })) {
                try {
                    $newUserPath = if ([string]::IsNullOrEmpty($currentUserPath)) { $composerBinDir } else { "$currentUserPath;$composerBinDir" }
                    [Environment]::SetEnvironmentVariable("Path", $newUserPath, "User")
                    Write-Host "✅ Se agregó $composerBinDir al PATH de usuario"
                    Write-Host "ℹ️ El cambio estará disponible en nuevas sesiones de terminal."
                } catch {
                    Write-Host "⚠️ No se pudo actualizar PATH de usuario: $($_.Exception.Message)"
                }
            } else {
                Write-Host "ℹ️ $composerBinDir ya estaba en el PATH de usuario"
            }

            # Crear wrapper composer.bat en la misma carpeta para poder ejecutar 'composer' sin php en la invocación
            $wrapperBat = Join-Path $composerBinDir "composer.bat"
            try {
                $batContent = "@echo off`nphp `"%~dp0composer.phar`" %*"
                Set-Content -Path $wrapperBat -Value $batContent -Encoding ASCII -Force
                Write-Host "✅ Wrapper creado: $wrapperBat"
            } catch {
                Write-Host "⚠️ No se pudo crear wrapper composer.bat: $($_.Exception.Message)"
            }

            # Preparar comando a usar en este proceso (no requiere reinicio)
            if (Test-Path $targetPhar) {
                $composerCmd = "php `"$targetPhar`""
                Write-Host "ℹ️ En este script se usará Composer como: $composerCmd"
            } elseif (Test-Path $wrapperBat) {
                $composerCmd = "`"$wrapperBat`""
                Write-Host "ℹ️ En este script se usará Composer como: $composerCmd"
            }
        } else {
            Write-Host "❌ composer.phar no está disponible; Composer no instalado."
        }
    }
}

# Fallbacks adicionales: buscar composer.phar en rutas comunes si aún no definido
if (-not $composerCmd) {
    $candidates = @(
        Join-Path $env:USERPROFILE "AppData\Local\Composer\composer.phar",
        Join-Path $env:ProgramData "ComposerSetup\composer.phar",
        Join-Path $env:ProgramFiles "Composer\composer.phar",
        Join-Path $scriptDir "composer.phar"
    )
    $found = $candidates | Where-Object { Test-Path $_ } | Select-Object -First 1
    if ($found) { $composerCmd = "php `"$found`""; Write-Host "ℹ️ Composer fallback usado: $composerCmd" }
}

# Exportar variable para uso posterior en el script
Set-Variable -Name composerCmd -Value $composerCmd -Scope Global

# Ejemplo de uso inmediato (si composerCmd está definido)
if ($composerCmd) {
    try {
        Write-Host "ℹ️ Probando composer --version..."
        iex "$composerCmd --version" 2>&1 | Write-Host
    } catch {
        Write-Host "⚠️ No se pudo ejecutar composer desde $composerCmd en esta sesión, pero está instalado y listo para nuevas terminales."
    }
} else {
    Write-Host "❌ Composer no disponible. Para usar Composer en nuevas sesiones, abre una nueva terminal o instala Composer manualmente."
}

# Node/npm: intentar PATH, si no, descargar instalador MSI y ejecutar (silencioso)
$npmAvailable = (Get-Command npm -ErrorAction SilentlyContinue) -ne $null
if (-not $npmAvailable) {
    Write-Host "ℹ️ npm no está disponible. Se descargará e instalará Node.js (incluye npm)..."
    $nodeVersionUrl = "https://nodejs.org/dist/latest-v20.x/"  # apunta a la última v20.x
    $nodeMsi = "$scriptPath\node-latest-x64.msi"
    # Intento de URL directa (fallback a v20.10.0 si la anterior falla)
    $downloaded = $false
    try {
        Invoke-WebRequest -Uri "${nodeVersionUrl}node-v22.12.0-x64.msi" -OutFile $nodeMsi -UseBasicParsing -ErrorAction Stop
        $downloaded = $true
    } catch {
        try {
            Invoke-WebRequest -Uri "https://nodejs.org/dist/v22.12.0/node-v22.12.0-x64.msi" -OutFile $nodeMsi -UseBasicParsing -ErrorAction Stop
            $downloaded = $true
        } catch {
            $downloaded = $false
        }
    }
    if ($downloaded -and (Test-Path $nodeMsi)) {
        Start-Process msiexec.exe -ArgumentList "/i `"$nodeMsi`" /quiet /norestart" -Wait
        Remove-Item $nodeMsi -ErrorAction SilentlyContinue
        Start-Sleep -Seconds 1
        if ((Get-Command npm -ErrorAction SilentlyContinue) -ne $null) {
            Write-Host "✅ Node.js y npm instalados"
            $npmAvailable = $true
        } else {
            Write-Host "⚠️ Instalación completada pero npm no está disponible en este proceso. Reinicia PowerShell si es necesario."
        }
    } else {
        Write-Host "❌ No se pudo descargar el instalador de Node.js. Instala Node.js manualmente desde https://nodejs.org"
    }
} else {
    Write-Host "✅ npm disponible en PATH"
}

$steps[0].done = $true
Show-Checklist
Start-Sleep -Seconds 1.5
cls

# ----------------------
# Paso 2: Copiar .env si falta
# ----------------------
Pause-Step "Paso 2: Verificar si existe .env y copiar si falta"
if (-not (Test-Path "$scriptPath\.env")) {
    Copy-Item "$scriptPath\.env.example" "$scriptPath\.env" -ErrorAction SilentlyContinue
    Write-Host "✅ .env copiado desde .env.example"
} else {
    Write-Host "✅ .env ya existe"
}
$steps[1].done = $true
Show-Checklist
Start-Sleep -Seconds 1.5
cls

# ----------------------
# Paso 3: Instalar dependencias PHP y JS (composer fallback)
# ----------------------
# Helper: obtener ruta de npm (intenta PATH, luego rutas comunes de instalación)
function Get-NpmCmd {
    # Si npm está en PATH, usar "npm"
    if ((Get-Command npm -ErrorAction SilentlyContinue) -ne $null) {
        return "npm"
    }

    # Rutas comunes donde npm.cmd puede residir en Windows
    $possible = @(
        "$env:ProgramFiles\nodejs\npm.cmd",
        "$env:ProgramFiles(x86)\nodejs\npm.cmd",
        "$env:ProgramFiles\nodejs\nnpm\nbin\nnpm-cli.js",
        "$env:ProgramFiles(x86)\nodejs\nnpm\nbin\nnpm-cli.js",
        "$scriptPath\nodejs\nnpm.cmd",
        "$scriptPath\nodejs\nbin\nnpm.cmd"
    )

    foreach ($p in $possible) {
        if (Test-Path $p) {
            # Si es .js, ejecutarlo con node
            if ($p -like "*.js") {
                # encontrar node.exe
                $nodePaths = @(
                    "$env:ProgramFiles\nodejs\node.exe",
                    "$env:ProgramFiles(x86)\nodejs\node.exe",
                    "$scriptPath\nodejs\nnode.exe"
                )
                $nodeFound = $nodePaths | Where-Object { Test-Path $_ } | Select-Object -First 1
                if ($nodeFound) {
                    return "`"$nodeFound`" `"$p`""
                }
            } else {
                return "`"$p`""
            }
        }
    }

    return $null
}

# Antes de usar npm en el script, obtener comando
$npmCmd = Get-NpmCmd
if (-not $npmCmd) {
    Write-Host "⚠️ npm no está disponible ni en PATH ni en rutas comunes. Las tareas con npm se omitirán."
} else {
    Write-Host "ℹ️ Se usará npm desde: $npmCmd"
}

# ----------------------
# Paso 3: Instalar dependencias PHP y JS (composer fallback + npm via ruta completa)
# ----------------------
Pause-Step "Paso 3: Ejecutar composer install y npm install"

# Composer: intentar desde PATH, si no, buscar composer.phar (misma lógica que tenías)
$composerCmdSucceeded = $false
if ((Get-Command composer -ErrorAction SilentlyContinue) -ne $null) {
    try { composer install --no-interaction; $composerCmdSucceeded = $true } catch { $composerCmdSucceeded = $false }
}
if (-not $composerCmdSucceeded) {
    $composerPharPaths = @("$scriptPath\composer.phar", "$env:ProgramData\ComposerSetup\bin\composer.phar", "$env:ProgramFiles\Composer\composer.phar")
    $found = $composerPharPaths | Where-Object { Test-Path $_ } | Select-Object -First 1
    if ($found) {
        php "`"$found`"" install --no-interaction
        Write-Host "✅ Composer ejecutado desde $found"
        $composerCmdSucceeded = $true
    } else {
        Write-Host "⚠️ Composer no disponible. Se omitirá 'composer install'. Instala Composer manualmente si es necesario."
    }
}

# npm install usando $npmCmd (que puede ser "npm" o una ruta a npm.cmd o "node path npm-cli.js")
if ($npmCmd) {
    try {
        if ($npmCmd -match "node.exe") {
            # comando formado como: "C:\Program Files\nodejs\node.exe" "path\to\npm-cli.js"
            iex "$npmCmd install"
        } else {
            & cmd /c "$npmCmd install"
        }
        Write-Host "✅ Dependencias JS instaladas con npm"
    } catch {
        Write-Host "⚠️ Error al ejecutar npm install desde $npmCmd"
    }
} else {
    Write-Host "⚠️ npm no disponible. Omitiendo 'npm install'."
}

$steps[2].done = $true
Show-Checklist
Start-Sleep -Seconds 1.5
cls


# ----------------------
# Paso 4: Generar clave de aplicación
# ----------------------
Pause-Step "Paso 4: Generar clave de aplicación"
try {
    php artisan key:generate --ansi
    Write-Host "✅ Clave generada"
    $steps[3].done = $true
} catch {
    Write-Host "⚠️ No se pudo generar la clave de aplicación."
}
Show-Checklist
Start-Sleep -Seconds 1.5
cls

# ----------------------
# Paso 5: Verificar conexión a base de datos y migraciones
# ----------------------
Pause-Step "Paso 5: Verificar conexión a la base de datos con php artisan"

try {
    $output = php artisan migrate 2>&1

    if ($output -match "No application encryption key has been specified") {
        Write-Host "⚠️ Laravel necesita una clave de aplicación. Ejecuta php artisan key:generate."
    }

    if ($output -match "could not find driver" -or $output -match "SQLSTATE") {
        Write-Host "❌ No se pudo conectar a la base de datos. Revisa DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD en .env"
    } elseif ($output -match "Do you want to create it") {
        Write-Host "ℹ️ La base de datos no existe. Intentando crear y ejecutar migraciones..."
        cmd /c "echo y | php artisan migrate --force" | Out-Null
        php artisan db:seed --force
        Write-Host "✅ Base de datos creada y seeders ejecutados."
        $mysqlConnected = $true
        $steps[4].done = $true
    } else {
        Write-Host "✅ Base de datos detectada. Ejecutando migrate:fresh --force..."
        php artisan migrate:fresh --force
        php artisan db:seed --force
        Write-Host "✅ Migraciones reiniciadas y seeders ejecutados."
        $mysqlConnected = $true
        $steps[4].done = $true
    }
} catch {
    Write-Host "❌ Error al verificar/ejecutar migraciones: $($_.Exception.Message)"
}
Show-Checklist
Start-Sleep -Seconds 1.5
cls

# ----------------------
# Paso 6: Ejecutar migraciones y seeders adicionales si falta
# ----------------------
if ($mysqlConnected) {
    Pause-Step "Paso 6: Ejecutar migraciones y seeders (comprobación final)"
    try {
        php artisan migrate --force
        php artisan db:seed --force
        Write-Host "✅ Migraciones y seeders ejecutados"
        $steps[5].done = $true
    } catch {
        Write-Host "⚠️ Error al ejecutar migraciones/seeders adicionales."
    }
} else {
    Write-Host "⏭️ Migraciones y seeders omitidos por falta de conexión a MySQL"
}
Show-Checklist
Start-Sleep -Seconds 1.5
cls

# ----------------------
# Paso 7: Crear enlace simbólico de storage (verificar antes)
# ----------------------
Pause-Step "Paso 7: Crear enlace simbólico de storage"
try {
    $storageLink = Join-Path $scriptPath "public\storage"
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
Start-Sleep -Seconds 1.5
cls

# Paso 8: Compilar assets con Vite (npm run build) — versión segura
Pause-Step "Paso 8: Compilar assets con Vite (npm run build)"
function Resolve-NpmRunner {
    param($npmCmd)

    # 1) Si "npm" está en PATH, usarlo
    if ((Get-Command npm -ErrorAction SilentlyContinue) -ne $null) {
        return @{ type="npm"; cmd="npm" }
    }

    # 2) Si $npmCmd contiene ruta a npm.cmd, usarla si existe
    if ($npmCmd -and $npmCmd -match "npm\.cmd") {
        $path = $npmCmd.Trim('"')
        if (Test-Path $path) { return @{ type="npmcmd"; cmd="`"$path`"" } }
    }

    # 3) Si $npmCmd es una invocación con node.exe y npm-cli.js, comprobar existencia
    if ($npmCmd -and $npmCmd -match "node") {
        if ($npmCmd -match '"([^"]+node.exe)"\s+"([^"]+npm-cli\.js)"') {
            $nodeExe = $matches[1]; $npmCli = $matches[2]
            if (Test-Path $nodeExe -and Test-Path $npmCli) { return @{ type="nodejs"; cmd="`"$nodeExe`" `"$npmCli`"" } }
        }
    }

    # 4) Construir rutas candidatas con Join-Path y variables (evita insertar ${env:ProgramFiles(x86)} dentro de cadenas)
    $pf = ${env:ProgramFiles}
    $pf86 = ${env:ProgramFiles(x86)}
    $candidates = @(
        Join-Path $pf "nodejs\npm.cmd",
        if ($pf86) { Join-Path $pf86 "nodejs\npm.cmd" } else { $null },
        Join-Path $pf "nodejs\node.exe",
        if ($pf86) { Join-Path $pf86 "nodejs\node.exe" } else { $null },
        Join-Path $scriptPath "nodejs\npm.cmd",
        Join-Path $scriptPath "nodejs\node.exe"
    ) | Where-Object { $_ -and $_ -ne $null }

    foreach ($p in $candidates) {
        if ($p -like "*npm.cmd" -and (Test-Path $p)) { return @{ type="npmcmd"; cmd="`"$p`"" } }
        if ($p -like "*node.exe" -and (Test-Path $p)) {
            $npmCli = Join-Path (Split-Path $p -Parent) "node_modules\npm\bin\npm-cli.js"
            if (Test-Path $npmCli) { return @{ type="nodejs"; cmd="`"$p`" `"$npmCli`"" } }
        }
    }

    return $null
}




$runner = Resolve-NpmRunner $npmCmd

if (-not $runner) {
    Write-Host "⚠️ npm no disponible ni en PATH ni en rutas conocidas. Omitiendo compilación con Vite."
} else {
    try {
        switch ($runner.type) {
            "npm" {
                Write-Host "ℹ️ Usando npm desde PATH..."
                & npm run build 2>&1 | ForEach-Object { Write-Host $_ }
            }
            "npmcmd" {
                Write-Host "ℹ️ Usando npm desde ruta: $($runner.cmd)"
                # ejecutar usando cmd /c para .cmd
                $cmdLine = "$($runner.cmd) run build"
                cmd /c $cmdLine 2>&1 | ForEach-Object { Write-Host $_ }
            }
            "nodejs" {
                Write-Host "ℹ️ Usando node con npm-cli.js: $($runner.cmd)"
                # runner.cmd ya es: "C:\...\node.exe" "...\npm-cli.js"
                & cmd /c "$($runner.cmd) run build" 2>&1 | ForEach-Object { Write-Host $_ }
            }
            default {
                Write-Host "⚠️ Modo de ejecución desconocido para npm. Omitiendo."
            }
        }
        Write-Host "✅ Intento de compilación finalizado (revisa salida para errores)."
        $steps[7].label = "Compilar assets con Vite"
        $steps[7].done = $true
    } catch {
        Write-Host "⚠️ Error al ejecutar la compilación: $($_.Exception.Message)"
    }
}

Show-Checklist
Start-Sleep -Seconds 1.5

# Mantener la consola abierta y evitar cierre accidental

cls
# Paso 9: Iniciar servidor Laravel y abrir Edge automáticamente (si se desea)
if ($mysqlConnected) {
    Pause-Step "Paso 9: ¿Deseas iniciar el servidor Laravel con php artisan serve?"

    $respuesta = Read-Host "🟢 ¿Iniciar servidor ahora y abrir Edge? (s/n)"
    if ($respuesta -eq "s" -or $respuesta -eq "S") {
        Write-Host "`n🚀 Iniciando servidor Laravel en http://localhost:8000 ..."

        # Iniciar php artisan serve en segundo plano (nueva ventana)
        $phpExe = "php"
        $serveArgs = "artisan serve --host=127.0.0.1 --port=8000"
        Start-Process -FilePath $phpExe -ArgumentList $serveArgs -WindowStyle Normal

        # Esperar a que el puerto 8000 esté aceptando conexiones (timeout en segundos)
        $maxWait = 20
        $waited = 0
        while ($waited -lt $maxWait) {
            try {
                $tcp = New-Object System.Net.Sockets.TcpClient
                $async = $tcp.BeginConnect("127.0.0.1", 8000, $null, $null)
                $success = $async.AsyncWaitHandle.WaitOne(1000)
                if ($success -and $tcp.Connected) {
                    $tcp.EndConnect($async)
                    $tcp.Close()
                    break
                }
                $tcp.Close()
            } catch { }
            Start-Sleep -Seconds 1
            $waited++
        }

        if ($waited -lt $maxWait) {
            Write-Host "✅ Servidor listo. Abriendo Edge en http://localhost:8000"
            # Intentar abrir Microsoft Edge (msedge) — funciona si msedge está en PATH
            try {
                Start-Process "msedge" "http://localhost:8000"
            } catch {
                # Si no existe msedge en PATH, intentar abrir con el navegador por defecto
                Start-Process "http://localhost:8000"
            }
        } else {
            Write-Host "⚠️ Tiempo de espera agotado. El servidor puede no estar listo aún."
            Write-Host "ℹ️ Abre manualmente: http://localhost:8000"
        }

        # No hacemos exit para que el resto del script pueda continuar
    } else {
        Write-Host "ℹ️ Puedes iniciar el servidor manualmente con: php artisan serve"
    }
}



# Final
Write-Host "`n🎉 ¡Configuración completa!"
if (-not $mysqlConnected) {
    Write-Host "⚠️ Las migraciones y el servidor Laravel no se ejecutaron porque no se pudo conectar a MySQL."
}
Write-Host "`nPresiona cualquier tecla para cerrar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
