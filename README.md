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
El botón de envío al acudiente y la confirmación automática de registro de docentes usan SMTP real configurado desde `.env`.

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

## Landing comercial COMPORTATE
- URL local: `http://localhost/Sistema_disciplinario/landing.php` (ajusta el segmento de carpeta si AMPPS publica el proyecto con otro nombre).
- La landing se muestra al abrir el proyecto (`index.php`, `/` o `landing.php`). El inicio de sesion existente sigue disponible en `acceso.php`; la copia bajo `chatbot/` tambien lleva a la landing y conserva su ingreso en `chatbot/acceso.php`.
- Los precios y la vigencia de la promocion se presentan en COP. La promocion del primer mes aparece seleccionable solo en enero y febrero, segun la fecha de `America/Bogota`.
- El formulario envia una solicitud al correo fijo `comportateweb@gmail.com`; la direccion diligenciada por el docente se usa como `Reply-To` y recibe una confirmacion despues de que el servidor SMTP acepta la solicitud.
- Para el envio, completa en `.env` `MAIL_FROM`, `MAIL_FROM_NAME`, `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD` y `SMTP_ENCRYPTION`. Usa TLS o SSL y credenciales autorizadas por el proveedor. En Gmail, crea una contrasena de aplicacion. No copies credenciales a archivos PHP ni al repositorio.
- Si falta la configuracion o el servidor rechaza el correo, el formulario muestra un error y conserva lo diligenciado. No persiste los datos del formulario en MySQL.
- La ruta `contacto.php` valida los datos en PHP, exige token CSRF y autorizacion de privacidad, descarta el honeypot y limita reenvios de la misma sesion.

## Control de cuentas docentes
- El inicio de sesion permanece en `acceso.php`; no presenta registro publico. Los docentes nuevos deben solicitar una cuenta por el formulario de contacto.
- La creacion, edicion y baja de cuentas se realiza desde el panel administrativo. Las acciones de usuario requieren una sesion con rol `administrador`; la accion publica antigua `crearDocente` se rechaza con HTTP 403.
- El endpoint administrativo solo crea cuentas con rol `docente`. En edicion, el servidor conserva el rol que ya tiene la cuenta, aunque se manipule el formulario o la solicitud. El selector de rol no es editable desde el panel.
- La copia de la aplicacion bajo `chatbot/` aplica los mismos controles y conserva el acceso en `chatbot/acceso.php`.

## Estructura principal
- `app/backend/`: API, conexion, configuracion y scripts PHP de servidor.
- `app/views/`: vistas PHP que mezclan HTML con logica de presentacion.
- `frontend/`: assets del cliente (`css`, `js`, `img`, `chatbot`).
- `database/database.sql`: creacion de la base `app_educativa_recuperada`.
- `index.php`: landing comercial; `acceso.php`: entrada al inicio de sesion; `panel_admin.php`, `panel_docente.php`, `api.php`: archivos puente para el sistema.
- `verificar.php`: validacion tecnica de instalacion.
- `test.html`: pruebas rapidas de endpoints.

## Endpoints
- `GET api.php?action=test`
- `POST api.php?action=login`
- `GET api.php?action=obtenerEstudiantes`
- `GET api.php?action=obtenerEstudiante&id=1`
- `POST api.php?action=agregarEstudiante`
- `POST api.php?action=actualizarEstudiante`
- `POST api.php?action=archivarEstudiante`
- `POST api.php?action=restaurarEstudiante`
- `POST api.php?action=archivarRegistrosHistorial`
- `POST api.php?action=guardarRegistro`

## Verificacion rapida
- `http://localhost/proyecto-educativo/verificar.php`
- `http://localhost/proyecto-educativo/test.html`

## Respaldos
- `respaldar_bd.bat`: crea un respaldo SQL en `storage/backups/`.
- `restaurar_bd.bat`: restaura el ultimo respaldo generado o uno que le pases por ruta.
- `reparar_mysql_xampp.bat`: repara el arranque de MariaDB/XAMPP cuando aparece `MySQL shutdown unexpectedly`.
- Ejemplo de restauracion manual: `restaurar_bd.bat "C:\ruta\al\archivo.sql"`

## Recuperacion de MySQL en XAMPP
Si XAMPP muestra `MySQL shutdown unexpectedly` y la app no deja iniciar sesion, normalmente quedo corrupta una tabla interna del esquema `mysql` por un cierre brusco.

Pasos recomendados:
- Cierra MySQL en XAMPP.
- Ejecuta `reparar_mysql_xampp.bat` desde la raiz del proyecto.
- Espera a que el script cree un respaldo en `storage/backups/`, restaure `mysql\db.*`, repare `mysql.columns_priv` y valide la base `app_educativa_recuperada`.
- Si el panel de XAMPP no refresca el estado, cierralo y abrelo otra vez.

Ruta alternativa:
- `powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts\repair_xampp_mysql.ps1`

## Notas
- Los estudiantes, las cuentas de usuario y los registros disciplinarios se archivan (`activo = 0`); su información no se borra. Los estudiantes y las cuentas pueden restaurarse desde sus respectivos listados.
- El proyecto queda configurado para usar `app_educativa_recuperada`.
- La base antigua `app_educativa` puede permanecer aparte sin afectar la app.

## Contraseñas de docentes y recuperación por correo
- En el módulo administrativo, el correo del docente es también su usuario de inicio de sesión. Nombre, apellido, teléfono, correo y contraseña inicial son requeridos; la contraseña exige 10 caracteres, mayúscula, minúscula y número y se guarda con `password_hash()`.
- El docente puede usar **Cambiar contraseña** desde su panel. El servidor exige sesión, token CSRF, contraseña actual correcta, confirmación y una nueva contraseña segura; solo modifica la cuenta autenticada.
- **¿Olvidaste tu contraseña?** solicita solo el correo. El servidor siempre devuelve un mensaje genérico; limita solicitudes, genera un token aleatorio de un uso, guarda únicamente su hash y envía un enlace con vencimiento de 30 minutos. No se envían contraseñas por correo.
- La inspección de la base activa `app_educativa_recuperada` encontró `docentes` sin `telefono` y cero correos duplicados. El script `database/migrations/20261008_password_reset.sql` agrega el teléfono, amplía el campo de usuario, agrega unicidad de correo y crea tablas auxiliares. **No se ejecutó**: haz un respaldo y aplícalo manualmente desde phpMyAdmin/MySQL para habilitar estos flujos.
- Copia `.env.example` a `.env` si aún no existe y configura en `.env.local` `APP_BASE_URL` y un `APP_SECRET` privado de al menos 32 caracteres; conserva la configuración SMTP en `.env`. En AMPPS, selecciona `app_educativa_recuperada` en phpMyAdmin, importa el script SQL y configura SMTP con credenciales autorizadas. Para Gmail usa una contraseña de aplicación. Mantén `.env` fuera del repositorio.
- El proyecto reutiliza su transporte SMTP existente. La entrega real depende de la cuenta y el servidor SMTP; no se ha afirmado que el correo llegue ni se ha probado con una cuenta real.
