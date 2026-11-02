<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/backend/session.php';
require_once dirname(__DIR__) . '/backend/password_reset.php';
require_once dirname(__DIR__) . '/backend/db.php';
require_once dirname(__DIR__) . '/backend/bootstrap_db.php';
require_once dirname(__DIR__) . '/backend/subscriptions.php';

try {
    ensureAppSessionStarted();
} catch (Throwable $_) {
    header('Location: acceso.php');
    exit;
}

$authUser = $_SESSION['auth_user'] ?? null;
if (!is_array($authUser) || (($authUser['rol'] ?? '') !== 'docente')) {
    header('Location: acceso.php');
    exit;
}

try {
    ensureDatabaseReady();
    $subscriptionConnection = conectarDB();
    $subscriptionAccess = subscriptionAccess($subscriptionConnection, (int) ($authUser['id'] ?? 0), 'docente');
} catch (Throwable $_) {
    header('Location: acceso.php');
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
header_remove('ETag');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

$projectRoot = dirname(__DIR__, 2);
$csrfToken = getCsrfToken();
$chatbotCssVersion = (string) md5_file($projectRoot . '/frontend/chatbot/chatbot.css');
$chatbotJsVersion = (string) md5_file($projectRoot . '/frontend/chatbot/chatbot.js');
$estudiantesJsVersion = (string) md5_file($projectRoot . '/frontend/js/estudiantes.js');
$stylesVersion = (string) md5_file($projectRoot . '/frontend/css/styles.css');
$scriptJsVersion = (string) md5_file($projectRoot . '/frontend/js/script.js');

if (!$subscriptionAccess['allowed']) {
    $expiredStatus = htmlspecialchars((string) ($subscriptionAccess['status'] ?? 'PRUEBA FINALIZADA'), ENT_QUOTES, 'UTF-8');
    $expiredDate = htmlspecialchars((string) ($subscriptionAccess['subscription']['fecha_vencimiento'] ?? ''), ENT_QUOTES, 'UTF-8');
    $contactEmail = htmlspecialchars((string) (getenv('ADMIN_CONTACT_EMAIL') ?: 'comportateweb@gmail.com'), ENT_QUOTES, 'UTF-8');
    $contactPhone = htmlspecialchars((string) (getenv('ADMIN_CONTACT_PHONE') ?: '1212121212'), ENT_QUOTES, 'UTF-8');
    ?>
    <!doctype html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Suscripción COMPORTATE</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="frontend/css/styles.css">
      <style>
        body.subscription-locked{min-height:100vh;display:grid;place-items:center;margin:0;background:linear-gradient(135deg,#102b46,#2d756b);font-family:Manrope,Arial,sans-serif}
        .subscription-locked .modal{display:block;background:rgba(7,25,39,.62)}
        .subscription-locked .modal-dialog{max-width:610px;margin:1.5rem auto}
        .subscription-locked .modal-content{border:0;border-radius:18px;box-shadow:0 25px 80px rgba(4,20,34,.35)}
        .subscription-locked .modal-header{border:0;padding:2rem 2rem 1rem}
        .subscription-locked .modal-body{padding:0 2rem 2rem;color:#53687c;line-height:1.7}
        .subscription-locked h1{color:#102b46;font-size:clamp(1.45rem,4vw,2rem);font-weight:800}
        .subscription-locked .subscription-meta{padding:.85rem 1rem;margin:1rem 0;border-radius:10px;background:#eef7f4;color:#427d70;font-size:.9rem}
        .subscription-locked .btn-contact{display:inline-flex;align-items:center;justify-content:center;width:100%;padding:.85rem 1rem;border-radius:9px;background:#408b7f;color:#fff;text-decoration:none;font-weight:700}
        .subscription-locked .btn-logout{display:block;width:100%;margin-top:.8rem;padding:.75rem;border:1px solid #d9e1e5;border-radius:9px;background:#fff;color:#53687c;font-weight:700}
      </style>
    </head>
    <body class="subscription-locked">
      <div class="modal fade show" role="dialog" aria-modal="true" aria-labelledby="subscriptionExpiredTitle">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header"><h1 class="modal-title" id="subscriptionExpiredTitle">¡TU PERIODO DE PRUEBA GRATUITA HA FINALIZADO!</h1></div>
            <div class="modal-body">
              <p>Han transcurrido los 14 días de prueba gratuita de COMPORTATE.</p>
              <p>Si deseas continuar disfrutando de nuestro servicio y acceder a todas sus funcionalidades, puedes comunicarte con la administración para conocer los planes disponibles y realizar tu contratación.</p>
              <div class="subscription-meta">Estado actual: <strong><?= $expiredStatus ?></strong><?php if ($expiredDate !== ''): ?> · Vencimiento: <?= $expiredDate ?><?php endif; ?></div>
              <?php if ($contactEmail !== ''): ?><a class="btn-contact" href="mailto:<?= $contactEmail ?>?subject=Activar%20suscripción%20COMPORTATE">CONTACTAR A LA ADMINISTRACIÓN</a><?php else: ?><p class="alert alert-warning">El contacto administrativo aún no está configurado.</p><?php endif; ?>
              <?php if ($contactEmail !== ''): ?><p class="small mt-3 mb-0">Correo: <?= $contactEmail ?><?php if ($contactPhone !== ''): ?> · Teléfono: <?= $contactPhone ?><?php endif; ?></p><?php endif; ?>
              <form method="post" action="api.php?action=logout" class="mt-3" id="subscriptionLogoutForm"><button type="submit" class="btn-logout">Cerrar sesión</button></form>
            </div>
          </div>
        </div>
      </div>
      <script>document.getElementById('subscriptionLogoutForm')?.addEventListener('submit', async function (event) { event.preventDefault(); try { await fetch('api.php?action=logout', {method:'POST', credentials:'same-origin'}); } finally { window.location.href='acceso.php'; } });</script>
    </body>
    </html>
    <?php
    exit;
}
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
  <title>Panel Docente</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/css/styles.css?v=<?php echo htmlspecialchars($stylesVersion, ENT_QUOTES, 'UTF-8'); ?>">
  <link rel="stylesheet" href="frontend/chatbot/chatbot.css?v=<?php echo htmlspecialchars($chatbotCssVersion, ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body class="site-body">
  <script>
    window.__panelUser = <?php echo json_encode($authUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
  </script>

  <div class="container py-5">
    <header class="page-header text-center mb-5">
      <img src="frontend/img/Logo-comportate-transparent.png" alt="Logo Comportate" class="brand-logo img-fluid mb-3">
      <h1 class="text-primary">Panel Docente</h1>
      <h2 class="h4 text-primary"></h2>
      <p id="userGreeting" class="text-muted small mt-2"></p>
    </header>
  </div>

  <?php include __DIR__ . '/partials/panel-content.php'; ?>

  <footer class="site-footer">
    <div class="site-footer-shell">
      <div class="site-footer-brand">
        <img src="frontend/img/Logo-comportate-white-clean.png" alt="Logo Comportate" class="site-footer-logo">
        <div>
          <strong>Tú comportaminto construye un entorno positivo</strong>
          <p>Panel docente para estudiantes, reportes, acudientes e historial disciplinario.</p>
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
        ? `Sesión activa: ${name} (${window.__panelUser.rol})`
        : `Sesión activa: ${window.__panelUser.rol}`;
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="frontend/js/script.js?v=<?php echo htmlspecialchars($scriptJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script src="frontend/js/estudiantes.js?v=<?php echo htmlspecialchars($estudiantesJsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>

  <div id="chatbot-bubble" aria-hidden="false" title="Abrir asistente virtual">💬</div>
  <div id="chatbot-window" role="dialog" aria-label="Asistente Virtual">
    <div id="chatbot-header">
      Asistente Virtual
      <button class="close-btn" id="chatbot-close" aria-label="Cerrar chat" title="Cerrar (Esc)">✖</button>
    </div>
    <div id="chatbot-messages" aria-live="polite" aria-label="Historial de conversación"></div>
  </div>
  <script src="frontend/chatbot/chatbot.js?v=<?php echo htmlspecialchars($chatbotJsVersion, ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
