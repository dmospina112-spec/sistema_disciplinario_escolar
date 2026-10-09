(() => {
  const toggle = document.querySelector('.nav-toggle');
  const navigation = document.getElementById('main-navigation');
  if (toggle && navigation) {
    toggle.addEventListener('click', () => {
      const open = navigation.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    });
    navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
      navigation.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }));
  }

  const form = document.getElementById('contact-form');
  if (!form) return;
  const planSelect = document.getElementById('plan');
  const feedback = document.getElementById('contact-feedback');
  const submitButton = form.querySelector('button[type="submit"]');
  const submitLabel = submitButton.querySelector('.submit-label');
  const promotionActive = document.body.dataset.promotionActive === '1';

  const selectPlan = (plan) => {
    if (plan === 'promocion' && !promotionActive) return;
    if ([...planSelect.options].some((option) => option.value === plan)) {
      planSelect.value = plan;
      document.getElementById('contacto').scrollIntoView({ behavior: 'smooth', block: 'start' });
      window.setTimeout(() => planSelect.focus({ preventScroll: true }), 450);
    }
  };

  document.querySelectorAll('[data-plan]').forEach((button) => {
    button.addEventListener('click', () => selectPlan(button.dataset.plan));
  });

  const params = new URLSearchParams(window.location.search);
  const requestedPlan = params.get('plan');
  if (requestedPlan) selectPlan(requestedPlan);

  const showFeedback = (message, success = false) => {
    feedback.textContent = message;
    feedback.className = `form-alert ${success ? 'is-success' : 'is-error'}`;
    feedback.hidden = false;
    feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    submitButton.disabled = true;
    submitLabel.textContent = 'ENVIANDO…';
    feedback.hidden = true;
    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'fetch' },
        credentials: 'same-origin'
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        showFeedback(result.message || 'No fue posible enviar tu solicitud. Revisa tus datos e inténtalo de nuevo.');
        return;
      }
      showFeedback('¡Gracias por contactar a COMPORTATE! Tu solicitud fue enviada correctamente. No crea una cuenta; la administradora revisará la solicitud y se pondrá en contacto contigo.', true);
      form.reset();
    } catch (error) {
      showFeedback('No pudimos conectar con el servicio de envío. Conservamos la información del formulario para que puedas intentarlo de nuevo.');
    } finally {
      submitButton.disabled = false;
      submitLabel.textContent = 'ENVIAR MENSAJE';
    }
  });
})();
