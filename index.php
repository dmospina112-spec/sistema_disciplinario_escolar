<?php
declare(strict_types=1);

require_once __DIR__ . '/app/backend/config.php';
require_once __DIR__ . '/app/backend/session.php';

try {
    loadLocalEnv();
    ensureAppSessionStarted();
    $authUser = $_SESSION['auth_user'] ?? null;
    if (is_array($authUser)) {
        $role = strtolower((string) ($authUser['rol'] ?? 'docente'));
        header('Location: ' . ($role === 'administrador' ? 'panel_admin.php' : 'panel_docente.php'));
        exit;
    }
} catch (Throwable $exception) {
    // Si la sesión no puede iniciarse, se conserva el acceso a la landing pública.
}

require __DIR__ . '/landing.php';
