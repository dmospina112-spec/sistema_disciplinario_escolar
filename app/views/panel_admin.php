<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'] ?? null;
if (!is_array($authUser) || (($authUser['rol'] ?? '') !== 'administrador')) {
    header('Location: index.php');
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
header_remove('ETag');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

$assetVersion = static function (string $path, string $suffix = ''): string {
    if (!is_file($path)) {
        return '0' . $suffix;
    }

    $hash = md5_file($path);
    if ($hash === false) {
        return (string) filemtime($path) . $suffix;
    }

    return substr($hash, 0, 12) . $suffix;
};

$projectRoot = dirname(__DIR__, 2);
$chatbotCssVersion = $assetVersion($projectRoot . '/frontend/chatbot/chatbot.css');
$chatbotJsVersion = $assetVersion($projectRoot . '/frontend/chatbot/chatbot.js', '-20260416');
$estudiantesJsVersion = $assetVersion($projectRoot . '/frontend/js/estudiantes.js');
$stylesVersion = $assetVersion($projectRoot . '/frontend/css/styles.css', '-logout-red-20260514');
$scriptJsVersion = $assetVersion($projectRoot . '/frontend/js/script.js');
$adminUsuariosJsVersion = $assetVersion($projectRoot . '/frontend/js/admin-usuarios.js');
$heroLogoVersion = $assetVersion($projectRoot . '/frontend/img/Logo-hero-contrast-v3.png');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Panel Administrativo</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/css/styles.css?v=<?php echo htmlspecialchars($stylesVersion, ENT_QUOTES, 'UTF-8'); ?>">
  <link rel="stylesheet" href="frontend/chatbot/chatbot.css?v=<?php echo htmlspecialchars($chatbotCssVersion, ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body class="site-body admin-panel-page">
  <script>
    window.__panelUser = <?php echo json_encode($authUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
  </script>

  <main class="admin-dashboard-shell">
    <section class="container admin-hero-wrap">
      <div class="admin-hero-card">
        <div class="admin-hero-grid">
          <div class="admin-hero-content">
            <div class="admin-hero-logo-stage">
              <img src="frontend/img/Logo-hero-contrast-v3.png?v=<?php echo htmlspecialchars($heroLogoVersion, ENT_QUOTES, 'UTF-8'); ?>" alt="Logo institucional grande" class="admin-hero-logo-large">
            </div>

            <p class="admin-hero-institution">Institución Educativa Gilberto Alzate Avendaño</p>

            <div class="admin-hero-actions">
              <button
                type="button"
                class="btn btn-primary admin-hero-btn"
                id="btnAbrirUsuariosAdmin"
                data-bs-toggle="modal"
                data-bs-target="#adminUsersModal"
              >
                Administrar usuarios
              </button>
            </div>
          </div>

          <div class="admin-hero-side">
            <div class="admin-session-card">
              <span class="admin-session-label">Sesión activa</span>
              <p id="userGreeting" class="admin-session-value mb-0"></p>
            </div>

            <div class="admin-stats-grid">
              <article class="admin-stat-card">
                <span class="admin-stat-label">Usuarios</span>
                <strong id="adminMetricUsuarios">--</strong>
                <p class="mb-0">Cuentas registradas</p>
              </article>

              <article class="admin-stat-card">
                <span class="admin-stat-label">Activos</span>
                <strong id="adminMetricActivos">--</strong>
                <p class="mb-0">Usuarios habilitados</p>
              </article>

              <article class="admin-stat-card">
                <span class="admin-stat-label">Estudiantes</span>
                <strong id="adminMetricEstudiantes">--</strong>
                <p class="mb-0">Registros disponibles</p>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade admin-users-modal" id="adminUsersModal" tabindex="-1" aria-labelledby="adminUsersModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable admin-users-modal-dialog">
        <div class="modal-content admin-users-modal-content">
          <div class="modal-header admin-users-modal-header">
            <div>
              <span class="admin-section-kicker">Administración de accesos</span>
              <h2 class="modal-title" id="adminUsersModalLabel">Gestionar usuarios</h2>
              <p class="mb-0 text-muted">Consulta, crea, edita y desactiva cuentas del sistema desde una ventana dedicada.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body admin-users-modal-body">
            <section id="seccionUsuariosAdmin">
              <div id="usuariosAdminMensaje" class="alert d-none mb-4" role="alert"></div>

              <div class="row g-4 admin-users-layout">
                <div class="col-xl-5">
                  <div class="admin-subpanel admin-form-panel">
                    <div class="admin-subpanel-head">
                      <span class="admin-subpanel-kicker">Formulario de acceso</span>
                      <h3 id="adminUsuarioFormTitle">Crear o editar usuario</h3>
                      <p id="adminUsuarioFormCopy">Define rol, estado y credenciales de cada cuenta según la operación administrativa.</p>
                    </div>

                    <form id="formUsuarioAdmin" autocomplete="off" novalidate>
                      <input type="hidden" id="adminUsuarioId">

                      <div class="mb-3">
                        <label for="adminNombre" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="adminNombre" placeholder="Nombre(s)" required>
                      </div>

                      <div class="mb-3">
                        <label for="adminApellido" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="adminApellido" placeholder="Apellidos" required>
                      </div>

                      <div class="mb-3">
                        <label for="adminUsuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="adminUsuario" placeholder="Ej. coordinacion1" minlength="4" maxlength="30" required>
                      </div>

                      <div class="mb-3">
                        <label for="adminCorreo" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="adminCorreo" placeholder="correo@institucion.edu.co" required>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label for="adminRol" class="form-label">Rol</label>
                          <select class="form-select" id="adminRol" required>
                            <option value="docente">Docente</option>
                            <option value="administrador">Administrador</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label for="adminEstado" class="form-label">Estado</label>
                          <select class="form-select" id="adminEstado" required>
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                          </select>
                        </div>
                      </div>

                      <div class="mt-3 mb-3">
                        <label for="adminPreguntaSeguridad" class="form-label">Pregunta de seguridad</label>
                        <select class="form-select" id="adminPreguntaSeguridad">
                          <option value="">Selecciona una pregunta</option>
                          <option value="mascota">¿Cuál es el nombre de tu primera mascota?</option>
                          <option value="escuela">¿Cómo se llamaba tu escuela primaria?</option>
                          <option value="madre">¿Cuál es el segundo nombre de tu madre?</option>
                          <option value="ciudad">¿En qué ciudad naciste?</option>
                          <option value="profesor">¿Cuál fue tu profesor favorito?</option>
                          <option value="amigo">¿Cómo se llamaba tu mejor amigo de la infancia?</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label for="adminRespuestaSeguridad" class="form-label">Respuesta de seguridad</label>
                        <input type="text" class="form-control" id="adminRespuestaSeguridad" placeholder="Escribe la respuesta" autocomplete="off">
                        <div class="form-text" id="adminSecurityHelp">Obligatorias al crear. Se usarán para recuperar la contraseña si el usuario la olvida.</div>
                      </div>

                      <div class="mt-3 mb-3">
                        <label for="adminContrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="adminContrasena" placeholder="Mínimo 8 caracteres" minlength="8">
                        <div class="form-text" id="adminPasswordHelp">Obligatoria al crear. Si editas y la dejas vacía, se conserva la actual.</div>
                      </div>

                      <div class="mb-4">
                        <label for="adminContrasenaConfirmacion" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="adminContrasenaConfirmacion" placeholder="Repite la contraseña" minlength="8">
                      </div>

                      <div class="admin-form-actions-wrap">
                        <p class="admin-form-mode-note d-none" id="adminUsuarioFormModeNote"></p>
                        <div class="admin-form-actions">
                          <button type="submit" class="btn btn-success" id="btnGuardarUsuarioAdmin">Crear usuario</button>
                          <button type="submit" class="btn btn-primary d-none" id="btnGuardarCambiosUsuarioAdmin">Guardar cambios</button>
                          <button type="button" class="btn btn-secondary d-none" id="btnCancelarUsuarioAdmin">Cancelar edición</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="col-xl-7">
                  <div class="admin-subpanel admin-directory-panel">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                      <div>
                        <span class="admin-subpanel-kicker">Directorio del sistema</span>
                        <h3 class="mb-1">Usuarios registrados</h3>
                        <p class="text-muted mb-0" id="adminUsuariosResumen">Cargando usuarios...</p>
                      </div>

                      <div class="admin-search-wrap">
                        <label for="buscarUsuarioAdmin" class="form-label visually-hidden">Buscar usuario</label>
                        <input type="search" class="form-control" id="buscarUsuarioAdmin" placeholder="Buscar por nombre, usuario, correo o rol">
                      </div>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                      <button type="button" class="btn btn-outline-primary btn-sm" id="btnRecargarUsuariosAdmin">Actualizar lista</button>
                    </div>

                    <div id="listaUsuariosAdmin" class="admin-users-list">
                      <p class="text-muted text-center mb-0 py-4">Cargando usuarios...</p>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/partials/panel-content.php'; ?>
  </main>

  <footer class="site-footer">
    <div class="site-footer-shell">
      <div class="site-footer-brand">
        <img src="frontend/img/Logo.png" alt="Logo institucional" class="site-footer-logo">
        <div>
          <strong>Institución Educativa Gilberto Alzate Avendaño</strong>
          <p>Panel administrativo para control de usuarios, accesos y operación institucional.</p>
        </div>
      </div>
    </div>
  </footer>

  <script>
    (function () {
      const badge = document.getElementById('userGreeting');
      if (!badge || !window.__panelUser) {
        return;
      }
      const name = [window.__panelUser.nombre, window.__panelUser.apellido].filter(Boolean).join(' ');
      badge.textContent = name
        ? `${name} (${window.__panelUser.rol})`
        : `${window.__panelUser.rol}`;
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="frontend/js/script.js?v=<?php echo htmlspecialchars($scriptJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script src="frontend/js/estudiantes.js?v=<?php echo htmlspecialchars($estudiantesJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script src="frontend/js/admin-usuarios.js?v=<?php echo htmlspecialchars($adminUsuariosJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>

  <div id="chatbot-bubble" aria-hidden="false" title="Abrir asistente virtual">💬</div>
  <div id="chatbot-window" role="dialog" aria-label="Asistente Virtual">
    <div id="chatbot-header">
      Asistente Virtual
      <button class="close-btn" id="chatbot-close" aria-label="Cerrar chat" title="Cerrar (Esc)">✖</button>
    </div>
    <div id="chatbot-messages" aria-live="polite" aria-label="Historial de conversación"></div>
    <div id="chatbot-input-area">
      <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." aria-label="Campo de entrada de mensajes">
      <button id="chatbot-send" title="Enviar mensaje (Enter)">Enviar</button>
    </div>
  </div>
  <script src="frontend/chatbot/chatbot.js?v=<?php echo htmlspecialchars($chatbotJsVersion, ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
