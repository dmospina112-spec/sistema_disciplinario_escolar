<?php

declare(strict_types=1);

/** Password reset and change flows shared by the two COMPORTATE entry points. */
function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function docenteHasPhoneColumn(mysqli $conn): bool
{
    static $hasColumn = null;
    if ($hasColumn !== null) return $hasColumn;
    $result = $conn->query("SHOW COLUMNS FROM docentes LIKE 'telefono'");
    $hasColumn = $result !== false && $result->num_rows > 0;
    return $hasColumn;
}

function requireCsrfToken(): void
{
    $provided = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $stored = (string) ($_SESSION['csrf_token'] ?? '');
    if ($provided === '' || $stored === '' || !hash_equals($stored, $provided)) {
        jsonResponse(403, ['success' => false, 'error' => 'La sesión del formulario venció. Recarga la página e inténtalo de nuevo.']);
    }
}

function isStrongPassword(string $password): bool
{
    return strlen($password) >= 10
        && preg_match('/[a-z]/', $password) === 1
        && preg_match('/[A-Z]/', $password) === 1
        && preg_match('/[0-9]/', $password) === 1;
}

function validateNewPassword(string $password): void
{
    if (!isStrongPassword($password)) {
        jsonResponse(400, ['success' => false, 'error' => 'La contraseña debe tener mínimo 10 caracteres e incluir mayúscula, minúscula y número.']);
    }
}

function requestIpHash(): string
{
    return hash_hmac('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), (string) getenv('APP_SECRET'));
}

function requestEmailHash(string $email): string
{
    return hash_hmac('sha256', strtolower(trim($email)), (string) getenv('APP_SECRET'));
}

function logPasswordResetFailure(string $stage, Throwable $exception): void
{
    $message = $exception->getMessage();
    $smtpPassword = (string) getenv('SMTP_PASSWORD');
    $secrets = [$smtpPassword, (string) getenv('APP_SECRET')];
    if (strtolower((string) getenv('SMTP_HOST')) === 'smtp.gmail.com') {
        $secrets[] = preg_replace('/\s+/', '', trim($smtpPassword)) ?? trim($smtpPassword);
    }
    foreach ($secrets as $secret) {
        if ($secret !== '') $message = str_replace($secret, '[redacted]', $message);
    }
    $message = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', '[redacted-email]', $message) ?? '';
    $message = preg_replace('/\b[a-f0-9]{64}\b/i', '[redacted-token]', $message) ?? '';
    error_log('Password reset failed at ' . $stage . ': ' . get_class($exception) . ' (' . $exception->getCode() . '): ' . $message);
}

function logPasswordResetDatabaseFailure(string $stage, mysqli $conn): void
{
    error_log('Password reset database failure at ' . $stage . ' (MySQL ' . $conn->errno . '): ' . $conn->error);
}

function requestPasswordReset(mysqli $conn, array $data): void
{
    requireCsrfToken();
    $email = normalizeEmailAddress($data['correo'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(400, ['success' => false, 'error' => 'Ingresa un correo electrónico válido.']);
    }

    $genericMessage = 'Si el correo corresponde a una cuenta activa y el envío está configurado, recibirás instrucciones. Si no llega el mensaje, contacta a la administradora.';
    if (strlen((string) getenv('APP_SECRET')) < 32) {
        jsonResponse(503, ['success' => false, 'error' => 'El servicio de recuperación requiere completar su configuración. Contacta a la administradora.']);
    }
    $ipHash = requestIpHash();
    $emailHash = requestEmailHash($email);
    $now = gmdate('Y-m-d H:i:s');

    // Limit 5 requests per IP per hour and one request per address per minute.
    $limit = $conn->prepare('SELECT COUNT(*) AS total FROM password_reset_requests WHERE ip_hash = ? AND created_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 HOUR)');
    if (!$limit) {
        logPasswordResetDatabaseFailure('IP rate-limit query prepare', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta más tarde.']);
    }
    $limit->bind_param('s', $ipHash);
    if (!$limit->execute()) {
        $limit->close();
        logPasswordResetDatabaseFailure('IP rate-limit query execute', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta nuevamente.']);
    }
    $total = (int) (($limit->get_result()->fetch_assoc()['total'] ?? 0));
    $limit->close();

    $recent = $conn->prepare('SELECT id FROM password_reset_requests WHERE email_hash = ? AND created_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 MINUTE) LIMIT 1');
    if (!$recent) {
        logPasswordResetDatabaseFailure('email rate-limit query prepare', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta más tarde.']);
    }
    $recent->bind_param('s', $emailHash);
    if (!$recent->execute()) {
        $recent->close();
        logPasswordResetDatabaseFailure('email rate-limit query execute', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta nuevamente.']);
    }
    $hasRecent = (bool) $recent->get_result()->fetch_assoc();
    $recent->close();

    if ($total >= 5 || $hasRecent) {
        jsonResponse(200, ['success' => true, 'message' => $genericMessage]);
    }

    $conn->query('DELETE FROM password_reset_requests WHERE created_at < DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY)');

    $log = $conn->prepare('INSERT INTO password_reset_requests (email_hash, ip_hash, created_at) VALUES (?, ?, ?)');
    if (!$log) {
        logPasswordResetDatabaseFailure('rate-limit record prepare', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta más tarde.']);
    }
    $log->bind_param('sss', $emailHash, $ipHash, $now);
    if (!$log->execute()) {
        $log->close();
        logPasswordResetDatabaseFailure('rate-limit record insert', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta más tarde.']);
    }
    $log->close();

    $stmt = $conn->prepare('SELECT id, nombre, apellido, correo FROM docentes WHERE LOWER(correo) = ? AND activo = 1 LIMIT 1');
    if (!$stmt) {
        logPasswordResetDatabaseFailure('active account lookup prepare', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta nuevamente.']);
    }
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        $stmt->close();
        logPasswordResetDatabaseFailure('active account lookup execute', $conn);
        jsonResponse(503, ['success' => false, 'error' => 'No fue posible procesar la solicitud. Intenta nuevamente.']);
    }
    $account = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    if ($account) {
        $tokenHash = '';
        try {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $accountId = (int) $account['id'];
            $delete = $conn->prepare('DELETE FROM password_reset_tokens WHERE docente_id = ?');
            if (!$delete) throw new RuntimeException('No se pudo preparar la invalidación de tokens anteriores.');
            $delete->bind_param('i', $accountId);
            if (!$delete->execute()) throw new RuntimeException('No se pudieron invalidar tokens anteriores.');
            $delete->close();
            $conn->query('DELETE FROM password_reset_tokens WHERE expires_at < DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY) OR (used_at IS NOT NULL AND used_at < DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY))');

            $expiresAt = gmdate('Y-m-d H:i:s', time() + 1800);
            $insert = $conn->prepare('INSERT INTO password_reset_tokens (docente_id, token_hash, expires_at, created_at) VALUES (?, ?, ?, ?)');
            if (!$insert) throw new RuntimeException('No se pudo preparar el token temporal.');
            $insert->bind_param('isss', $accountId, $tokenHash, $expiresAt, $now);
            if (!$insert->execute()) throw new RuntimeException('No se pudo guardar el token temporal.');
            $insert->close();

            $baseUrl = rtrim(trim((string) (getenv('APP_BASE_URL') ?: '')), '/');
            if ($baseUrl === '' || !filter_var($baseUrl, FILTER_VALIDATE_URL) || !in_array((string) parse_url($baseUrl, PHP_URL_SCHEME), ['http', 'https'], true)) {
                throw new RuntimeException('Configura APP_BASE_URL con la URL pública de la aplicación.');
            }
            if (strtolower((string) parse_url($baseUrl, PHP_URL_SCHEME)) !== 'https' && !in_array(strtolower((string) parse_url($baseUrl, PHP_URL_HOST)), ['localhost', '127.0.0.1'], true)) {
                throw new RuntimeException('La recuperación de contraseñas requiere HTTPS en producción.');
            }

            // Keep the bearer token in the URL fragment so web server access logs never receive it.
            $resetUrl = $baseUrl . '/restablecer.php#token=' . rawurlencode($token);
            $name = trim((string) $account['nombre'] . ' ' . (string) $account['apellido']);
            $body = "Hola {$name},\n\nRecibimos una solicitud para restablecer tu contraseña de COMPORTATE.\n\n"
                . "Abre este enlace para definir una nueva contraseña:\n{$resetUrl}\n\n"
                . "El enlace vence en 30 minutos y solo puede utilizarse una vez. Si no solicitaste el cambio, ignora este mensaje; tu contraseña actual seguirá activa.\n\nCOMPORTATE";
            sendEmailUsingSmtp((string) $account['correo'], 'Recuperación de contraseña – COMPORTATE', $body, getMailTransportConfig());
        } catch (Throwable $exception) {
            if ($tokenHash !== '') {
                $cleanup = $conn->prepare('DELETE FROM password_reset_tokens WHERE token_hash = ?');
                if ($cleanup) {
                    $cleanup->bind_param('s', $tokenHash);
                    $cleanup->execute();
                    $cleanup->close();
                }
            }
            logPasswordResetFailure('token/email delivery', $exception);
            jsonResponse(503, ['success' => false, 'error' => 'No se pudo enviar el enlace de recuperación. Verifica la configuración del correo o contacta a la administradora.']);
        }
    }

    jsonResponse(200, ['success' => true, 'message' => $genericMessage]);
}

function resetPasswordWithToken(mysqli $conn, array $data): void
{
    requireCsrfToken();
    $token = trim((string) ($data['token'] ?? ''));
    $password = (string) ($data['contrasena_nueva'] ?? '');
    $confirmation = (string) ($data['confirmacion'] ?? '');
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) jsonResponse(400, ['success' => false, 'error' => 'El enlace no es válido o ya venció. Solicita uno nuevo.']);
    if ($password !== $confirmation) jsonResponse(400, ['success' => false, 'error' => 'La confirmación de la contraseña no coincide.']);
    validateNewPassword($password);

    $tokenHash = hash('sha256', $token);
    $stmt = $conn->prepare('SELECT id, docente_id FROM password_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at > UTC_TIMESTAMP() LIMIT 1');
    if (!$stmt) jsonResponse(503, ['success' => false, 'error' => 'No fue posible validar el enlace. Ejecuta la migración de restablecimiento.']);
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    if (!$row) jsonResponse(400, ['success' => false, 'error' => 'El enlace no es válido o ya venció. Solicita uno nuevo.']);

    $newHash = password_hash($password, PASSWORD_DEFAULT);
    if ($newHash === false) jsonResponse(500, ['success' => false, 'error' => 'No se pudo actualizar la contraseña.']);
    $conn->begin_transaction();
    try {
        $update = $conn->prepare('UPDATE docentes SET password = ? WHERE id = ? AND activo = 1 LIMIT 1');
        if (!$update) throw new RuntimeException('No se pudo preparar la actualización.');
        $docenteId = (int) $row['docente_id'];
        $update->bind_param('si', $newHash, $docenteId);
        if (!$update->execute() || $update->affected_rows !== 1) throw new RuntimeException('No se pudo actualizar la cuenta.');
        $update->close();
        $mark = $conn->prepare('UPDATE password_reset_tokens SET used_at = UTC_TIMESTAMP() WHERE id = ? AND used_at IS NULL');
        if (!$mark) throw new RuntimeException('No se pudo invalidar el enlace.');
        $tokenId = (int) $row['id'];
        $mark->bind_param('i', $tokenId);
        if (!$mark->execute() || $mark->affected_rows !== 1) throw new RuntimeException('El enlace ya fue utilizado.');
        $mark->close();
        $conn->commit();
    } catch (Throwable $exception) {
        $conn->rollback();
        jsonResponse(400, ['success' => false, 'error' => 'No se pudo completar el restablecimiento. Solicita un enlace nuevo.']);
    }
    jsonResponse(200, ['success' => true, 'message' => 'Contraseña restablecida. Ya puedes iniciar sesión con la nueva clave.']);
}

function changeOwnPassword(mysqli $conn, array $data): void
{
    requireCsrfToken();
    $user = getAuthenticatedUserSession();
    if (!$user || (int) ($user['id'] ?? 0) <= 0) jsonResponse(401, ['success' => false, 'error' => 'Inicia sesión para cambiar tu contraseña.']);
    $current = (string) ($data['contrasena_actual'] ?? '');
    $password = (string) ($data['contrasena_nueva'] ?? '');
    $confirmation = (string) ($data['confirmacion'] ?? '');
    if ($current === '' || $password === '' || $confirmation === '') jsonResponse(400, ['success' => false, 'error' => 'Completa todos los campos.']);
    if ($password !== $confirmation) jsonResponse(400, ['success' => false, 'error' => 'La confirmación de la contraseña no coincide.']);
    validateNewPassword($password);
    $userId = (int) $user['id'];
    $stmt = $conn->prepare('SELECT password, activo FROM docentes WHERE id = ? LIMIT 1');
    if (!$stmt) jsonResponse(503, ['success' => false, 'error' => 'No fue posible validar la cuenta.']);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    $storedPassword = is_array($row) ? (string) ($row['password'] ?? '') : '';
    $currentMatches = $storedPassword !== '' && password_verify($current, $storedPassword);
    if (!$row || (int) $row['activo'] !== 1 || !$currentMatches) {
        jsonResponse(401, ['success' => false, 'error' => 'La contraseña actual es incorrecta.']);
    }
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $update = $conn->prepare('UPDATE docentes SET password = ? WHERE id = ? LIMIT 1');
    if (!$update) jsonResponse(503, ['success' => false, 'error' => 'No fue posible actualizar la contraseña.']);
    $update->bind_param('si', $newHash, $userId);
    $ok = $update->execute();
    $update->close();
    if (!$ok) jsonResponse(503, ['success' => false, 'error' => 'No fue posible actualizar la contraseña.']);
    jsonResponse(200, ['success' => true, 'message' => 'Tu contraseña se cambió correctamente.']);
}
