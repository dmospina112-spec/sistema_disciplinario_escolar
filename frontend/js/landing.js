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
  const trialFields = document.getElementById('trial-registration-fields');
  const trialPassword = document.getElementById('password');
  const trialPasswordConfirmation = document.getElementById('password_confirmation');
  const messageField = document.getElementById('message');

  const updateTrialFields = () => {
    const isTrial = planSelect.value === 'prueba_gratuita';
    if (trialFields) trialFields.hidden = !isTrial;
    if (trialPassword) trialPassword.required = isTrial;
    if (trialPasswordConfirmation) trialPasswordConfirmation.required = isTrial;
    if (messageField) messageField.required = !isTrial;
  };

  const selectPlan = (plan) => {
    if (plan === 'promocion' && !promotionActive) return;
    if ([...planSelect.options].some((option) => option.value === plan)) {
      planSelect.value = plan;
      updateTrialFields();
      document.getElementById('contacto').scrollIntoView({ behavior: 'smooth', block: 'start' });
      window.setTimeout(() => planSelect.focus({ preventScroll: true }), 450);
    }
  };

  planSelect.addEventListener('change', updateTrialFields);
  updateTrialFields();

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
      const isTrial = planSelect.value === 'prueba_gratuita';
      showFeedback(isTrial
        ? '¡Tu cuenta fue creada! La prueba gratuita de 14 días está activa. Ya puedes ingresar con tu mismo correo y contraseña.'
        : '¡Gracias por contactar a COMPORTATE! Tu solicitud fue enviada correctamente. La administradora revisará la solicitud y se pondrá en contacto contigo.', true);
      form.reset();
      updateTrialFields();
    } catch (error) {
      showFeedback('No pudimos conectar con el servicio de envío. Conservamos la información del formulario para que puedas intentarlo de nuevo.');
    } finally {
      submitButton.disabled = false;
      submitLabel.textContent = 'ENVIAR MENSAJE';
    }
  });
})();
