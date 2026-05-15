@echo off
setlocal

if "%~1"=="" (
    powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\restore_database.ps1"
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\restore_database.ps1" -BackupFile "%~1"
)

set EXITCODE=%ERRORLEVEL%

echo.
if not "%EXITCODE%"=="0" (
    echo La restauracion no se completo.
) else (
    echo La restauracion termino correctamente.
)

pause
exit /b %EXITCODE%
