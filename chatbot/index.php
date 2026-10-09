<?php
declare(strict_types=1);

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$authUser = $_SESSION['auth_user'] ?? null;
if (is_array($authUser)) {
    $role = strtolower((string) ($authUser['rol'] ?? 'docente'));
    header('Location: ' . ($role === 'administrador' ? 'panel_admin.php' : 'panel_docente.php'), true, 302);
    exit;
}
header('Location: ../', true, 302);
exit;
