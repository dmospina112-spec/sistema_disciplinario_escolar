# App Educativa (XAMPP + MySQL)

Proyecto web para registrar observaciones disciplinarias y estimulos por estudiante.

## Estado actual
- CRUD de estudiantes funcional via `api.php`.
- Login conectado a base de datos (`docentes`).
- Registro disciplinario guardado en MySQL (`registros_disciplinarios`).
- Base de datos activa: `app_educativa_recuperada`.
- `database/database.sql` crea la estructura base en `app_educativa_recuperada`.

## Requisitos
- XAMPP (Apache + MySQL)
- phpMyAdmin
- Navegador moderno

## Instalacion rapida en XAMPP
1. Copia esta carpeta en `C:\xampp\htdocs\proyecto-educativo`.
2. Inicia Apache y MySQL desde XAMPP.
3. Abre phpMyAdmin: `http://localhost/phpmyadmin`.
4. Ejecuta el archivo `database/database.sql` completo.
5. Confirma que exista la base `app_educativa_recuperada`.
6. Abre la app: `http://localhost/proyecto-educativo/`.

## Credenciales iniciales
- Usuario: `admin`
- Contrasena: `1234`

## Configuracion de conexion
La conexion vive en `app/backend/config.php`.

Valores por defecto:
- `DB_HOST=localhost`
- `DB_PORT=3306`
- `DB_USER=root`
- `DB_PASS=`
- `DB_NAME=app_educativa_recuperada`

Si no defines variables de entorno, se usan esos valores automaticamente.
Puedes crear un `.env` usando `.env.example`.

## Envio de correos
El boton de envio al acudiente ahora usa SMTP real configurado desde `.env`.

Variables disponibles:
- `MAIL_FROM`
- `MAIL_FROM_NAME`
- `SMTP_HOST`
- `SMTP_PORT`
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_ENCRYPTION`
- `SMTP_TIMEOUT`

Ejemplo con Gmail:
- `MAIL_FROM=tu_correo@gmail.com`
- `MAIL_FROM_NAME=App Educativa`
- `SMTP_HOST=smtp.gmail.com`
- `SMTP_PORT=587`
- `SMTP_USERNAME=tu_correo@gmail.com`
- `SMTP_PASSWORD=tu_app_password`
- `SMTP_ENCRYPTION=tls`

Nota:
- Para Gmail debes usar una contrasena de aplicacion, no tu contrasena normal.

## Estructura principal
- `app/backend/`: API, conexion, configuracion y scripts PHP de servidor.
- `app/views/`: vistas PHP que mezclan HTML con logica de presentacion.
- `frontend/`: assets del cliente (`css`, `js`, `img`, `chatbot`).
- `database/database.sql`: creacion de la base `app_educativa_recuperada`.
- `index.php`, `panel_admin.php`, `panel_docente.php`, `api.php`: archivos puente en la raiz para mantener las URLs actuales.
- `verificar.php`: validacion tecnica de instalacion.
- `test.html`: pruebas rapidas de endpoints.

## Endpoints
- `GET api.php?action=test`
- `POST api.php?action=login`
- `GET api.php?action=obtenerEstudiantes`
- `GET api.php?action=obtenerEstudiante&id=1`
- `POST api.php?action=agregarEstudiante`
- `POST api.php?action=actualizarEstudiante`
- `POST api.php?action=eliminarEstudiante`
- `POST api.php?action=guardarRegistro`

## Verificacion rapida
- `http://localhost/proyecto-educativo/verificar.php`
- `http://localhost/proyecto-educativo/test.html`

## Respaldos
- `respaldar_bd.bat`: crea un respaldo SQL en `storage/backups/`.
- `restaurar_bd.bat`: restaura el ultimo respaldo generado o uno que le pases por ruta.
- Ejemplo de restauracion manual: `restaurar_bd.bat "C:\ruta\al\archivo.sql"`

## Notas
- La eliminacion de estudiantes es logica (`activo = 0`).
- El proyecto queda configurado para usar `app_educativa_recuperada`.
- La base antigua `app_educativa` puede permanecer aparte sin afectar la app.
