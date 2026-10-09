<?php

declare(strict_types=1);

const APP_AUTH_IDLE_TIMEOUT = 7200;

function getProjectSessionDirectory(): string
{
    return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';
}

function normalizeSessionSavePath(string $value): string
{
    $path = trim($value);
    if ($path === '') {
        return '';
    }

    if (preg_match('/^\d+;/', $path) === 1) {
        $parts = explode(';', $path, 2);
        $path = $parts[1] ?? '';
    }

    return trim($path, "\"' \t\n\r\0\x0B");
}

function isWritableSessionDirectory(string $path): bool
{
    return $path !== '' && is_dir($path) && is_writable($path);
}

function prepareProjectSessionDirectory(): string
{
    $directory = getProjectSessionDirectory();

    if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException('No se pudo crear la carpeta local de sesiones.');
    }

    if (!isWritableSessionDirectory($directory)) {
        throw new RuntimeException('La carpeta local de sesiones no tiene permisos de escritura.');
    }

    return $directory;
}

function ensureAppSessionStarted(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.gc_maxlifetime', (string) APP_AUTH_IDLE_TIMEOUT);
    $configuredPath = normalizeSessionSavePath((string) ini_get('session.save_path'));
    if (!isWritableSessionDirectory($configuredPath)) {
        session_save_path(prepareProjectSessionDirectory());
    }

    if (!session_start()) {
        throw new RuntimeException('No se pudo iniciar la sesión de PHP.');
    }

    if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])) {
        $lastActivity = (int) ($_SESSION['auth_last_activity'] ?? 0);
        if ($lastActivity > 0 && (time() - $lastActivity) > APP_AUTH_IDLE_TIMEOUT) {
            unset($_SESSION['auth_user'], $_SESSION['auth_last_activity']);
            $_SESSION['auth_expired'] = true;
        } else {
            $_SESSION['auth_last_activity'] = time();
        }
    }
}
