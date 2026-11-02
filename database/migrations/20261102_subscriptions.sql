-- Migración aditiva para prueba gratuita, pagos y suscripciones.
-- No elimina ni modifica registros existentes. Ejecutar en
-- app_educativa_recuperada si la inicialización automática está deshabilitada.

USE app_educativa_recuperada;

CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(120) NOT NULL,
    precio DECIMAL(12,2) NOT NULL DEFAULT 0,
    duracion_dias INT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO subscription_plans (codigo, nombre, precio, duracion_dias, activo) VALUES
    ('prueba_gratuita', 'Prueba gratuita de 14 días', 0, 14, 1),
    ('mensual', 'Suscripción mensual', 15000, 30, 1),
    ('promocion', 'Promoción de temporada escolar', 12000, 30, 1),
    ('anual', 'Suscripción anual', 150000, 365, 1);

CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    docente_id INT NOT NULL,
    plan_id INT NOT NULL,
    valor_recibido DECIMAL(12,2) NOT NULL DEFAULT 0,
    fecha_pago DATETIME NOT NULL,
    metodo_pago VARCHAR(80) NOT NULL,
    referencia VARCHAR(120) DEFAULT NULL,
    observaciones TEXT DEFAULT NULL,
    estado_pago VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE',
    subscription_id BIGINT UNSIGNED DEFAULT NULL,
    registrado_por INT NOT NULL,
    verificado_por INT DEFAULT NULL,
    verificado_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_payment_reference (referencia),
    KEY idx_payments_docente (docente_id),
    KEY idx_payments_estado (estado_pago),
    CONSTRAINT fk_payment_docente FOREIGN KEY (docente_id) REFERENCES docentes(id),
    CONSTRAINT fk_payment_plan FOREIGN KEY (plan_id) REFERENCES subscription_plans(id),
    CONSTRAINT fk_payment_registered_by FOREIGN KEY (registrado_por) REFERENCES docentes(id),
    CONSTRAINT fk_payment_verified_by FOREIGN KEY (verificado_por) REFERENCES docentes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @schema_name = DATABASE();
SET @payment_subscription_column_exists = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @schema_name
      AND TABLE_NAME = 'payments'
      AND COLUMN_NAME = 'subscription_id'
);
SET @ddl = IF(@payment_subscription_column_exists = 0,
    'ALTER TABLE payments ADD COLUMN subscription_id BIGINT UNSIGNED NULL AFTER estado_pago',
    'SELECT 1'
);
PREPARE add_payment_subscription_id FROM @ddl;
EXECUTE add_payment_subscription_id;
DEALLOCATE PREPARE add_payment_subscription_id;

CREATE TABLE IF NOT EXISTS docente_suscripciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    docente_id INT NOT NULL,
    plan_id INT NOT NULL,
    tipo_plan VARCHAR(50) NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_vencimiento DATETIME NOT NULL,
    duracion_dias INT NOT NULL,
    estado VARCHAR(30) NOT NULL,
    estado_pago VARCHAR(20) NOT NULL,
    pago_id BIGINT UNSIGNED DEFAULT NULL,
    registrado_por INT DEFAULT NULL,
    activado_por INT DEFAULT NULL,
    activado_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_subscriptions_docente (docente_id),
    KEY idx_subscriptions_state (estado),
    KEY idx_subscriptions_expiry (fecha_vencimiento),
    UNIQUE KEY uq_subscription_payment (pago_id),
    CONSTRAINT fk_subscription_docente FOREIGN KEY (docente_id) REFERENCES docentes(id),
    CONSTRAINT fk_subscription_plan FOREIGN KEY (plan_id) REFERENCES subscription_plans(id),
    CONSTRAINT fk_subscription_payment FOREIGN KEY (pago_id) REFERENCES payments(id),
    CONSTRAINT fk_subscription_registered_by FOREIGN KEY (registrado_por) REFERENCES docentes(id),
    CONSTRAINT fk_subscription_activated_by FOREIGN KEY (activado_por) REFERENCES docentes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS subscription_audit_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT DEFAULT NULL,
    accion VARCHAR(80) NOT NULL,
    entidad VARCHAR(50) NOT NULL,
    entidad_id BIGINT UNSIGNED NOT NULL,
    detalles JSON DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_subscription_audit_entity (entidad, entidad_id),
    KEY idx_subscription_audit_admin (admin_id),
    CONSTRAINT fk_subscription_audit_admin FOREIGN KEY (admin_id) REFERENCES docentes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

