<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/backend/session.php';
ensureAppSessionStarted();
require_once dirname(__DIR__) . '/app/backend/password_reset.php';
$csrfToken = getCsrfToken();

$authUser = $_SESSION['auth_user'] ?? null;
if (is_array($authUser)) {
    $role = strtolower((string) ($authUser['rol'] ?? 'docente'));
    $target = $role === 'administrador' ? 'panel_admin.php' : 'panel_docente.php';
    header('Location: ' . $target);
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
header_remove('ETag');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

$stylesVersion = (string) md5_file(__DIR__ . '/styles/styles.css');
$scriptJsVersion = (string) md5_file(__DIR__ . '/js/script.js');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>App Educativa Docente</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/styles.css?v=<?php echo htmlspecialchars($stylesVersion, ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body class="site-body login-page">
  <main class="login-shell container-xxl">
    <section class="login-hero">
      <div class="hero-brand">
        <img src="img/Logo.png" alt="Escudo institucional" class="hero-logo">
        <div class="hero-brand-copy">
          <span class="hero-kicker">Plataforma docente</span>
          <span class="hero-motto">Ciencia, amor y virtud</span>
        </div>
      </div>

      <div class="hero-copy">
        <span class="hero-badge">Acceso institucional seguro</span>
        <h1 class="hero-title">Bienvenido Docente</h1>
        <p class="hero-subtitle">
          Gestiona observaciones, reportes y seguimiento estudiantil desde un entorno más claro,
          ordenado y alineado con la identidad de la institución.
        </p>
      </div>
    </section>

    <section id="loginSection" class="login-section">
      <section class="card login-card auth-card">
        <div class="auth-card-top">
          <span class="auth-chip">Ingreso al sistema</span>
          <h2 class="auth-title">Accede con tu cuenta</h2>
          <p class="auth-copy">Usa tu usuario institucional para continuar con el panel docente.</p>
        </div>

        <form id="loginForm" class="auth-form" novalidate>
          <div class="mb-3">
            <label for="usuario" class="form-label">Correo electrónico del docente o usuario administrador</label>
            <input type="text" class="form-control" id="usuario" autocomplete="username" placeholder="Ingresa tu correo o usuario" required>
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" autocomplete="current-password" placeholder="Ingresa tu contraseña" required>
          </div>

          <div id="mensajeError" class="auth-feedback auth-feedback-error d-none">
            Usuario o contraseña incorrectos.
          </div>

          <div class="auth-actions">
            <button type="button" class="btn btn-link auth-help" id="recordarBtn">¿Olvidaste tu contraseña?</button>
          </div>
          <button type="submit" class="btn btn-primary w-100 auth-submit">Ingresar</button>
          <p class="auth-copy text-center mt-2">¿Necesitas una cuenta? <a href="../#contacto">Contacta a la administradora.</a></p>
        </form>


        <form id="recoverForm" class="auth-form d-none" novalidate>
          <div class="auth-subheader">
            <span class="auth-chip auth-chip-muted">Recuperación segura</span>
            <h3>Restablece tu contraseña</h3>
            <p>Ingresa el correo asociado a tu cuenta. Te enviaremos un enlace temporal válido por 30 minutos.</p>
          </div>
          <div class="mb-3">
            <label for="recuperarCorreo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="recuperarCorreo" placeholder="correo@institucion.edu.co" autocomplete="email" required>
          </div>
          <button type="submit" class="btn btn-primary w-100 auth-submit" id="solicitarResetBtn">Enviar enlace</button>
          <button type="button" class="btn btn-link auth-switch auth-switch-back" id="switchRecoverToLogin">Volver al inicio</button>
          <div id="mensajeRecuperacion" class="alert d-none mt-3 auth-feedback" role="alert"></div>
        </form>
      </section>
    </section>
  </main>

  <footer class="site-footer">
    <div class="site-footer-shell">
      <div class="site-footer-brand">
        <img src="img/Logo.png" alt="Logo institucional" class="site-footer-logo">
        <div>
          <strong>Institución Educativa Gilberto Alzate Avendaño</strong>
          <p>Plataforma de seguimiento disciplinario.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js?v=<?php echo htmlspecialchars($scriptJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>

</body>
</html>
