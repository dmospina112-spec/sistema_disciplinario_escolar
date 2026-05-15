param(
    [string]$ProjectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path,
    [string]$OutputDir = '',
    [string]$FilePrefix = ''
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
$config = Read-EnvFile -Path $envPath

$dbHost = if ([string]::IsNullOrWhiteSpace($config['DB_HOST'])) { 'localhost' } else { $config['DB_HOST'].Trim() }
$dbPort = if ([string]::IsNullOrWhiteSpace($config['DB_PORT'])) { '3306' } else { $config['DB_PORT'].Trim() }
$dbUser = if ([string]::IsNullOrWhiteSpace($config['DB_USER'])) { 'root' } else { $config['DB_USER'].Trim() }
$dbPass = if ($config.ContainsKey('DB_PASS')) { $config['DB_PASS'] } else { '' }
$dbName = if ([string]::IsNullOrWhiteSpace($config['DB_NAME'])) { 'app_educativa_recuperada' } else { $config['DB_NAME'].Trim() }

if ([string]::IsNullOrWhiteSpace($OutputDir)) {
    $OutputDir = Join-Path $ProjectRoot 'storage\backups'
}

if ([string]::IsNullOrWhiteSpace($FilePrefix)) {
    $FilePrefix = $dbName
}

$FilePrefix = ($FilePrefix -replace '[\\/:*?"<>|]+', '_').Trim()
if ([string]::IsNullOrWhiteSpace($FilePrefix)) {
    $FilePrefix = 'respaldo_bd'
}

New-Item -ItemType Directory -Path $OutputDir -Force | Out-Null

$timestamp = Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'
$backupFile = Join-Path $OutputDir ($FilePrefix + '_' + $timestamp + '.sql')
$envBackupFile = Join-Path $OutputDir ($FilePrefix + '_' + $timestamp + '.env.backup')
$schemaSource = Join-Path $ProjectRoot 'database\database.sql'
$schemaBackupFile = Join-Path $OutputDir ($FilePrefix + '_' + $timestamp + '.schema.sql')
$latestInfoFile = Join-Path $OutputDir 'ultimo_respaldo.txt'

$mysqldumpExe = Resolve-MySqlTool -ExeName 'mysqldump.exe' -ProjectRootPath $ProjectRoot

$dumpArgs = @(
    '--default-character-set=utf8mb4',
    '--single-transaction',
    '--routines',
    '--triggers',
    '--events',
    '--add-drop-table',
    "--host=$dbHost",
    "--port=$dbPort",
    "--user=$dbUser"
)

if (-not [string]::IsNullOrEmpty($dbPass)) {
    $dumpArgs += "--password=$dbPass"
}

$dumpArgs += "--result-file=$backupFile"
$dumpArgs += $dbName

Write-Host "Creando respaldo de $dbName ..."
& $mysqldumpExe @dumpArgs

if ($LASTEXITCODE -ne 0) {
    throw "mysqldump termino con codigo $LASTEXITCODE."
}

if (Test-Path -LiteralPath $envPath) {
    Copy-Item -LiteralPath $envPath -Destination $envBackupFile -Force
}

if (Test-Path -LiteralPath $schemaSource) {
    Copy-Item -LiteralPath $schemaSource -Destination $schemaBackupFile -Force
}

@(
    "Base de datos: $dbName"
    "Archivo SQL: $backupFile"
    "Archivo .env: $envBackupFile"
    "Archivo schema: $schemaBackupFile"
    "Fecha: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
) | Set-Content -LiteralPath $latestInfoFile -Encoding UTF8

Write-Host ''
Write-Host 'Respaldo creado correctamente.'
Write-Host "SQL: $backupFile"
Write-Host "Registro: $latestInfoFile"
