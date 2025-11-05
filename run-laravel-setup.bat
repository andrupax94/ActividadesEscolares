@echo off
:: Verifica si se está ejecutando como administrador
net session >nul 2>&1
if %errorLevel% == 0 (
    echo Ejecutando script PowerShell como administrador...
    powershell -ExecutionPolicy Bypass -File "%~dp0setup-laravel.ps1"
) else (
    echo Requiere privilegios de administrador. Reintentando...
    powershell -Command "Start-Process powershell -ArgumentList '-ExecutionPolicy Bypass -File \"\"%~dp0setup-laravel.ps1\"\"' -Verb RunAs"
)
pause
