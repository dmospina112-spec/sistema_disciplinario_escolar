<?php
declare(strict_types=1);
require_once __DIR__ . '/app/backend/session.php';
require_once __DIR__ . '/app/backend/password_reset.php';
ensureAppSessionStarted();
$csrfToken = getCsrfToken();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Referrer-Policy: no-referrer');
header('X-Content-Type-Options: nosniff');
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><meta name="referrer" content="no-referrer"><title>Restablecer contraseña | COMPORTATE</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><main class="container py-5" style="max-width:540px"><section class="card shadow-sm"><div class="card-body p-4"><h1 class="h3 mb-2">Define una nueva contraseña</h1><p class="text-secondary">El enlace es válido por 30 minutos y solo se puede usar una vez.</p><form id="resetPasswordForm" novalidate><input type="hidden" id="resetToken"><label for="resetPassword" class="form-label">Nueva contraseña</label><input id="resetPassword" class="form-control mb-2" type="password" minlength="10" autocomplete="new-password" required><div class="form-text mb-3">Mínimo 10 caracteres, con mayúscula, minúscula y número.</div><label for="resetConfirmation" class="form-label">Confirma la nueva contraseña</label><input id="resetConfirmation" class="form-control mb-3" type="password" minlength="10" autocomplete="new-password" required><div id="resetMessage" class="alert d-none" role="alert"></div><button id="resetSubmit" class="btn btn-primary w-100" type="submit">Guardar nueva contraseña</button><a href="acceso.php" class="btn btn-link w-100 mt-2">Volver al inicio de sesión</a></form></div></section></main>
<script>
const tokenFromFragment = new URLSearchParams(window.location.hash.slice(1)).get('token') || '';
document.getElementById('resetToken').value = tokenFromFragment;
history.replaceState(null, '', window.location.pathname);
document.getElementById('resetPasswordForm').addEventListener('submit', async (event) => {
  event.preventDefault();
  const password = document.getElementById('resetPassword').value;
  const confirmation = document.getElementById('resetConfirmation').value;
  const box = document.getElementById('resetMessage');
  const button = document.getElementById('resetSubmit');
  box.className = 'alert';
  if (password !== confirmation) { box.classList.add('alert-warning'); box.textContent = 'La confirmación de la contraseña no coincide.'; return; }
  button.disabled = true;
  try {
    const response = await fetch('api.php?action=restablecerContrasena', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ token: document.getElementById('resetToken').value, contrasena_nueva: password, confirmacion: confirmation }) });
    const result = await response.json();
    if (!response.ok || result.success !== true) throw new Error(result.error || 'No se pudo restablecer la contraseña.');
    box.classList.add('alert-success'); box.textContent = result.message;
    document.getElementById('resetPasswordForm').reset();
  } catch (error) { box.classList.add('alert-danger'); box.textContent = error.message; }
  finally { button.disabled = false; }
});
</script></body></html>
