const loginForm = document.getElementById('loginForm');
const recoverForm = document.getElementById('recoverForm');
const switchRecoverToLoginBtn = document.getElementById('switchRecoverToLogin');
const recordarBtn = document.getElementById('recordarBtn');
const mensajeError = document.getElementById('mensajeError');
const mensajeRecuperacion = document.getElementById('mensajeRecuperacion');
const loginSection = document.getElementById('loginSection');
const isLoginPage = Boolean(loginSection);
const API_ENDPOINT = 'api.php';
const AUTH_STORAGE_KEY = 'auth_user';
const DOCENTE_STORAGE_KEY = 'docente';
const SESSION_FLAG = 'authed';
const PANEL_PATHS = { administrador: 'panel_admin.php', docente: 'panel_docente.php' };

function toggleLoginMode(mode) {
  loginForm?.classList.toggle('d-none', mode !== 'login');
  recoverForm?.classList.toggle('d-none', mode !== 'recover');
  mensajeError?.classList.add('d-none');
  if (mensajeRecuperacion) {
    mensajeRecuperacion.textContent = '';
    mensajeRecuperacion.classList.add('d-none');
    mensajeRecuperacion.classList.remove('alert-success', 'alert-warning', 'alert-danger', 'alert-info');
  }
}

function showLoginError(message) {
  if (!mensajeError) return;
  mensajeError.textContent = message || 'Usuario o contraseña incorrectos.';
  mensajeError.classList.remove('d-none');
}

function showRecoveryMessage(text, variant = 'info') {
  if (!mensajeRecuperacion) return;
  mensajeRecuperacion.textContent = text;
  mensajeRecuperacion.classList.remove('d-none', 'alert-success', 'alert-warning', 'alert-danger', 'alert-info');
  mensajeRecuperacion.classList.add(`alert-${variant}`);
}

function persistSession(user) {
  if (!user) return;
  const value = JSON.stringify(user);
  sessionStorage.setItem(AUTH_STORAGE_KEY, value);
  sessionStorage.setItem(DOCENTE_STORAGE_KEY, value);
  sessionStorage.setItem(SESSION_FLAG, '1');
}

function clearPersistedSession() {
  sessionStorage.removeItem(SESSION_FLAG);
  sessionStorage.removeItem(AUTH_STORAGE_KEY);
  sessionStorage.removeItem(DOCENTE_STORAGE_KEY);
}

if (!isLoginPage && window.__panelUser) persistSession(window.__panelUser);
if (isLoginPage) clearPersistedSession();

async function postAction(action, payload) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const response = await fetch(`${API_ENDPOINT}?action=${encodeURIComponent(action)}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
    body: JSON.stringify(payload),
  });
  const raw = await response.text();
  let data;
  try { data = JSON.parse(raw); } catch (_error) { throw new Error('La API devolvió una respuesta inválida.'); }
  if (!response.ok || data.success === false) throw new Error(data.error || data.message || `Error HTTP ${response.status}`);
  return data;
}

function getPanelPath(role) {
  return PANEL_PATHS[(role || 'docente').toLowerCase()] || PANEL_PATHS.docente;
}

if (loginForm) {
  loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const usuario = document.getElementById('usuario').value.trim();
    const contrasena = document.getElementById('contrasena').value;
    mensajeError?.classList.add('d-none');
    if (!usuario || !contrasena) return showLoginError('Completa usuario y contraseña.');
    try {
      const result = await postAction('login', { usuario, contrasena });
      persistSession(result.data);
      window.location.href = getPanelPath(result.data?.rol);
    } catch (error) { showLoginError(error.message); }
  });
}

recordarBtn?.addEventListener('click', () => {
  toggleLoginMode('recover');
  document.getElementById('recuperarCorreo')?.focus();
});
switchRecoverToLoginBtn?.addEventListener('click', () => toggleLoginMode('login'));

recoverForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const correo = (document.getElementById('recuperarCorreo')?.value || '').trim().toLowerCase();
  if (!document.getElementById('recuperarCorreo').checkValidity()) return showRecoveryMessage('Ingresa un correo electrónico válido.', 'warning');
  const button = document.getElementById('solicitarResetBtn');
  button.disabled = true;
  try {
    const result = await postAction('solicitarRestablecimiento', { correo });
    showRecoveryMessage(result.message, 'info');
  } catch (error) { showRecoveryMessage(error.message, 'danger'); }
  finally { button.disabled = false; }
});

document.getElementById('changePasswordForm')?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const current = document.getElementById('currentPassword').value;
  const password = document.getElementById('newPassword').value;
  const confirmation = document.getElementById('confirmNewPassword').value;
  const message = document.getElementById('changePasswordMessage');
  message.className = 'alert mt-3 mb-0';
  message.textContent = '';
  if (password !== confirmation) {
    message.classList.add('alert-warning');
    message.textContent = 'La confirmación de la contraseña no coincide.';
    return;
  }
  try {
    const result = await postAction('cambiarMiContrasena', { contrasena_actual: current, contrasena_nueva: password, confirmacion: confirmation });
    message.classList.add('alert-success');
    message.textContent = result.message;
    event.currentTarget.reset();
  } catch (error) {
    message.classList.add('alert-danger');
    message.textContent = error.message;
  }
});

const ACTIONS = [
  { id: 'btnGenerarReporte', handler: generarReporte },
  { id: 'btnImprimir', handler: () => window.print() },
  { id: 'btnReporteEstimulos', handler: generarReporteEstimulos },
  { id: 'btnImprimirEstimulos', handler: () => window.print() },
  { id: 'btnLogout', handler: cerrarSesion },
];

ACTIONS.forEach(({ id, handler }) => {
  const element = document.getElementById(id);
  if (element) {
    element.addEventListener('click', handler);
  }
});

function obtenerSeleccion(selector) {
  return Array.from(document.querySelectorAll(selector))
    .filter((checkbox) => checkbox.checked)
    .map((checkbox) => checkbox.nextElementSibling.textContent.trim());
}

function guardarSeleccion() {
  const seleccionadas = obtenerSeleccion('#disciplinariasAccordion input[type="checkbox"]');
  localStorage.setItem('faltasSeleccionadas', JSON.stringify(seleccionadas));
  alert('Selección guardada correctamente.');
}

function generarReporte() {
  if (typeof window.generarReporteDisciplinarioPdf === 'function') {
    window.generarReporteDisciplinarioPdf();
    return;
  }

  const lista = document.getElementById('listaReporte');
  const reporte = document.getElementById('reporteGenerado');
  if (!lista || !reporte) return;

  const seleccionadas = obtenerSeleccion('#disciplinariasAccordion input[type="checkbox"]');
  lista.innerHTML = '';

  if (seleccionadas.length === 0) {
    alert('No hay observaciones seleccionadas.');
    reporte.classList.add('d-none');
    return;
  }

  seleccionadas.forEach((item) => {
    const li = document.createElement('li');
    li.textContent = item;
    lista.appendChild(li);
  });

  reporte.classList.remove('d-none');
}

function generarReporteEstimulos() {
  if (typeof window.generarVistaPreviaDiplomaEstimulo === 'function') {
    window.generarVistaPreviaDiplomaEstimulo();
    return;
  }

  const reporte = document.getElementById('reporteEstimulos');
  if (typeof window.generarReporteEstimulosPdf === 'function') {
    window.generarReporteEstimulosPdf();
    return;
  }

  if (!reporte) return;

  const seleccionadas = obtenerSeleccion('#seccionEstimulos input[type="checkbox"]');
  reporte.innerHTML = '';

  if (seleccionadas.length === 0) {
    alert('No hay estímulos seleccionados.');
    reporte.classList.add('d-none');
    localStorage.removeItem('estimulosSeleccionados');
    return;
  }

  localStorage.setItem('estimulosSeleccionados', JSON.stringify(seleccionadas));

  const titulo = document.createElement('h5');
  titulo.textContent = 'Estímulos seleccionados:';

  const lista = document.createElement('ul');
  lista.className = 'mb-0';

  seleccionadas.forEach((item) => {
    const li = document.createElement('li');
    li.textContent = item;
    lista.appendChild(li);
  });

  reporte.appendChild(titulo);
  reporte.appendChild(lista);
  reporte.classList.remove('d-none');
}

function cerrarSesion() {
  postAction('logout', {})
    .catch(() => null)
    .finally(() => {
      clearPersistedSession();

      if (!loginSection) {
        window.location.href = 'acceso.php';
        return;
      }

      const forms = document.querySelectorAll('form');
      forms.forEach((form) => form.reset());
      mensajeError?.classList.add('d-none');
      hideRecoveryMessage();
      clearRecoveryVerificationState();
      toggleLoginMode('login');
    });
}
// Chatbot y UI gestionados por frontend/chatbot/chatbot.js
// Se eliminó el código duplicado aquí para evitar conflictos con el módulo del chatbot.
