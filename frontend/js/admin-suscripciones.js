(() => {
  const modal = document.getElementById('adminSubscriptionsModal');
  if (!modal) return;

  const message = document.getElementById('subscriptionAdminMessage');
  const tableBody = document.getElementById('subscriptionAdminTableBody');
  const search = document.getElementById('subscriptionAdminSearch');
  const filter = document.getElementById('subscriptionAdminFilter');
  const teacher = document.getElementById('subscriptionPaymentTeacher');
  const plan = document.getElementById('subscriptionPaymentPlan');
  const paymentForm = document.getElementById('subscriptionPaymentForm');
  const activationForm = document.getElementById('subscriptionActivationForm');
  const activationPaymentId = document.getElementById('subscriptionActivationPaymentId');
  const activationSubmit = document.getElementById('subscriptionActivationSubmit');
  const activationSummary = document.getElementById('subscriptionSelectedSummary');
  const history = document.getElementById('subscriptionHistory');
  let rows = [];

  const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
  const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
  const dateToday = () => new Date().toISOString().slice(0, 10);

  function showMessage(text, variant = 'info') {
    message.textContent = text;
    message.className = `alert alert-${variant}`;
  }

  function clearMessage() {
    message.textContent = '';
    message.className = 'alert d-none';
  }

  async function request(action, method = 'GET', body = null, query = {}) {
    const params = new URLSearchParams({ action, ...query, _ts: String(Date.now()) });
    const response = await fetch(`api.php?${params.toString()}`, {
      method,
      credentials: 'same-origin',
      cache: 'no-store',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf() },
      body: body === null ? undefined : JSON.stringify(body),
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok || data.success === false) throw new Error(data.error || `Error HTTP ${response.status}`);
    return data;
  }

  function visibleRows() {
    const value = String(search.value || '').trim().toLocaleLowerCase();
    return rows.filter((row) => !value || `${row.nombre_completo} ${row.usuario} ${row.correo}`.toLocaleLowerCase().includes(value));
  }

  function renderTeacherOptions() {
    const selected = teacher.value;
    teacher.innerHTML = '<option value="">Selecciona un docente...</option>' + rows.map((row) => `<option value="${row.docente_id}">${escapeHtml(row.nombre_completo)} — ${escapeHtml(row.correo)}</option>`).join('');
    if (selected && rows.some((row) => String(row.docente_id) === selected)) teacher.value = selected;
  }

  function renderRows() {
    const visible = visibleRows();
    if (!visible.length) {
      tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay docentes que coincidan con el filtro.</td></tr>';
      return;
    }
    tableBody.innerHTML = visible.map((row) => {
      const pending = row.pagos_pendientes?.[0];
      const paymentAction = pending
        ? `<button type="button" class="btn btn-sm ${pending.estado_pago === 'VERIFICADO' ? 'btn-success' : 'btn-outline-warning'}" data-payment-action="${pending.estado_pago === 'VERIFICADO' ? 'activate' : 'verify'}" data-payment-id="${pending.id}" data-teacher-id="${row.docente_id}">${pending.estado_pago === 'VERIFICADO' ? 'Activar pago verificado' : 'Verificar pago'}</button>`
        : '';
      return `<tr>
        <td><strong>${escapeHtml(row.nombre_completo)}</strong><br><small>${escapeHtml(row.correo || row.usuario)}</small></td>
        <td>${escapeHtml(row.fecha_registro)}</td>
        <td>${escapeHtml(row.plan_nombre)}</td>
        <td>${escapeHtml(row.fecha_inicio || '—')}</td>
        <td>${escapeHtml(row.fecha_vencimiento || '—')}</td>
        <td><span class="badge text-bg-${row.estado === 'ACTIVA' || row.estado === 'PRUEBA ACTIVA' ? 'success' : row.estado === 'PENDIENTE DE PAGO' ? 'warning' : 'secondary'}">${escapeHtml(row.estado)}</span></td>
        <td>${escapeHtml(row.estado_pago || 'SIN PAGO')}<br>${pending ? `<small>${escapeHtml(pending.plan_nombre)}</small>` : ''}</td>
        <td><div class="d-flex flex-wrap gap-1">${paymentAction}<button type="button" class="btn btn-sm btn-outline-primary" data-payment-action="select" data-teacher-id="${row.docente_id}">Registrar pago</button><button type="button" class="btn btn-sm btn-outline-secondary" data-payment-action="history" data-teacher-id="${row.docente_id}">Historial</button></div></td>
      </tr>`;
    }).join('');
  }

  async function loadRows() {
    const result = await request('obtenerSuscripcionesAdmin', 'GET', null, { filtro: filter.value, buscar: search.value.trim() });
    rows = Array.isArray(result.data) ? result.data : [];
    renderTeacherOptions();
    renderRows();
  }

  async function loadPlans() {
    const result = await request('obtenerPlanesSuscripcion');
    plan.innerHTML = '<option value="">Selecciona un plan...</option>' + (result.data || []).map((item) => `<option value="${escapeHtml(item.codigo)}" data-duration="${item.duracion_dias}" data-price="${item.precio}">${escapeHtml(item.nombre)} · $${escapeHtml(item.precio)}</option>`).join('');
  }

  async function loadHistory(teacherId) {
    const result = await request('obtenerHistorialSuscripcionesAdmin', 'GET', null, { docente_id: teacherId });
    if (!result.data?.length) {
      history.textContent = 'No hay suscripciones históricas.';
      return;
    }
    history.innerHTML = result.data.map((item) => `<div class="border-bottom py-2"><strong>${escapeHtml(item.plan_nombre)}</strong><br>${escapeHtml(item.estado)} · ${escapeHtml(item.fecha_inicio)} → ${escapeHtml(item.fecha_vencimiento)}<br><small>Pago: ${escapeHtml(item.estado_pago)}${item.valor_recibido ? ` · $${escapeHtml(item.valor_recibido)}` : ''}</small></div>`).join('');
  }

  function selectTeacher(teacherId) {
    teacher.value = String(teacherId);
    document.getElementById('subscriptionPaymentForm')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  async function verifyPayment(paymentId) {
    await request('verificarPagoAdmin', 'POST', { pago_id: Number(paymentId) });
    showMessage('Pago verificado. Ahora puedes activar o renovar la suscripción.', 'success');
    await loadRows();
  }

  function prepareActivation(paymentId, teacherId) {
    activationPaymentId.value = String(paymentId);
    activationSubmit.disabled = false;
    document.getElementById('subscriptionActivationStart').value = dateToday();
    activationSummary.textContent = 'Pago verificado seleccionado. Define el inicio y la duración para activar o renovar la cuenta existente.';
    selectTeacher(teacherId);
    activationForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  tableBody.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-payment-action]');
    if (!button) return;
    const action = button.dataset.paymentAction;
    const teacherId = Number(button.dataset.teacherId || 0);
    try {
      if (action === 'select') selectTeacher(teacherId);
      if (action === 'history') { selectTeacher(teacherId); await loadHistory(teacherId); }
      if (action === 'verify') await verifyPayment(button.dataset.paymentId);
      if (action === 'activate') prepareActivation(button.dataset.paymentId, teacherId);
    } catch (error) { showMessage(error.message, 'danger'); }
  });

  paymentForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
      await request('registrarPagoAdmin', 'POST', {
        docente_id: Number(teacher.value),
        plan: plan.value,
        valor_recibido: Number(document.getElementById('subscriptionPaymentAmount').value),
        fecha_pago: document.getElementById('subscriptionPaymentDate').value,
        metodo_pago: document.getElementById('subscriptionPaymentMethod').value.trim(),
        referencia: document.getElementById('subscriptionPaymentReference').value.trim(),
        observaciones: document.getElementById('subscriptionPaymentNotes').value.trim(),
        estado_pago: document.getElementById('subscriptionPaymentState').value,
      });
      showMessage('Pago registrado. Revisa el listado para verificarlo y activarlo cuando corresponda.', 'success');
      paymentForm.reset();
      document.getElementById('subscriptionPaymentDate').value = dateToday();
      await loadRows();
    } catch (error) { showMessage(error.message, 'danger'); }
  });

  activationForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
      await request('activarSuscripcionAdmin', 'POST', {
        pago_id: Number(activationPaymentId.value),
        fecha_inicio: document.getElementById('subscriptionActivationStart').value,
        duracion_dias: Number(document.getElementById('subscriptionActivationDuration').value),
      });
      showMessage('Suscripción activada o renovada correctamente. La cuenta existente conserva sus datos.', 'success');
      activationPaymentId.value = '';
      activationSubmit.disabled = true;
      await loadRows();
    } catch (error) { showMessage(error.message, 'danger'); }
  });

  search.addEventListener('input', renderRows);
  filter.addEventListener('change', () => loadRows().catch((error) => showMessage(error.message, 'danger')));
  document.getElementById('subscriptionAdminRefresh').addEventListener('click', () => loadRows().then(() => showMessage('Listado actualizado.', 'info')).catch((error) => showMessage(error.message, 'danger')));
  modal.addEventListener('show.bs.modal', async () => {
    clearMessage();
    document.getElementById('subscriptionPaymentDate').value = dateToday();
    document.getElementById('subscriptionActivationStart').value = dateToday();
    try { await Promise.all([loadPlans(), loadRows()]); } catch (error) { showMessage(error.message, 'danger'); }
  });
})();

