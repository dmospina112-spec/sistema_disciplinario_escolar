-- =====================================================
-- Base de datos principal del proyecto
-- Nombre: app_educativa_recuperada
-- =====================================================

CREATE DATABASE IF NOT EXISTS app_educativa_recuperada
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE app_educativa_recuperada;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS notificaciones_acudiente;
DROP TABLE IF EXISTS acudientes;
DROP TABLE IF EXISTS registros_disciplinarios;
DROP TABLE IF EXISTS estudiantes;
DROP TABLE IF EXISTS password_reset_requests;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS docentes;

CREATE TABLE docentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(254) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL DEFAULT 'Docente',
    rol VARCHAR(20) NOT NULL DEFAULT 'docente',
    correo VARCHAR(254) DEFAULT NULL,
    telefono VARCHAR(30) DEFAULT NULL,
    pregunta_seguridad VARCHAR(80) DEFAULT NULL,
    respuesta_seguridad_hash VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(120) NOT NULL,
    precio DECIMAL(12,2) NOT NULL DEFAULT 0,
    duracion_dias INT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

CREATE TABLE password_reset_tokens (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    docente_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_password_reset_token_hash (token_hash),
    KEY idx_password_reset_docente (docente_id),
    KEY idx_password_reset_expiry (expires_at),
    CONSTRAINT fk_password_reset_docente
        FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE password_reset_requests (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email_hash CHAR(64) NOT NULL,
    ip_hash CHAR(64) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_password_reset_request_email_created (email_hash, created_at),
    KEY idx_password_reset_request_ip_created (ip_hash, created_at)
) ENGINE=InnoDB;

CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    numero_matricula VARCHAR(50) NOT NULL UNIQUE,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_estudiantes_activo (activo)
) ENGINE=InnoDB;

CREATE TABLE acudientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL DEFAULT '',
    parentesco VARCHAR(60) DEFAULT NULL,
    telefono VARCHAR(30) DEFAULT NULL,
    correo VARCHAR(150) DEFAULT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_acudiente_estudiante (estudiante_id),
    CONSTRAINT fk_acudiente_estudiante
        FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE registros_disciplinarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    docente_id INT NULL,
    faltas_tipo1 LONGTEXT NOT NULL,
    faltas_tipo2 LONGTEXT NOT NULL,
    faltas_tipo3 LONGTEXT NOT NULL,
    estimulos LONGTEXT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_registros_estudiante (estudiante_id),
    INDEX idx_registros_docente (docente_id),
    CONSTRAINT fk_registro_estudiante
        FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_registro_docente
        FOREIGN KEY (docente_id) REFERENCES docentes(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE notificaciones_acudiente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NULL,
    estudiante_id INT NOT NULL,
    acudiente_id INT NULL,
    correo_destino VARCHAR(150) DEFAULT NULL,
    asunto VARCHAR(255) NOT NULL,
    mensaje LONGTEXT NOT NULL,
    fecha_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notificacion_registro (registro_id),
    INDEX idx_notificacion_estudiante (estudiante_id),
    INDEX idx_notificacion_acudiente (acudiente_id),
    CONSTRAINT fk_notificacion_registro
        FOREIGN KEY (registro_id) REFERENCES registros_disciplinarios(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_notificacion_estudiante
        FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_notificacion_acudiente
        FOREIGN KEY (acudiente_id) REFERENCES acudientes(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO docentes (usuario, password, nombre, apellido, rol, correo, activo)
SELECT
    'admin',
    '$2y$10$grrjr/tNLvezOzDUuwSBJOCy9zjHjTCHXD.aVYfMvQXK/3bSLpft.',
    'Administrador',
    'Principal',
    'administrador',
    'admin@ieaea.edu.co',
    1
WHERE NOT EXISTS (SELECT 1 FROM docentes LIMIT 1);

INSERT INTO estudiantes (nombre, apellido, numero_matricula, activo)
SELECT seed.nombre, seed.apellido, seed.numero_matricula, seed.activo
FROM (
    SELECT 'MELANIE' AS nombre, 'ARIAS ALVAREZ' AS apellido, '2994' AS numero_matricula, 1 AS activo
    UNION ALL SELECT 'DYLAN ENMANUEL', 'BRICEÑO NUÑEZ', '2853', 1
    UNION ALL SELECT 'VALENTINA', 'CARMONA SANCHEZ', '220563', 1
    UNION ALL SELECT 'CHRISTOPHER JESUS', 'CASTILLO PAREJO', '2212', 1
    UNION ALL SELECT 'JUANITA SOFIA', 'CASTRO ROCHA', '2886', 1
    UNION ALL SELECT 'MATEO', 'COCHERO DE HOYOS', '2881', 1
    UNION ALL SELECT 'ANTHONY', 'DELGADO PINO', '2854', 1
    UNION ALL SELECT 'PAULINA', 'FLOREZ CARDONA', '221044', 1
    UNION ALL SELECT 'KRISTHOFER ALEXANDER', 'GORDONES ZERPA', '457', 1
    UNION ALL SELECT 'DANIEL', 'HENAO GOMEZ', '223068', 1
    UNION ALL SELECT 'MIGUEL ANGEL', 'HERNANDEZ ALVAREZ', '3188', 1
    UNION ALL SELECT 'JUAN CAMILO', 'JARAMILLO DAVID', '2903', 1
    UNION ALL SELECT 'JHOAN ENRIQUE', 'MARTINEZ OSORIO', '3069', 1
    UNION ALL SELECT 'NICOLAS', 'MARULANDA AVENDAÑO', '221547', 1
    UNION ALL SELECT 'MATHIAS', 'MIRANDA VANEGAS', '417', 1
    UNION ALL SELECT 'MARIANGEL', 'MONSALVE REINOSA', '2095', 1
    UNION ALL SELECT 'DANIELA', 'MORALES PEREZ', '221735', 1
    UNION ALL SELECT 'DARWIN', 'PLATA RODRIGUEZ', '1494', 1
    UNION ALL SELECT 'MIGUEL ANGEL', 'QUINTERO MENESES', '222204', 1
    UNION ALL SELECT 'RASHEL JOHANA', 'REYES FERNANDEZ', '1214', 1
    UNION ALL SELECT 'TAHIRA', 'RUEDA CANO', '2896', 1
    UNION ALL SELECT 'NICOLAS', 'SEPULVEDA ARBELAEZ', '222643', 1
    UNION ALL SELECT 'ALEJANDRO', 'SERNA ROJAS', '222533', 1
    UNION ALL SELECT 'ANTHONY', 'VILLA SANPEDRO', '222718', 1
    UNION ALL SELECT 'VALERIN', 'VILLEGAS CARDONA', '1754', 1
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM estudiantes LIMIT 1);
