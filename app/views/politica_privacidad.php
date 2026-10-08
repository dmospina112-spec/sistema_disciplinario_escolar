<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$projectRoot = dirname(__DIR__, 2);
$stylesVersion = (string) md5_file($projectRoot . '/frontend/css/styles.css');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, follow">
  <title>Política de tratamiento de datos personales</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/css/styles.css?v=<?php echo htmlspecialchars($stylesVersion, ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    .privacy-shell { width: min(980px, calc(100% - 2rem)); margin: 2.5rem auto; }
    .privacy-document { padding: clamp(1.25rem, 4vw, 3.25rem); border: 1px solid rgba(148,163,184,.28); border-radius: 1.5rem; background: rgba(255,255,255,.94); box-shadow: 0 18px 48px rgba(15,23,42,.12); }
    .privacy-document h1 { color: #1d4f91; font-family: var(--font-display); font-size: clamp(2rem,5vw,3.15rem); line-height: 1.05; }
    .privacy-document h2 { margin-top: 2rem; color: #1d4f91; font-size: 1.15rem; font-weight: 800; }
    .privacy-document p, .privacy-document li { color: #334155; line-height: 1.75; }
    .privacy-meta { color: #64748b; font-size: .9rem; }
    .privacy-pending { padding: 1rem 1.15rem; border-left: 4px solid #b7791f; border-radius: .5rem; background: #fffbeb; color: #713f12; }
    .privacy-document a { overflow-wrap: anywhere; }
    @media print { body.site-body { display: block; background: #fff; } body.site-body::before, body.site-body::after, .privacy-back, .site-footer { display: none !important; } .privacy-shell { width: 100%; margin: 0; } .privacy-document { padding: 0; border: 0; box-shadow: none; } }
  </style>
</head>
<body class="site-body">
  <main class="privacy-shell">
    <a class="btn btn-outline-primary privacy-back mb-3" href="index.php">← Volver a la aplicación</a>
    <article class="privacy-document">
      <header class="mb-4">
        <p class="text-uppercase fw-bold small text-primary mb-2">Protección de datos personales</p>
        <h1>Política de tratamiento de datos personales</h1>
        <p class="privacy-meta mb-1">Aplicación de seguimiento disciplinario y convivencia escolar</p>
        <p class="privacy-meta">Versión 1.0 · Aprobación y vigencia: <strong>[completar fecha de aprobación]</strong></p>
      </header>

      <div class="privacy-pending mb-4"><strong>Documento de trabajo pendiente de completar:</strong> la aplicaci&oacute;n es ofrecida directamente por su creadora a docentes que se registran individualmente; ninguna instituci&oacute;n administra las cuentas de la plataforma. Antes de usar datos reales, complete la identidad y contacto de la creadora, confirme alojamiento/subencargados y plazos, y formalice las autorizaciones y acuerdos requeridos. Los roles de responsable y encargado dependen de qui&eacute;n decide realmente las finalidades y medios de cada tratamiento.</div>

            <h2>1. Responsable del tratamiento y alcance</h2>
      <p>La plataforma es creada y ofrecida directamente a docentes por <strong>[nombre legal completo de la creadora o empresa]</strong>, identificada con <strong>[c&eacute;dula o NIT]</strong>, con direcci&oacute;n de contacto <strong>[direcci&oacute;n electr&oacute;nica o f&iacute;sica]</strong> y tel&eacute;fono <strong>[n&uacute;mero de contacto]</strong> (la “Operadora”). Estas cuentas son individuales: una instituci&oacute;n educativa no administra el registro ni la cuenta dentro de la aplicaci&oacute;n. La Operadora es responsable del tratamiento de los datos de cuenta de docentes que recolecta para registro, autenticaci&oacute;n, seguridad, soporte y prestaci&oacute;n directa del servicio.</p>
      <p>Los docentes pueden registrar datos de estudiantes y acudientes para llevar seguimiento escolar y elaborar comunicaciones. La calidad de responsable o encargado respecto de esos datos se determina seg&uacute;n qui&eacute;n decide efectivamente sus finalidades y medios. Un docente que act&uacute;a de manera independiente y decide esos aspectos puede ser responsable del tratamiento. Si el docente registra informaci&oacute;n como parte de sus funciones para una instituci&oacute;n educativa, esa entidad puede ser responsable aunque no administre la cuenta de la aplicaci&oacute;n; el docente debe contar previamente con autorizaci&oacute;n institucional para usar un servicio externo. La Operadora solo act&uacute;a como encargada de esos datos cuando procesa exclusivamente por cuenta e instrucciones documentadas del responsable y existe el acuerdo aplicable. Esta pol&iacute;tica no cambia los roles que resulten de la operaci&oacute;n real.</p>
      <p>La Operadora no recibe del solo registro del docente autorizaci&oacute;n para tratar datos de estudiantes o acudientes, ni puede presumir que quien crea una cuenta est&aacute; facultado para cargar expedientes escolares. Antes de utilizar la plataforma con datos reales de menores, el docente debe verificar su competencia, la base legal, las autorizaciones exigibles, el deber de informaci&oacute;n, la opini&oacute;n del menor seg&uacute;n corresponda y las instrucciones de su empleador si act&uacute;a en una instituci&oacute;n.</p>
      <p>Esta pol&iacute;tica cubre los datos de cuenta tratados por la Operadora y las operaciones t&eacute;cnicas que realiza sobre los datos escolares incorporados por docentes, sin atribuir a la Operadora facultades para decidir sanciones ni para usar esos datos con fines propios o comerciales.</p>
<h2>2. Datos tratados</h2>
      <ul>
        <li><strong>Estudiantes:</strong> nombres, apellidos, número de matrícula o identificación escolar, registros de seguimiento disciplinario y convivencia, fecha del registro, observaciones sobre faltas y estímulos, y datos de identificación del docente responsable.</li>
        <li><strong>Acudientes y representantes:</strong> nombres, apellidos, parentesco o calidad de representación, teléfono, correo electrónico, dirección y comunicaciones relacionadas con el seguimiento escolar.</li>
        <li><strong>Cuenta docente:</strong> nombre, usuario, correo, rol, estado de la cuenta y datos de seguridad de acceso; y, si corresponde, datos de contacto contractual y facturación. Las contraseñas y respuestas de seguridad deben almacenarse mediante hash y utilizarse solo para autenticación y seguridad.</li>
        <li><strong>Datos técnicos:</strong> información mínima de sesión y operación que genere la aplicación o el servidor para autenticación, seguridad, diagnóstico y trazabilidad, cuando efectivamente se registre.</li>
      </ul>
      <p>Los registros disciplinarios son información personal de carácter reservado para los fines escolares del sistema. No toda anotación disciplinaria constituye por sí sola un dato sensible conforme a la ley; sin embargo, una observación podría contener datos sensibles —por ejemplo, de salud, discapacidad, origen étnico o vida sexual—. El personal debe evitar incluirlos si no son estrictamente necesarios y no existe fundamento jurídico y protección reforzada para tratarlos.</p>

            <h2>3. Finalidades y usos permitidos</h2>
      <ul>
        <li>Para cuentas docentes: registrar y autenticar usuarios; administrar roles y accesos; restablecer credenciales; prestar soporte; proteger, mantener y diagnosticar la aplicaci&oacute;n.</li>
        <li>Para datos escolares ingresados por el docente: facilitar la identificaci&oacute;n del estudiante, documentar seguimiento pedag&oacute;gico y de convivencia, consultar historiales, generar informes y, cuando se active esa funci&oacute;n, comunicar informaci&oacute;n pertinente al acudiente autorizado.</li>
        <li>Para seguridad y continuidad: mantener copias de respaldo y registros t&eacute;cnicos estrictamente necesarios, atender incidentes y cumplir obligaciones legales o solicitudes v&aacute;lidas de autoridad competente.</li>
      </ul>
      <p>Los datos escolares solo se procesar&aacute;n para las instrucciones y fines autorizados por la persona que sea responsable del tratamiento. No se vender&aacute;n, usar&aacute;n para publicidad, perfilamiento comercial, publicaci&oacute;n abierta, entrenamiento de modelos ni calificaci&oacute;n automatizada de estudiantes. La aplicaci&oacute;n no decide ni recomienda sanciones; las decisiones deben quedar en manos de personas competentes y respetar el procedimiento educativo aplicable.</p>
<h2>4. Reglas reforzadas para niños, niñas y adolescentes</h2>
      <p>El tratamiento de datos de menores se realizará únicamente cuando tenga una finalidad legítima vinculada con la prestación y gestión del servicio educativo, respete el interés superior y no ponga en riesgo sus derechos prevalentes. Se limitará a los datos estrictamente necesarios, con acceso restringido y circulación controlada.</p>
      <p>Cuando la autorización sea la base jurídica aplicable, se solicitará al representante legal de acuerdo con la normativa, se explicará el tratamiento de forma clara y se escuchará previamente al niño, niña o adolescente; su opinión se valorará según su madurez, autonomía y capacidad para comprender el asunto. La autorización de un acudiente no habilita usos ilimitados ni divulgar la información a terceros.</p>
      <p>Los registros deben describir hechos pertinentes de manera objetiva, respetuosa y proporcional. Se prohíbe usar la plataforma para etiquetar, exponer, humillar o discriminar a un estudiante. Las decisiones escolares que se apoyen en los registros deberán ser adoptadas por personas competentes, con garantías aplicables y consideración de la voz del estudiante.</p>

            <h2>5. Autorizaci&oacute;n y deber de informar</h2>
      <p>La Operadora informa en esta pol&iacute;tica las finalidades de los datos de cuenta del docente, sus derechos y los canales de contacto. Cuando la autorizaci&oacute;n sea la base jur&iacute;dica del tratamiento, solicitar&aacute; consentimiento previo, expreso e informado y conservar&aacute; prueba conforme a la ley. La aceptaci&oacute;n de esta pol&iacute;tica por parte del docente se refiere a los datos de su propia cuenta; no es autorizaci&oacute;n de estudiantes, acudientes o representantes.</p>
      <p>El docente que sea responsable, o la entidad que resulte responsable si el docente act&uacute;a en sus funciones, debe informar directamente a los titulares sobre el tratamiento de datos escolares y obtener y conservar las autorizaciones cuando sean exigibles. En el caso de ni&ntilde;os, ni&ntilde;as y adolescentes, deben cumplirse las reglas especiales descritas en la secci&oacute;n 4 y escucharse al menor de acuerdo con su madurez, autonom&iacute;a y capacidad para comprender el asunto.</p>
      <p>La entrega de datos sensibles es facultativa salvo que una norma disponga otra cosa. No se deben registrar datos sensibles que no sean estrictamente necesarios ni utilizarlos sin fundamento jur&iacute;dico, informaci&oacute;n expl&iacute;cita y protecci&oacute;n reforzada.</p>
      <h2>6. Circulaci&oacute;n, destinatarios y transferencias</h2>
      <p>El docente debe limitar el acceso a estudiantes y acudientes a las personas que tengan competencia y necesidad funcional. La informaci&oacute;n solo podr&aacute; compartirse con el titular, sus representantes debidamente verificados, personas autorizadas por el responsable o autoridades con competencia legal. No se permite compartir historiales por canales personales o grupos abiertos ni utilizar datos para finalidades distintas.</p>
      <p>Para operar la plataforma, la Operadora puede utilizar proveedores tecnol&oacute;gicos de alojamiento, correo, mantenimiento o soporte. Antes del uso con datos reales se deben identificar esos proveedores, las funciones que cumplen, los pa&iacute;ses desde los cuales almacenan o acceden a informaci&oacute;n y las salvaguardas contractuales y legales aplicables. La lista vigente de proveedores y ubicaciones debe publicarse o facilitarse en <strong>[enlace o canal de consulta]</strong>. No se efectuar&aacute; una transferencia o transmisi&oacute;n internacional fuera de los casos y condiciones permitidos por la legislaci&oacute;n colombiana.</p>
<h2>7. Derechos de los titulares</h2>
      <p>El titular, o quien lo represente legalmente cuando proceda, puede: conocer, actualizar y rectificar sus datos; solicitar prueba de la autorización cuando sea exigible; conocer el uso dado a sus datos; presentar consultas y reclamos; solicitar la supresión o revocar la autorización cuando sea procedente; y presentar quejas ante la Superintendencia de Industria y Comercio después de agotar el trámite ante el responsable o encargado.</p>
      <p>La supresión o revocatoria no procede cuando exista un deber legal o contractual de conservar la información, una finalidad legítima vigente u otra excepción legal. Archivar un registro en el sistema cambia su estado operativo, pero no equivale por sí mismo a borrarlo ni a suprimirlo de todas las copias o respaldos.</p>

            <h2>8. C&oacute;mo presentar consultas o reclamos</h2>
      <p>Para datos de cuenta, contacte a la Operadora en <strong>[direcci&oacute;n electr&oacute;nica o f&iacute;sica y tel&eacute;fono]</strong>. Para datos escolares, dirija la solicitud al responsable del tratamiento, que ser&aacute; el docente independiente o la entidad que determine los fines y medios seg&uacute;n las circunstancias. La Operadora recibir&aacute; y trasladar&aacute; oportunamente las solicitudes que correspondan a datos que procese por cuenta de un responsable, conforme al acuerdo aplicable. Las solicitudes presentadas en representaci&oacute;n de un menor deben acreditar la representaci&oacute;n cuando sea necesario.</p>
      <p>Los titulares pueden conocer, actualizar y rectificar sus datos; solicitar prueba de autorizaci&oacute;n cuando sea exigible; conocer el uso dado a la informaci&oacute;n; presentar consultas y reclamos; y pedir supresi&oacute;n o revocatoria cuando procedan. Las consultas se atienden en diez (10) d&iacute;as h&aacute;biles y, si se informa una demora, hasta cinco (5) d&iacute;as h&aacute;biles adicionales. Los reclamos se rigen por los plazos y etapas del art&iacute;culo 15 de la Ley 1581 de 2012. Agotado el tr&aacute;mite previo, puede acudirse ante la Superintendencia de Industria y Comercio conforme a sus competencias.</p>
      <h2>9. Conservaci&oacute;n, archivo y eliminaci&oacute;n</h2>
      <p>Los datos de cuenta se conservar&aacute;n mientras exista la cuenta y durante los plazos necesarios para atender obligaciones legales, seguridad y reclamaciones; al terminar esos plazos se suprimir&aacute;n o anonimizar&aacute;n cuando corresponda. El docente responsable define y documenta el per&iacute;odo de conservaci&oacute;n de los expedientes escolares de acuerdo con su finalidad y obligaciones. Si la Operadora act&uacute;a como encargada, conservar&aacute;, devolver&aacute; o eliminar&aacute; esos datos seg&uacute;n las instrucciones y el acuerdo aplicable, salvo deber legal de retenci&oacute;n. Archivar un registro dentro de la aplicaci&oacute;n no equivale necesariamente a borrarlo de respaldos.</p>
      <h2>10. Seguridad y confidencialidad</h2>
      <p>La Operadora debe definir e implementar medidas t&eacute;cnicas, humanas y administrativas proporcionales al riesgo, preservar la confidencialidad, controlar accesos y gestionar incidentes. El docente debe proteger sus credenciales, usar la informaci&oacute;n solo para la finalidad autorizada y comunicar incidentes a <strong>[direcci&oacute;n electr&oacute;nica o f&iacute;sica y tel&eacute;fono de privacidad/soporte]</strong>. Los contratos con proveedores t&eacute;cnicos deben restringir su acceso y uso de datos.</p>
      <p>Antes de cargar informaci&oacute;n real de estudiantes y acudientes, la Operadora debe verificar que las operaciones de consulta, modificaci&oacute;n, eliminaci&oacute;n, historial, informes y respaldos requieran autenticaci&oacute;n y apliquen autorizaci&oacute;n por docente; que los datos de un docente no sean visibles o modificables por otro; y que existan controles de respaldo, registro de incidentes y restauraci&oacute;n. La existencia de esta pol&iacute;tica no demuestra que esos controles est&eacute;n implementados.</p>
<h2>11. Cambios de esta política</h2>
      <p>Los cambios se publicarán en esta misma dirección indicando la versión y fecha de actualización. Si una modificación afecta finalidades o condiciones relevantes del tratamiento, se informará a los titulares y se solicitará una nueva autorización cuando la ley lo exija.</p>

      <h2>12. Normas de referencia</h2>
      <ul>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=4125" target="_blank" rel="noopener">Constitución Política de Colombia</a>, artículos 15 y 44.</li>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=49981" target="_blank" rel="noopener">Ley Estatutaria 1581 de 2012</a> y reglamentación compilada en el Decreto 1074 de 2015.</li>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=22106" target="_blank" rel="noopener">Ley 1098 de 2006 (Código de Infancia y Adolescencia)</a>, en especial las garantías de intimidad, interés superior y prevalencia de derechos.</li>
        <li><a href="https://www.corteconstitucional.gov.co/relatoria/2011/c-748-11.htm" target="_blank" rel="noopener">Corte Constitucional, Sentencia C-748 de 2011</a>, sobre la constitucionalidad y aplicación de la Ley 1581, incluidos los datos de niños, niñas y adolescentes.</li>
        <li><a href="https://www.sic.gov.co/tema/proteccion-de-datos-personales" target="_blank" rel="noopener">Superintendencia de Industria y Comercio: protección de datos personales</a>.</li>
      </ul>
      <p class="privacy-pending mt-4 mb-0"><strong>Pendiente antes de tratar datos reales:</strong> completar nombre legal, identificaci&oacute;n y canales de la creadora; identificar alojamiento, correo y subencargados con pa&iacute;ses; establecer plazos de conservaci&oacute;n, exportaci&oacute;n y borrado; documentar si cada docente act&uacute;a independientemente o en funciones para una entidad; formalizar acuerdos de encargo/transmisi&oacute;n cuando sean necesarios; y verificar controles t&eacute;cnicos de autenticaci&oacute;n, autorizaci&oacute;n, aislamiento de datos entre docentes, respaldos y respuesta a incidentes. La publicaci&oacute;n de esta pol&iacute;tica no acredita que esas medidas t&eacute;cnicas o contractuales ya existan. Este borrador debe revisarse y aprobarse para la operaci&oacute;n concreta.</p>
    </article>
  </main>
</body>
</html>
