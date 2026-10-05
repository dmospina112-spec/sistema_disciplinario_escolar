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

      <div class="privacy-pending mb-4"><strong>Borrador para emprendimiento SaaS:</strong> antes de publicar como política definitiva, complete los datos legales y canales del proveedor, los datos de cada cliente responsable y los proveedores/subencargados reales. La política pública del proveedor no reemplaza el aviso y la autorización que correspondan a cada docente, institución o entidad cliente.</div>

      <h2>1. Responsable del tratamiento y alcance</h2>
      <p>La aplicación es un servicio web ofrecido por <strong>[nombre legal de la persona emprendedora o empresa]</strong>, identificado con <strong>[NIT o identificación]</strong>, domicilio en <strong>[municipio y departamento]</strong> y canal de privacidad: <strong>[correo y/o formulario]</strong> (en adelante, el “Proveedor”).</p>
      <p>Los roles dependen de quién decide realmente para qué y cómo se usan los datos. Para los datos de estudiantes, acudientes y registros escolares que un cliente incorpora en su espacio, el cliente que define esos fines —una institución/entidad educativa o, solo cuando tenga autoridad legítima, un docente independiente— actúa como responsable del tratamiento. El Proveedor actúa como encargado y procesa esos datos siguiendo instrucciones documentadas y un contrato de transmisión de datos. Cada cliente debe identificar a su responsable legal y entregar a las personas su propio aviso de privacidad y canal de derechos. Si el docente actúa en nombre de una institución, no debe cargar registros institucionales sin autorización de esta.</p>
      <p>El Proveedor es responsable de los datos que decide tratar para sus propios fines de operación, como crear y administrar cuentas de clientes, gestionar la relación contractual, prestar soporte, facturar cuando aplique y proteger la seguridad del servicio. En esos casos, el canal indicado arriba atiende consultas y reclamos. Los nombres de los roles en esta política no prevalecen sobre la forma efectiva en que cada parte determina el tratamiento.</p>
      <p>Esta política explica ambos tratamientos: los datos de cuenta gestionados por el Proveedor y los datos que los clientes cargan en la aplicación, incluidos datos de niños, niñas y adolescentes, acudientes, docentes y administradores.</p>

      <h2>2. Datos tratados</h2>
      <ul>
        <li><strong>Estudiantes:</strong> nombres, apellidos, número de matrícula o identificación escolar, registros de seguimiento disciplinario y convivencia, fecha del registro, observaciones sobre faltas y estímulos, y datos de identificación del docente responsable.</li>
        <li><strong>Acudientes y representantes:</strong> nombres, apellidos, parentesco o calidad de representación, teléfono, correo electrónico, dirección y comunicaciones relacionadas con el seguimiento escolar.</li>
        <li><strong>Cuenta del cliente/docente:</strong> nombre, usuario, correo, rol, estado de la cuenta y datos de seguridad de acceso; y, si corresponde, datos de contacto contractual y facturación. Las contraseñas y respuestas de seguridad deben almacenarse mediante hash y utilizarse solo para autenticación y seguridad.</li>
        <li><strong>Datos técnicos:</strong> información mínima de sesión y operación que genere la aplicación o el servidor para autenticación, seguridad, diagnóstico y trazabilidad, cuando efectivamente se registre.</li>
      </ul>
      <p>Los registros disciplinarios son información personal de carácter reservado para los fines escolares del sistema. No toda anotación disciplinaria constituye por sí sola un dato sensible conforme a la ley; sin embargo, una observación podría contener datos sensibles —por ejemplo, de salud, discapacidad, origen étnico o vida sexual—. El personal debe evitar incluirlos si no son estrictamente necesarios y no existe fundamento jurídico y protección reforzada para tratarlos.</p>

      <h2>3. Finalidades y usos permitidos</h2>
      <ul>
        <li>Administrar la identificación escolar necesaria para seleccionar al estudiante y consultar su historial.</li>
        <li>Registrar, consultar, corregir y conservar actuaciones de seguimiento pedagógico, convivencia, faltas y estímulos, conforme al manual de convivencia y a las competencias de la institución.</li>
        <li>Facilitar la comunicación institucional con padres, madres, acudientes o representantes respecto del estudiante a su cargo y conservar la trazabilidad de las notificaciones que el sistema registre.</li>
        <li>Crear y administrar cuentas de docentes y administradores, verificar roles y proteger el acceso a la aplicación.</li>
        <li>Generar informes internos requeridos para la gestión escolar, atención de solicitudes, cumplimiento de deberes legales y defensa de derechos.</li>
        <li>Realizar copias de seguridad, mantenimiento, soporte y gestión de incidentes de seguridad, con acceso limitado a quienes deban prestar esos servicios.</li>
      </ul>
      <p>Los datos no se usarán para publicidad, perfilamiento comercial, publicación abierta, calificación automatizada de estudiantes ni finalidades incompatibles con las aquí descritas. La aplicación es una herramienta de apoyo: no impone por sí sola sanciones ni reemplaza la valoración humana, el debido proceso escolar, el derecho de defensa ni la escucha del estudiante.</p>

      <h2>4. Reglas reforzadas para niños, niñas y adolescentes</h2>
      <p>El tratamiento de datos de menores se realizará únicamente cuando tenga una finalidad legítima vinculada con la prestación y gestión del servicio educativo, respete el interés superior y no ponga en riesgo sus derechos prevalentes. Se limitará a los datos estrictamente necesarios, con acceso restringido y circulación controlada.</p>
      <p>Cuando la autorización sea la base jurídica aplicable, se solicitará al representante legal de acuerdo con la normativa, se explicará el tratamiento de forma clara y se escuchará previamente al niño, niña o adolescente; su opinión se valorará según su madurez, autonomía y capacidad para comprender el asunto. La autorización de un acudiente no habilita usos ilimitados ni divulgar la información a terceros.</p>
      <p>Los registros deben describir hechos pertinentes de manera objetiva, respetuosa y proporcional. Se prohíbe usar la plataforma para etiquetar, exponer, humillar o discriminar a un estudiante. Las decisiones escolares que se apoyen en los registros deberán ser adoptadas por personas competentes, con garantías aplicables y consideración de la voz del estudiante.</p>

      <h2>5. Autorización y deber de informar</h2>
      <p>Antes de recolectar datos sujetos a autorización, el cliente que sea responsable debe informar de manera previa, expresa y verificable las finalidades, los derechos del titular y sus canales, y conservar prueba de la autorización cuando sea exigible. También debe establecer y documentar la base jurídica aplicable. El Proveedor no obtiene por el solo registro del docente autorización para tratar datos de estudiantes o acudientes ni sustituye las obligaciones del cliente. El silencio o la inacción no se entenderán como autorización.</p>
      <p>Si se solicitan datos sensibles, se informará expresamente su carácter, la finalidad y que responder preguntas sobre ellos es facultativo, salvo que exista una excepción legal aplicable. No se condicionará el acceso al servicio educativo a autorizar tratamientos facultativos ajenos a su prestación.</p>

      <h2>6. Circulación, destinatarios y transferencias</h2>
      <p>El acceso se limita a personal autorizado según sus funciones. La información de un estudiante solo se facilitará a su representante o acudiente después de verificar su identidad y la relación o facultad para recibirla, y a autoridades competentes cuando exista habilitación legal. Los docentes accederán únicamente a la información necesaria para su labor.</p>
      <p>El Proveedor podrá contratar servicios de alojamiento, correo, mantenimiento u otros subencargados, bajo obligaciones de confidencialidad, seguridad y tratamiento limitado. Antes de usar datos reales, publicará o comunicará a sus clientes la lista de proveedores, la finalidad del acceso y los países donde se almacenan o acceden los datos; documentará las transmisiones o transferencias internacionales y cumplirá las condiciones legales aplicables. El cliente deberá autorizar y documentar la transmisión al Proveedor mediante el contrato correspondiente.</p>

      <h2>7. Derechos de los titulares</h2>
      <p>El titular, o quien lo represente legalmente cuando proceda, puede: conocer, actualizar y rectificar sus datos; solicitar prueba de la autorización cuando sea exigible; conocer el uso dado a sus datos; presentar consultas y reclamos; solicitar la supresión o revocar la autorización cuando sea procedente; y presentar quejas ante la Superintendencia de Industria y Comercio después de agotar el trámite ante el responsable o encargado.</p>
      <p>La supresión o revocatoria no procede cuando exista un deber legal o contractual de conservar la información, una finalidad legítima vigente u otra excepción legal. Archivar un registro en el sistema cambia su estado operativo, pero no equivale por sí mismo a borrarlo ni a suprimirlo de todas las copias o respaldos.</p>

      <h2>8. Cómo presentar consultas o reclamos</h2>
      <p>Para datos de estudiantes, acudientes o registros cargados por un cliente, presente la solicitud al responsable de ese espacio usando el canal que este haya informado. El Proveedor la remitirá al cliente responsable y prestará el apoyo previsto en el contrato. Para datos de cuenta tratados por el Proveedor para sus propios fines, use el canal de privacidad de la sección 1. Incluya nombre e identificación del titular, descripción de la solicitud y contacto para responder; si actúa por otra persona, acredite representación cuando corresponda. No envíe documentos de identidad por canales no oficiales.</p>
      <ul>
        <li><strong>Consultas:</strong> se responderán dentro de diez (10) días hábiles desde su recibo. Si no es posible, se informará antes del vencimiento y la respuesta podrá tardar hasta cinco (5) días hábiles adicionales.</li>
        <li><strong>Reclamos:</strong> si están incompletos, se solicitará subsanar dentro de los cinco (5) días hábiles siguientes. Una vez completo, se responderá en un máximo de quince (15) días hábiles contados desde el día siguiente a su recibo. Si no es posible, se informarán los motivos y la respuesta podrá tardar hasta ocho (8) días hábiles adicionales.</li>
        <li>Una vez agotado el procedimiento ante el responsable o encargado, el titular podrá acudir a la Superintendencia de Industria y Comercio conforme a sus competencias.</li>
      </ul>

      <h2>9. Conservación, archivo y eliminación</h2>
      <p>El cliente responsable define cuánto tiempo conserva los expedientes escolares, atendiendo sus obligaciones y reglas de archivo. El Proveedor conserva los datos del espacio mientras presta el servicio y durante el plazo de exportación o eliminación definido en el contrato; al terminar la relación, devolverá o eliminará los datos según instrucción del cliente y los plazos técnicos informados, salvo retención legal. El Proveedor define y comunica por separado los plazos de conservación de cuentas, facturación y registros técnicos que trate para sus propios fines. Estos plazos deben quedar establecidos antes de ofrecer el servicio comercialmente.</p>

      <h2>10. Seguridad y confidencialidad</h2>
      <p>El Proveedor debe implementar medidas administrativas, humanas y técnicas proporcionales al riesgo, limitar el acceso de su personal, mantener confidencialidad y gestionar incidentes; cada cliente debe definir permisos apropiados y usar el servicio conforme a sus obligaciones. El contrato establecerá cómo se notifican y atienden incidentes. Antes de almacenar información real de varios clientes, el servicio debe separar y restringir técnicamente los espacios de cada cliente en todas las consultas, operaciones y respaldos; la publicación de esta política por sí sola no acredita que esa separación exista.</p>

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
      <p class="privacy-pending mt-4 mb-0"><strong>Pendiente antes del lanzamiento comercial:</strong> completar la identidad legal y contacto del Proveedor; preparar contrato de transmisión/encargo y condiciones del servicio; identificar alojamiento, subencargados y países; fijar retención, exportación y borrado; proporcionar a cada cliente un aviso y canales para titulares; y habilitar aislamiento comprobable entre clientes, control de acceso, respaldos y atención de incidentes. No cargar datos reales de menores hasta contar con autoridad e instrucciones del responsable y completar esas medidas. Este documento es un borrador informativo y no sustituye asesoría jurídica para la operación concreta.</p>
    </article>
  </main>
</body>
</html>
