-- Migración aditiva para recuperación segura de contraseñas.
-- Ejecutar manualmente en app_educativa_recuperada tras revisar la estructura activa.
-- No elimina tablas ni modifica cuentas existentes.

USE app_educativa_recuperada;

SET @schema_name = DATABASE();
SET @column_exists = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'docentes' AND COLUMN_NAME = 'telefono'
);
SET @ddl = IF(@column_exists = 0,
  'ALTER TABLE docentes ADD COLUMN telefono VARCHAR(30) NULL AFTER correo',
  'SELECT 1'
);
PREPARE add_phone FROM @ddl;
EXECUTE add_phone;
DEALLOCATE PREPARE add_phone;

-- El correo también es el usuario de inicio; conserva datos y admite direcciones válidas largas.
ALTER TABLE docentes MODIFY COLUMN usuario VARCHAR(254) NOT NULL;
ALTER TABLE docentes MODIFY COLUMN correo VARCHAR(254) DEFAULT NULL;

-- La inspección de la base activa encontró cero correos duplicados (comparación sin distinguir mayúsculas).
SET @index_exists = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'docentes' AND INDEX_NAME = 'uq_docentes_correo'
);
SET @ddl = IF(@index_exists = 0,
  'ALTER TABLE docentes ADD UNIQUE KEY uq_docentes_correo (correo)',
  'SELECT 1'
);
PREPARE add_email_unique FROM @ddl;
EXECUTE add_email_unique;
DEALLOCATE PREPARE add_email_unique;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  docente_id INT NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_password_reset_token_hash (token_hash),
  KEY idx_password_reset_docente (docente_id),
  KEY idx_password_reset_expiry (expires_at),
  CONSTRAINT fk_password_reset_docente FOREIGN KEY (docente_id)
    REFERENCES docentes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_reset_requests (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email_hash CHAR(64) NOT NULL,
  ip_hash CHAR(64) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_password_reset_request_email_created (email_hash, created_at),
  KEY idx_password_reset_request_ip_created (ip_hash, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
