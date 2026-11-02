<?php
declare(strict_types=1);

require_once __DIR__ . '/app/backend/config.php';
require_once __DIR__ . '/app/backend/session.php';

try {
    loadLocalEnv();
    ensureAppSessionStarted();
} catch (Throwable $exception) {
    http_response_code(500);
    exit('No fue posible iniciar la página. Intenta nuevamente más tarde.');
}

$zone = new DateTimeZone('America/Bogota');
$today = new DateTimeImmutable('now', $zone);
$promotionIsActive = in_array((int) $today->format('n'), [1, 2], true);
$adminContactEmail = trim((string) (getenv('ADMIN_CONTACT_EMAIL') ?: 'comportateweb@gmail.com'));
$adminContactPhone = trim((string) (getenv('ADMIN_CONTACT_PHONE') ?: '1212121212'));
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['comportate_contact_csrf'] = $csrfToken;
$_SESSION['comportate_contact_started'] = time();
$styleVersion = (string) md5_file(__DIR__ . '/frontend/css/landing.css');
$scriptVersion = (string) md5_file(__DIR__ . '/frontend/js/landing.js');

function landingEscape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="COMPORTATE ayuda a docentes a organizar el seguimiento disciplinario, reconocer estímulos y fortalecer la comunicación escolar.">
  <meta name="theme-color" content="#102b46">
  <title>COMPORTATE | Seguimiento escolar con propósito</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/css/landing.css?v=<?= landingEscape($styleVersion) ?>">
</head>
<body class="landing-page" data-promotion-active="<?= $promotionIsActive ? '1' : '0' ?>">
  <header class="site-header">
    <nav class="container nav-shell" aria-label="Navegación principal">
      <a class="brand" href="#inicio" aria-label="COMPORTATE, inicio">
        <img src="frontend/img/Logo-comportate-transparent.png" alt="" class="brand-logo">
        <span class="brand-name">COMPORTATE</span>
      </a>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Abrir menú">
        <span></span><span></span><span></span>
      </button>
      <div class="nav-links" id="main-navigation">
        <a href="#inicio">Inicio</a><a href="#nosotros">Nosotros</a><a href="#mision-vision">Misión y visión</a>
        <a href="#valores">Valores</a><a href="#planes">Planes</a><a href="#contacto">Contacto</a>
        <a class="nav-login" href="acceso.php">Ingresar</a>
      </div>
    </nav>
  </header>

  <main>
    <section class="hero-section" id="inicio">
      <div class="hero-orbit hero-orbit-one"></div><div class="hero-orbit hero-orbit-two"></div>
      <div class="container hero-grid">
        <div class="hero-copy">
          <span class="eyebrow"><span class="eyebrow-dot"></span> Una mejor forma de acompañar</span>
          <h1>La convivencia escolar también se <span>construye día a día.</span></h1>
          <p>Organiza el seguimiento de tus estudiantes, reconoce sus avances y mantén una comunicación más clara con sus familias.</p>
          <div class="hero-actions">
            <a href="#planes" class="button button-primary">Conoce los planes <span aria-hidden="true">↗</span></a>
            <a href="#nosotros" class="button button-quiet">Descubre COMPORTATE <span aria-hidden="true">↓</span></a>
          </div>
          <div class="hero-note"><span class="note-check" aria-hidden="true">✓</span> Una herramienta de apoyo para el trabajo docente</div>
        </div>
        <div class="hero-art" aria-label="Ilustración de seguimiento escolar">
          <div class="art-glow"></div>
          <div class="art-card art-card-back"><span class="art-lines"><i></i><i></i><i></i></span><span class="art-spark">✦</span></div>
          <div class="art-card art-card-main">
            <div class="art-topline"><span class="art-mini-logo">C</span><span>Seguimiento escolar</span><span class="art-dots">•••</span></div>
            <div class="art-welcome">Acompañar también es reconocer.</div>
            <div class="art-profile"><div class="avatar avatar-one">L</div><div><strong>Logros y avances</strong><small>Un registro para cada paso</small></div><span class="art-check">✓</span></div>
            <div class="art-profile"><div class="avatar avatar-two">M</div><div><strong>Seguimiento organizado</strong><small>Información a tu alcance</small></div><span class="art-arrow">↗</span></div>
            <div class="art-progress-label"><span>Construyendo convivencia</span><span>+ positivo</span></div>
            <div class="art-progress"><i></i></div>
          </div>
          <div class="floating-tag tag-star"><span>✦</span> Estímulos positivos</div>
          <div class="floating-tag tag-chat"><span>↗</span> Comunicación cercana</div>
          <div class="art-sun"></div>
        </div>
      </div>
      <div class="hero-bottom container"><span>Hecha para acompañar la labor docente</span><span class="hero-bottom-line"></span><span>Organización · Convivencia · Comunicación</span></div>
    </section>

    <section class="trial-banner section-pad" id="prueba-gratuita">
      <div class="container trial-banner-grid">
        <div>
          <span class="eyebrow eyebrow-dark">Empieza hoy</span>
          <h2>¡PRUEBA COMPORTATE GRATIS DURANTE 14 DÍAS!</h2>
          <p>Conoce todas las herramientas que tenemos para facilitar la gestión disciplinaria y fortalecer la comunicación entre docentes y acudientes.</p>
        </div>
        <div class="trial-banner-side">
          <ul class="trial-benefits">
            <li>14 días de acceso gratuito.</li>
            <li>Conoce las funcionalidades disponibles.</li>
            <li>Administra tus procesos disciplinarios.</li>
            <li>Mejora la comunicación con los acudientes.</li>
          </ul>
          <a class="button button-primary" href="#contacto" data-plan="prueba_gratuita">COMENZAR PRUEBA GRATUITA <span aria-hidden="true">→</span></a>
        </div>
      </div>
    </section>

    <section class="intro-section section-pad" id="nosotros">
      <div class="container intro-grid">
        <div class="section-heading">
          <span class="eyebrow eyebrow-dark">Conoce la plataforma</span>
          <h2>Más claridad para acompañar cada proceso.</h2>
          <p>COMPORTATE es una herramienta digital para apoyar la gestión disciplinaria escolar. Reúne en un solo lugar el registro y la consulta de observaciones, estímulos e historiales.</p>
          <a class="text-link" href="acceso.php">Ya tengo una cuenta <span aria-hidden="true">→</span></a>
        </div>
        <div class="feature-grid">
          <article class="feature-card feature-large"><span class="feature-icon icon-blue" aria-hidden="true">▤</span><h3>Seguimiento organizado</h3><p>Registra observaciones y consulta el historial de cada estudiante con mayor facilidad.</p><span class="feature-number">01</span></article>
          <article class="feature-card"><span class="feature-icon icon-orange" aria-hidden="true">✦</span><h3>Reconoce los avances</h3><p>Da visibilidad a los estímulos y logros positivos.</p><span class="feature-number">02</span></article>
          <article class="feature-card"><span class="feature-icon icon-green" aria-hidden="true">◎</span><h3>Comunicación cercana</h3><p>Apoya la comunicación con acudientes cuando esa función está habilitada.</p><span class="feature-number">03</span></article>
        </div>
      </div>
    </section>

    <section class="purpose-section section-pad" id="mision-vision">
      <div class="container">
        <div class="center-heading"><span class="eyebrow eyebrow-light">Nuestro propósito</span><h2>Una comunidad educativa que avanza en equipo.</h2></div>
        <div class="purpose-grid">
          <article class="purpose-card"><span class="purpose-symbol">✳</span><span class="purpose-label">01 / Misión</span><h3>Acompañar con herramientas útiles.</h3><p>Facilitar la gestión disciplinaria en las instituciones educativas mediante herramientas digitales que permitan registrar, organizar y consultar el historial disciplinario, promover los estímulos positivos y fortalecer la comunicación entre docentes y acudientes.</p></article>
          <article class="purpose-card purpose-card-accent"><span class="purpose-symbol">↗</span><span class="purpose-label">02 / Visión</span><h3>Contribuir a una convivencia más positiva.</h3><p>Ser una plataforma educativa reconocida por contribuir a la modernización de la gestión disciplinaria escolar, promoviendo una convivencia más positiva, una comunicación efectiva y el uso responsable de la tecnología.</p></article>
        </div>
      </div>
    </section>

    <section class="values-section section-pad" id="valores">
      <div class="container">
        <div class="section-heading values-heading"><span class="eyebrow eyebrow-dark">Lo que nos guía</span><h2>Valores que hacen parte de cada proceso.</h2></div>
        <div class="values-grid">
          <article class="value-card"><span class="value-icon">♡</span><h3>Respeto</h3><p>Promovemos relaciones respetuosas entre estudiantes, docentes y familias.</p></article>
          <article class="value-card"><span class="value-icon">✓</span><h3>Responsabilidad</h3><p>Fomentamos el cumplimiento de compromisos y deberes escolares.</p></article>
          <article class="value-card"><span class="value-icon">⌂</span><h3>Convivencia</h3><p>Contribuimos a construir ambientes educativos pacíficos y positivos.</p></article>
          <article class="value-card"><span class="value-icon">✳</span><h3>Compromiso</h3><p>Acompañamos a los docentes en el seguimiento de cada proceso.</p></article>
          <article class="value-card"><span class="value-icon">↗</span><h3>Comunicación</h3><p>Fortalecemos el intercambio de información entre docentes y acudientes.</p></article>
          <article class="value-card"><span class="value-icon">✦</span><h3>Innovación</h3><p>Aprovechamos la tecnología para mejorar la gestión escolar.</p></article>
        </div>
      </div>
    </section>

    <section class="plans-section section-pad" id="planes">
      <div class="container">
        <div class="plans-heading"><div><span class="eyebrow eyebrow-dark">Planes sencillos y claros</span><h2>Elige cómo quieres comenzar.</h2></div><p>Selecciona un plan para solicitar información. La selección expresa tu interés y no genera un cobro ni una contratación automática.</p></div>
        <div class="plans-grid">
          <article class="plan-card plan-card-trial">
            <div class="plan-top"><span class="plan-index">PLAN 00</span><span class="plan-icon" aria-hidden="true">✦</span></div>
            <h3>Prueba gratuita de 14 días</h3><p class="plan-desc">Acceso gratuito para conocer las funcionalidades de COMPORTATE.</p>
            <div class="plan-price"><strong>GRATIS</strong><span>14 días</span></div>
            <ul class="plan-benefits"><li>Acceso sin costo durante 14 días</li><li>Sin cobro automático</li><li>Conserva tu cuenta y tus datos</li></ul>
            <button class="button button-featured plan-select" type="button" data-plan="prueba_gratuita">Comenzar prueba gratuita <span aria-hidden="true">→</span></button>
          </article>
          <article class="plan-card">
            <div class="plan-top"><span class="plan-index">PLAN 01</span><span class="plan-icon" aria-hidden="true">◷</span></div>
            <h3>Suscripción mensual</h3><p class="plan-desc">Acceso a la plataforma con una mensualidad.</p>
            <div class="plan-price"><strong>$15.000</strong><span>COP / mes</span></div>
            <ul class="plan-benefits"><li>Un mes de servicio</li><li>Pago mensual</li><li>Sin cobro automático</li></ul>
            <button class="button button-outline plan-select" type="button" data-plan="mensual">Seleccionar plan mensual <span aria-hidden="true">→</span></button>
          </article>

          <?php if ($promotionIsActive): ?>
          <article class="plan-card plan-card-featured">
            <div class="plan-ribbon">20% de descuento · enero y febrero</div>
            <div class="plan-top"><span class="plan-index">PLAN 02</span><span class="plan-icon" aria-hidden="true">✦</span></div>
            <h3>Promoción de temporada escolar</h3><p class="plan-desc">Una oportunidad para empezar el año escolar.</p>
            <div class="plan-price"><strong>$12.000</strong><span>COP · primer mes</span></div>
            <p class="plan-was">Precio regular <s>$15.000</s> <span>Ahorras $3.000</span></p>
            <ul class="plan-benefits"><li>20% de descuento en la primera mensualidad</li><li>Mensualidades posteriores: $15.000 COP</li><li>Vigente solo en enero y febrero de <?= landingEscape($today->format('Y')) ?></li></ul>
            <button class="button button-featured plan-select" type="button" data-plan="promocion">Seleccionar promoción <span aria-hidden="true">→</span></button>
          </article>
          <?php else: ?>
          <article class="plan-card plan-card-unavailable">
            <div class="plan-top"><span class="plan-index">PLAN 02</span><span class="plan-icon" aria-hidden="true">✦</span></div>
            <span class="plan-status">Disponible en enero y febrero</span><h3>Promoción de temporada escolar</h3><p class="plan-desc">El descuento de temporada se activa únicamente durante enero y febrero.</p>
            <div class="plan-price"><strong>$12.000</strong><span>COP · primer mes promocional</span></div>
            <p class="plan-was">Precio regular <s>$15.000</s> · Ahorras $3.000</p>
            <ul class="plan-benefits"><li>20% de descuento en la primera mensualidad</li><li>Mensualidades posteriores: $15.000 COP</li><li>Promoción no vigente en este momento</li></ul>
            <button class="button button-outline" type="button" disabled>Promoción no disponible</button>
          </article>
          <?php endif; ?>

          <article class="plan-card">
            <div class="plan-top"><span class="plan-index">PLAN 03</span><span class="plan-icon" aria-hidden="true">▣</span></div>
            <h3>Suscripción anual</h3><p class="plan-desc">Pague 10, lleve 12: un año completo de servicio.</p>
            <div class="plan-price"><strong>$150.000</strong><span>COP / año</span></div>
            <p class="plan-was">Precio normal $180.000 · <span>Ahorras $30.000</span></p>
            <ul class="plan-benefits"><li>12 meses de servicio</li><li>Una sola cuota anual</li><li>Descuento aproximado del 16,6%</li></ul>
            <button class="button button-outline plan-select" type="button" data-plan="anual">Seleccionar plan anual <span aria-hidden="true">→</span></button>
          </article>
        </div>
        <p class="plans-footnote">Los precios se expresan en pesos colombianos (COP). La solicitud permite continuar una conversación comercial; no realiza pagos ni confirma una contratación.</p>
      </div>
    </section>

    <section class="contact-section section-pad" id="contacto">
      <div class="container contact-grid">
        <div class="contact-copy"><span class="eyebrow eyebrow-light">Hablemos de tu proceso</span><h2>¿Quieres conocer más sobre COMPORTATE?</h2><p>Selecciona la prueba gratuita para crear tu cuenta docente. Para los planes pagos, este formulario envía una solicitud de información y la administradora se pondrá en contacto contigo.</p>
          <div class="contact-detail"><span class="contact-detail-icon">✉</span><div><small>Correo de contacto y soporte</small><a href="mailto:<?= landingEscape($adminContactEmail) ?>"><?= landingEscape($adminContactEmail) ?></a></div></div>
          <div class="contact-detail"><span class="contact-detail-icon">☎</span><div><small>Teléfono</small><a href="tel:<?= landingEscape($adminContactPhone) ?>"><?= landingEscape($adminContactPhone) ?></a></div></div>
        </div>
        <div class="contact-form-wrap" id="formulario-contacto">
          <div class="form-heading"><span class="form-step">Tu solicitud</span><h3>Déjanos tus datos</h3><p>Los campos marcados con * son obligatorios.</p></div>
          <div class="form-alert" id="contact-feedback" role="status" aria-live="polite" hidden></div>
          <form id="contact-form" action="contacto.php" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= landingEscape($csrfToken) ?>">
            <div class="honeypot" aria-hidden="true"><label for="website">No completes este campo</label><input type="text" name="website" id="website" tabindex="-1" autocomplete="off"></div>
            <div class="form-row">
              <div class="field"><label for="full_name">Nombre completo <span>*</span></label><input id="full_name" name="full_name" type="text" maxlength="120" autocomplete="name" placeholder="Tu nombre y apellido" required></div>
              <div class="field"><label for="phone">Teléfono <span>*</span></label><input id="phone" name="phone" type="tel" maxlength="40" autocomplete="tel" placeholder="Tu número de contacto" required></div>
            </div>
            <div class="field"><label for="email">Correo electrónico <span>*</span></label><input id="email" name="email" type="email" maxlength="254" autocomplete="email" placeholder="nombre@correo.com" required></div>
            <div class="field"><label for="plan">Plan de interés <span>*</span></label>
              <select id="plan" name="plan" required>
                <option value="">Selecciona una opción</option>
                <option value="prueba_gratuita">Prueba gratuita de 14 días — GRATIS</option>
                <option value="mensual">Suscripción mensual — $15.000 COP</option>
                <?php if ($promotionIsActive): ?><option value="promocion">Promoción de temporada — $12.000 COP el primer mes</option><?php endif; ?>
                <option value="anual">Suscripción anual “Pague 10, lleve 12” — $150.000 COP</option>
                <option value="todos">Deseo recibir información sobre todos los planes</option>
              </select>
            </div>
            <div class="trial-registration-fields" id="trial-registration-fields" hidden>
              <div class="trial-registration-note">Para activar tu prueba crea la contraseña de tu cuenta. Debe tener mínimo 10 caracteres, con mayúscula, minúscula y número.</div>
              <div class="form-row">
                <div class="field"><label for="password">Contraseña <span>*</span></label><input id="password" name="password" type="password" minlength="10" autocomplete="new-password"></div>
                <div class="field"><label for="password_confirmation">Confirmar contraseña <span>*</span></label><input id="password_confirmation" name="password_confirmation" type="password" minlength="10" autocomplete="new-password"></div>
              </div>
            </div>
            <div class="field"><label for="message">Mensaje <span class="message-required-mark">*</span></label><textarea id="message" name="message" rows="4" maxlength="3000" placeholder="Escribe aquí tus preguntas o comentarios" required></textarea></div>
            <div class="privacy-check"><input type="checkbox" id="privacy" name="privacy" value="1" required><label for="privacy">Autorizo el tratamiento de mis datos personales para atender esta solicitud, conforme a la <a href="politica_privacidad.php" target="_blank" rel="noopener">Política de tratamiento de datos personales</a>. <span>*</span></label></div>
            <button type="submit" class="button button-submit"><span class="submit-label">ENVIAR MENSAJE</span><span class="submit-arrow" aria-hidden="true">→</span></button>
            <p class="form-privacy-note">Usaremos tus datos para crear la cuenta de prueba o responder esta solicitud. Los pagos no se realizan en línea.</p>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-main">
      <a class="footer-brand" href="#inicio"><img src="frontend/img/Logo-comportate-white-clean.png" alt=""><span>COMPORTATE</span></a>
      <p>Seguimiento escolar con propósito.</p>
      <div class="footer-links"><a href="#inicio">Inicio</a><a href="#planes">Planes</a><a href="#contacto">Contacto</a><a href="terminos_condiciones.php">Términos y condiciones</a><a href="politica_privacidad.php">Política de datos</a></div>
    </div>
    <div class="container footer-bottom"><span>© <?= landingEscape($today->format('Y')) ?> COMPORTATE</span><a href="acceso.php">Acceso docente <span aria-hidden="true">↗</span></a></div>
  </footer>
  <script src="frontend/js/landing.js?v=<?= landingEscape($scriptVersion) ?>" defer></script>
</body>
</html>
