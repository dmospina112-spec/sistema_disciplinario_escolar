<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['auth_user'] = [
    'id' => 1,
    'usuario' => 'admin',
    'nombre' => 'Administrador',
    'apellido' => 'Principal',
    'rol' => 'administrador',
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => true,
    'session_id' => session_id(),
    'auth_user' => $_SESSION['auth_user'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
