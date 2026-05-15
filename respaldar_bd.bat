@echo off
setlocal

powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\backup_database.ps1"
set EXITCODE=%ERRORLEVEL%

echo.
if not "%EXITCODE%"=="0" (
    echo El respaldo fallo.
) else (
    echo El respaldo termino correctamente.
)

pause
exit /b %EXITCODE%
