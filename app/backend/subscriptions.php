<?php

declare(strict_types=1);

/**
 * Servicios de suscripciones. El archivo no modifica cuentas existentes por
 * defecto: una cuenta antigua sin registro de suscripción conserva su acceso.
 */

const SUBSCRIPTION_TRIAL_DAYS = 14;

function subscriptionNow(): DateTimeImmutable
{
    return new DateTimeImmutable('now', new DateTimeZone('America/Bogota'));
}

function subscriptionNowSql(): string
{
    return subscriptionNow()->format('Y-m-d H:i:s');
}

function subscriptionPlanByCode(mysqli $conn, string $code): ?array
{
    $stmt = $conn->prepare(
        'SELECT id, codigo, nombre, precio, duracion_dias, activo
         FROM subscription_plans
         WHERE codigo = ?
         LIMIT 1'
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo consultar el plan de suscripción.');
    }

    $stmt->bind_param('s', $code);
    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException('No se pudo consultar el plan de suscripción.');
    }

    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
}

function subscriptionExpireDue(mysqli $conn): void
{
    $now = subscriptionNowSql();
    $stmt = $conn->prepare(
        "UPDATE docente_suscripciones
         SET estado = CASE
             WHEN estado = 'PRUEBA ACTIVA' THEN 'PRUEBA FINALIZADA'
             WHEN estado = 'ACTIVA' THEN 'VENCIDA'
             ELSE estado
         END,
         updated_at = CURRENT_TIMESTAMP
         WHERE fecha_vencimiento <= ?
           AND estado IN ('PRUEBA ACTIVA', 'ACTIVA')"
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo validar el vencimiento de las suscripciones.');
    }

    $stmt->bind_param('s', $now);
    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException('No se pudo actualizar el vencimiento de las suscripciones.');
    }
    $stmt->close();
}

function subscriptionCurrent(mysqli $conn, int $docenteId): ?array
{
    if ($docenteId <= 0) {
        return null;
    }

    subscriptionExpireDue($conn);
    $stmt = $conn->prepare(
        'SELECT s.id, s.docente_id, s.plan_id, p.codigo AS plan_codigo,
                p.nombre AS plan_nombre, p.precio AS plan_precio,
                s.tipo_plan, s.fecha_inicio, s.fecha_vencimiento,
                s.duracion_dias, s.estado, s.estado_pago, s.pago_id,
                s.registrado_por, s.activado_por, s.activado_at,
                s.created_at, s.updated_at
         FROM docente_suscripciones s
         INNER JOIN subscription_plans p ON p.id = s.plan_id
         WHERE s.docente_id = ?
         ORDER BY CASE WHEN s.estado IN ("ACTIVA", "PRUEBA ACTIVA") THEN 0 ELSE 1 END,
                  s.fecha_inicio DESC, s.id DESC
         LIMIT 1'
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo consultar la suscripción del docente.');
    }

    $stmt->bind_param('i', $docenteId);
    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException('No se pudo consultar la suscripción del docente.');
    }

    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
}

function subscriptionAccess(mysqli $conn, int $docenteId, string $role = 'docente'): array
{
    if ($role === 'administrador') {
        return [
            'allowed' => true,
            'status' => 'ADMINISTRADOR',
            'subscription' => null,
        ];
    }

    $subscription = subscriptionCurrent($conn, $docenteId);
    if (!$subscription) {
        // Compatibilidad: no se asignan pruebas retroactivas ni se bloquean
        // docentes antiguos que aún no tienen una suscripción registrada.
        return [
            'allowed' => true,
            'status' => 'SIN ASIGNAR',
            'subscription' => null,
        ];
    }

    $allowed = in_array((string) ($subscription['estado'] ?? ''), ['PRUEBA ACTIVA', 'ACTIVA'], true)
        && (string) ($subscription['fecha_vencimiento'] ?? '') > subscriptionNowSql();

    return [
        'allowed' => $allowed,
        'status' => (string) ($subscription['estado'] ?? ''),
        'subscription' => $subscription,
    ];
}

function requireActiveSubscriptionAccess(mysqli $conn): array
{
    $user = getAuthenticatedUserSession();
    if (!$user) {
        jsonResponse(401, [
            'success' => false,
            'error' => 'Debes iniciar sesión para continuar.',
        ]);
    }

    $access = subscriptionAccess($conn, (int) ($user['id'] ?? 0), strtolower((string) ($user['rol'] ?? 'docente')));
    if (!$access['allowed']) {
        $subscription = is_array($access['subscription']) ? $access['subscription'] : [];
        jsonResponse(403, [
            'success' => false,
            'subscription_required' => true,
            'subscription_status' => $access['status'],
            'subscription' => [
                'estado' => $access['status'],
                'fecha_vencimiento' => (string) ($subscription['fecha_vencimiento'] ?? ''),
                'plan_nombre' => (string) ($subscription['plan_nombre'] ?? ''),
            ],
            'error' => 'Tu periodo de acceso finalizó. Comunícate con la administración para activar o renovar tu suscripción.',
        ]);
    }

    return $access;
}

function subscriptionPayload(mysqli $conn, int $docenteId, string $role = 'docente'): array
{
    $access = subscriptionAccess($conn, $docenteId, $role);
    $subscription = is_array($access['subscription']) ? $access['subscription'] : null;
    return [
        'allowed' => (bool) $access['allowed'],
        'status' => (string) $access['status'],
        'plan_nombre' => $subscription ? (string) ($subscription['plan_nombre'] ?? '') : '',
        'fecha_inicio' => $subscription ? (string) ($subscription['fecha_inicio'] ?? '') : '',
        'fecha_vencimiento' => $subscription ? (string) ($subscription['fecha_vencimiento'] ?? '') : '',
        'estado_pago' => $subscription ? (string) ($subscription['estado_pago'] ?? '') : '',
    ];
}

function createTrialSubscription(mysqli $conn, int $docenteId, ?int $registeredBy = null): int
{
    $plan = subscriptionPlanByCode($conn, 'prueba_gratuita');
    if (!$plan || (int) ($plan['activo'] ?? 0) !== 1) {
        throw new RuntimeException('El plan de prueba gratuita no está configurado.');
    }

    $existing = $conn->prepare('SELECT id FROM docente_suscripciones WHERE docente_id = ? LIMIT 1');
    if (!$existing) {
        throw new RuntimeException('No se pudo validar la prueba gratuita existente.');
    }
    $existing->bind_param('i', $docenteId);
    $existing->execute();
    $hasExisting = $existing->get_result()->fetch_assoc();
    $existing->close();
    if ($hasExisting) {
        throw new RuntimeException('La cuenta ya tiene una suscripción registrada.');
    }

    $start = subscriptionNow();
    $end = $start->modify('+' . SUBSCRIPTION_TRIAL_DAYS . ' days');
    $planId = (int) $plan['id'];
    $type = 'PRUEBA GRATUITA';
    $state = 'PRUEBA ACTIVA';
    $paymentState = 'SIN PAGO';
    $duration = SUBSCRIPTION_TRIAL_DAYS;
    $startSql = $start->format('Y-m-d H:i:s');
    $endSql = $end->format('Y-m-d H:i:s');
    $registeredByValue = $registeredBy !== null && $registeredBy > 0 ? $registeredBy : 0;

    $stmt = $conn->prepare(
        'INSERT INTO docente_suscripciones
            (docente_id, plan_id, tipo_plan, fecha_inicio, fecha_vencimiento,
             duracion_dias, estado, estado_pago, registrado_por)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, 0))'
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo preparar la activación de la prueba gratuita.');
    }

    $stmt->bind_param(
        'iisssissi',
        $docenteId,
        $planId,
        $type,
        $startSql,
        $endSql,
        $duration,
        $state,
        $paymentState,
        $registeredByValue
    );
    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        throw new RuntimeException('No se pudo activar la prueba gratuita: ' . $error);
    }

    $subscriptionId = (int) $conn->insert_id;
    $stmt->close();
    return $subscriptionId;
}

function createDocenteWithTrial(mysqli $conn, array $data): int
{
    $passwordHash = password_hash((string) $data['password'], PASSWORD_DEFAULT);
    if ($passwordHash === false) {
        throw new RuntimeException('No se pudo procesar la contraseña.');
    }

    $role = 'docente';
    $active = 1;
    $stmt = $conn->prepare(
        'INSERT INTO docentes (usuario, password, nombre, apellido, rol, correo, telefono, activo)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo preparar la creación de la cuenta docente.');
    }

    $stmt->bind_param(
        'sssssssi',
        $data['email'],
        $passwordHash,
        $data['nombre'],
        $data['apellido'],
        $role,
        $data['email'],
        $data['telefono'],
        $active
    );
    if (!$stmt->execute()) {
        $duplicate = $stmt->errno === 1062;
        $error = $stmt->error;
        $stmt->close();
        if ($duplicate) {
            throw new RuntimeException('El correo electrónico ya está registrado.');
        }
        throw new RuntimeException('No se pudo crear la cuenta docente: ' . $error);
    }

    $docenteId = (int) $conn->insert_id;
    $stmt->close();
    createTrialSubscription($conn, $docenteId);
    return $docenteId;
}

function recordSubscriptionAudit(mysqli $conn, ?int $adminId, string $action, string $entity, int $entityId, array $details = []): void
{
    $adminValue = $adminId !== null && $adminId > 0 ? $adminId : 0;
    $detailsJson = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (!is_string($detailsJson)) {
        $detailsJson = '{}';
    }

    $stmt = $conn->prepare(
        'INSERT INTO subscription_audit_log (admin_id, accion, entidad, entidad_id, detalles)
         VALUES (NULLIF(?, 0), ?, ?, ?, ?)'
    );
    if (!$stmt) {
        throw new RuntimeException('No se pudo registrar la trazabilidad administrativa.');
    }
    $stmt->bind_param('issis', $adminValue, $action, $entity, $entityId, $detailsJson);
    if (!$stmt->execute()) {
        $stmt->close();
        throw new RuntimeException('No se pudo registrar la trazabilidad administrativa.');
    }
    $stmt->close();
}

function subscriptionDateInput(string $value): DateTimeImmutable
{
    $value = trim($value);
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value, new DateTimeZone('America/Bogota'));
    $errors = DateTimeImmutable::getLastErrors();
    if (!$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
        throw new InvalidArgumentException('La fecha de inicio no tiene un formato válido.');
    }
    return $date;
}

