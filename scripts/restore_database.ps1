param(
    [string]$ProjectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path,
    [string]$BackupFile = ''
)

$ErrorActionPreference = 'Stop'

function Read-EnvFile {
    param([string]$Path)

    $values = @{}

    if (-not (Test-Path -LiteralPath $Path)) {
        return $values
    }

    foreach ($rawLine in Get-Content -LiteralPath $Path) {
        $line = $rawLine.Trim()
        if ($line -eq '' -or $line.StartsWith('#')) {
            continue
        }

        $parts = $line -split '=', 2
        if ($parts.Count -ne 2) {
            continue
        }

        $key = $parts[0].Trim()
        $value = $parts[1]

        if ($key -ne '') {
            $values[$key] = $value
        }
    }

    return $values
}

function Resolve-MySqlTool {
    param([string]$ExeName, [string]$ProjectRootPath)

    $candidates = @(
        'C:\xampp\mysql\bin',
        'C:\xampp\bin\mysql',
        (Join-Path $ProjectRootPath 'mysql\bin')
    )

    foreach ($dir in $candidates) {
        if ([string]::IsNullOrWhiteSpace($dir)) {
            continue
        }

        $candidate = Join-Path $dir $ExeName
        if (Test-Path -LiteralPath $candidate) {
            return $candidate
        }
    }

    $command = Get-Command $ExeName -ErrorAction SilentlyContinue
    if ($null -ne $command) {
        return $command.Source
    }

    throw "No se encontro $ExeName. Verifica la instalacion de XAMPP/MySQL."
}

$envPath = Join-Path $ProjectRoot '.env'
$backupDir = Join-Path $ProjectRoot 'storage\backups'
$config = Read-EnvFile -Path $envPath

$dbHost = if ([string]::IsNullOrWhiteSpace($config['DB_HOST'])) { 'localhost' } else { $config['DB_HOST'].Trim() }
$dbPort = if ([string]::IsNullOrWhiteSpace($config['DB_PORT'])) { '3306' } else { $config['DB_PORT'].Trim() }
$dbUser = if ([string]::IsNullOrWhiteSpace($config['DB_USER'])) { 'root' } else { $config['DB_USER'].Trim() }
$dbPass = if ($config.ContainsKey('DB_PASS')) { $config['DB_PASS'] } else { '' }
$dbName = if ([string]::IsNullOrWhiteSpace($config['DB_NAME'])) { 'app_educativa_recuperada' } else { $config['DB_NAME'].Trim() }

if ([string]::IsNullOrWhiteSpace($BackupFile)) {
    if (-not (Test-Path -LiteralPath $backupDir)) {
        throw 'No existe la carpeta storage\backups con respaldos para restaurar.'
    }

    $latestBackup = Get-ChildItem -LiteralPath $backupDir -Filter '*.sql' |
        Where-Object { $_.Name -notlike '*.schema.sql' } |
        Sort-Object LastWriteTime -Descending |
        Select-Object -First 1

    if ($null -eq $latestBackup) {
        throw 'No se encontro ningun archivo .sql para restaurar.'
    }

    $BackupFile = $latestBackup.FullName
}

if (-not (Test-Path -LiteralPath $BackupFile)) {
    throw "No existe el archivo de respaldo: $BackupFile"
}

$mysqlExe = Resolve-MySqlTool -ExeName 'mysql.exe' -ProjectRootPath $ProjectRoot
$backupScript = Join-Path $PSScriptRoot 'backup_database.ps1'

Write-Host "Base de datos destino: $dbName"
Write-Host "Archivo a restaurar: $BackupFile"
Write-Host ''

$confirmation = Read-Host 'Escribe SI para continuar con la restauracion'
if ($confirmation -cne 'SI') {
    Write-Host 'Restauracion cancelada.'
    exit 1
}

if (Test-Path -LiteralPath $backupScript) {
    Write-Host ''
    Write-Host 'Creando respaldo de seguridad previo a la restauracion ...'
    & $backupScript -ProjectRoot $ProjectRoot -OutputDir $backupDir -FilePrefix ($dbName + '_pre_restore')
}

$mysqlArgs = @(
    '--default-character-set=utf8mb4',
    "--host=$dbHost",
    "--port=$dbPort",
    "--user=$dbUser"
)

if (-not [string]::IsNullOrEmpty($dbPass)) {
    $mysqlArgs += "--password=$dbPass"
}

$escapedDbName = $dbName.Replace('`', '``')
$createDbSql = "CREATE DATABASE IF NOT EXISTS ``$escapedDbName`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

& $mysqlExe @mysqlArgs --execute=$createDbSql
if ($LASTEXITCODE -ne 0) {
    throw "No se pudo crear o verificar la base de datos $dbName."
}

Write-Host ''
Write-Host 'Restaurando respaldo ...'

Get-Content -LiteralPath $BackupFile -Raw -Encoding UTF8 | & $mysqlExe @mysqlArgs $dbName

if ($LASTEXITCODE -ne 0) {
    throw "mysql termino con codigo $LASTEXITCODE durante la restauracion."
}

Write-Host ''
Write-Host 'Restauracion completada correctamente.'
