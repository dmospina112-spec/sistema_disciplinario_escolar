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
  <title>T&eacute;rminos y Condiciones de uso</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/css/styles.css?v=<?php echo htmlspecialchars($stylesVersion, ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    .legal-shell{width:min(980px,calc(100% - 2rem));margin:2.5rem auto}
    .legal-document{padding:clamp(1.25rem,4vw,3.25rem);border:1px solid rgba(148,163,184,.28);border-radius:1.5rem;background:rgba(255,255,255,.96);box-shadow:0 18px 48px rgba(15,23,42,.12)}
    .legal-document h1{color:#1d4f91;font-size:clamp(2rem,5vw,3rem);line-height:1.08}
    .legal-document h2{margin-top:2rem;color:#1d4f91;font-size:1.15rem;font-weight:800}
    .legal-document p,.legal-document li{color:#334155;line-height:1.75}
    .legal-meta{color:#64748b;font-size:.9rem}
    .legal-pending{padding:1rem 1.15rem;border-left:4px solid #b7791f;border-radius:.5rem;background:#fffbeb;color:#713f12}
    @media print{body.site-body{display:block;background:#fff}body.site-body::before,body.site-body::after,.legal-back,.site-footer{display:none!important}.legal-shell{width:100%;margin:0}.legal-document{padding:0;border:0;box-shadow:none}}
  </style>
</head>
<body class="site-body">
  <main class="legal-shell">
    <a class="btn btn-outline-primary legal-back mb-3" href="index.php">&larr; Volver al registro</a>
    <article class="legal-document">
      <header class="mb-4">
        <p class="text-uppercase fw-bold small text-primary mb-2">Condiciones de uso de la plataforma</p>
        <h1>T&eacute;rminos y Condiciones</h1>
        <p class="legal-meta mb-1">Sistema de seguimiento disciplinario y convivencia escolar</p>
        <p class="legal-meta">Versi&oacute;n 1.0 &middot; Borrador para revisi&oacute;n y aprobaci&oacute;n institucional: <strong>[fecha de aprobaci&oacute;n]</strong></p>
      </header>

      <div class="legal-pending mb-4"><strong>Documento de trabajo pendiente de completar:</strong> la creadora ofrece la aplicaci&oacute;n directamente a docentes, quienes registran sus propias cuentas. No existe una instituci&oacute;n que administre las cuentas de la plataforma. Antes de ofrecerla para datos escolares reales, complete la identidad y contacto de la creadora, determine las funciones de cada parte sobre los datos de estudiantes y acudientes, confirme la infraestructura y formalice los acuerdos que correspondan.</div>

            <h2>1. Qui&eacute;n ofrece el servicio y aceptaci&oacute;n</h2>
      <p>La aplicaci&oacute;n es creada y ofrecida directamente a docentes por <strong>[nombre legal completo de la creadora o empresa]</strong>, identificada con <strong>[c&eacute;dula o NIT]</strong>, con direcci&oacute;n de contacto <strong>[direcci&oacute;n electr&oacute;nica o f&iacute;sica]</strong> y tel&eacute;fono <strong>[n&uacute;mero de contacto]</strong> (la “Operadora”). Cada cuenta pertenece al docente que la solicita. Ninguna instituci&oacute;n p&uacute;blica o privada administra las cuentas de esta plataforma. Al marcar la casilla correspondiente y crear la cuenta, el docente declara haber le&iacute;do y acepta estos T&eacute;rminos para su uso personal del servicio.</p>
      <p>La aceptaci&oacute;n no crea una relaci&oacute;n laboral, mandato ni representaci&oacute;n entre la Operadora y el docente o una instituci&oacute;n. Tampoco concede al docente facultades para tratar registros escolares que no est&eacute; autorizado a usar. Si el docente incorpora informaci&oacute;n obtenida en su trabajo o en una instituci&oacute;n, debe tener autorizaci&oacute;n previa para utilizar este servicio externo y respetar las instrucciones y reglas de esa entidad. El hecho de que una instituci&oacute;n no administre la cuenta no descarta que sea responsable de los datos tratados por el docente en sus funciones.</p>
<h2>2. Objeto y alcance del servicio</h2>
      <p>La plataforma permite administrar cuentas, estudiantes y datos de acudientes; registrar y consultar observaciones de seguimiento escolar, convivencia, faltas y est&iacute;mulos; consultar historiales; generar informes; y facilitar comunicaciones con acudientes cuando la funci&oacute;n est&eacute; habilitada. Sus funciones concretas pueden cambiar por mantenimiento o actualizaciones, sin alterar derechos legales de los titulares.</p>
      <p>La herramienta es un apoyo de registro y comunicaci&oacute;n. No eval&uacute;a autom&aacute;ticamente a los estudiantes, no determina la responsabilidad de una conducta, no impone sanciones y no reemplaza el manual de convivencia, la valoraci&oacute;n profesional, las competencias de quien resulte responsable ni el debido proceso aplicable.</p>

            <h2>3. Cuenta del docente y seguridad</h2>
      <ul>
        <li>El usuario debe proporcionar datos de cuenta correctos y mantenerlos actualizados. Cada cuenta es individual y no debe prestarse ni compartirse.</li>
        <li>El docente debe mantener su contrase&ntilde;a en reserva, cerrar sesi&oacute;n en equipos compartidos y avisar a la Operadora en <strong>[correo/tel&eacute;fono de soporte]</strong> ante p&eacute;rdida de acceso, uso sospechoso o incidente.</li>
        <li>La Operadora puede administrar roles y accesos necesarios para la operaci&oacute;n del servicio. Ning&uacute;n permiso de la plataforma ampl&iacute;a las facultades legales o laborales del docente sobre datos escolares.</li>
        <li>La Operadora puede suspender una cuenta por seguridad, incumplimiento de estos t&eacute;rminos, solicitud legal v&aacute;lida o necesidad operativa, informando el motivo cuando sea posible y respetando los derechos aplicables.</li>
      </ul>
<h2>4. Uso permitido y calidad de los registros</h2>
      <p>La plataforma debe emplearse exclusivamente para fines educativos e institucionales leg&iacute;timos. Los registros deben ser pertinentes, exactos, verificables, respetuosos, proporcionales y limitarse a hechos necesarios. El docente debe distinguir hechos observados de opiniones, evitar lenguaje ofensivo, estigmatizante o discriminatorio y corregir errores conforme al procedimiento de la Instituci&oacute;n.</p>
      <p>Est&aacute; prohibido utilizar el sistema para divulgar informaci&oacute;n fuera de las personas autorizadas, consultar expedientes por curiosidad, alterar o borrar registros sin competencia, cargar datos excesivos o ajenos a la finalidad escolar, acosar o discriminar, eludir controles de acceso, introducir c&oacute;digo malicioso o realizar actividades contrarias a la ley, al manual de convivencia o a instrucciones institucionales.</p>

            <h2>5. Protecci&oacute;n reforzada de ni&ntilde;os, ni&ntilde;as y adolescentes</h2>
      <p>Los datos de menores requieren protecci&oacute;n reforzada. Su tratamiento solo es admisible cuando respeta el inter&eacute;s superior, asegura sus derechos fundamentales y cumple la legislaci&oacute;n colombiana. El menor debe ser escuchado y su opini&oacute;n valorada de acuerdo con edad, madurez, autonom&iacute;a y capacidad para comprender el asunto.</p>
      <p>La aceptaci&oacute;n de estos t&eacute;rminos por el docente <strong>no es autorizaci&oacute;n de los estudiantes, padres, madres o representantes</strong>. El docente que determine fines y medios del tratamiento debe informar a los titulares, identificar la base jur&iacute;dica y obtener y conservar las autorizaciones cuando sean exigibles. Si los datos se tratan dentro de las funciones del docente para una instituci&oacute;n, esa entidad puede ser responsable aunque no administre esta aplicaci&oacute;n; el docente debe tener autorizaci&oacute;n para cargar su informaci&oacute;n en un servicio externo.</p>
      <p>Los registros deben ser pertinentes, objetivos y respetuosos. No deben incluirse datos sensibles (como datos de salud, discapacidad, origen &eacute;tnico o vida sexual) salvo necesidad estricta, fundamento jur&iacute;dico y medidas reforzadas. No use la plataforma para etiquetar, exponer, humillar o discriminar estudiantes.</p>
      <h2>6. Datos personales, privacidad y comunicaciones</h2>
      <p>El tratamiento se describe en la <a href="politica_privacidad.php">Pol&iacute;tica de Tratamiento de Datos Personales</a>. La Operadora trata los datos de cuenta para administrar y proteger el servicio. Para datos escolares, sus roles dependen de qui&eacute;n determine realmente las finalidades y medios: la Operadora solo act&uacute;a como encargada si trata los datos exclusivamente por cuenta e instrucciones documentadas de un responsable. El docente debe leer la pol&iacute;tica y, antes de cargar datos, asegurarse de contar con autoridad para hacerlo.</p>
      <p>La funci&oacute;n para enviar notificaciones a acudientes solo debe usarse por una persona autorizada, con destinatarios cuya identidad y relaci&oacute;n se hayan verificado, y tras revisar el contenido. No transmita informaci&oacute;n escolar por medios personales u otros canales no autorizados. Un registro de env&iacute;o no prueba que el destinatario haya recibido o le&iacute;do el mensaje.</p>
<h2>7. Responsabilidades de la Operadora y del docente</h2>
      <ul>
        <li>La Operadora responde por los datos de registro que trata para administrar cuentas y prestar el servicio. Para datos escolares, su rol depende de la operaci&oacute;n real: solo ser&aacute; encargada cuando trate informaci&oacute;n por cuenta e instrucciones documentadas del responsable y no determine fines propios.</li>
        <li>El docente debe usar la aplicaci&oacute;n con datos cuya inclusi&oacute;n est&eacute; legalmente habilitada; definir o confirmar qui&eacute;n es responsable del tratamiento; contar con las autorizaciones y avisos requeridos; respetar el inter&eacute;s superior, la intimidad y los derechos de los estudiantes; y no ingresar datos sensibles innecesarios.</li>
        <li>Si el docente act&uacute;a en funciones para una instituci&oacute;n educativa, debe obtener autorizaci&oacute;n de esa entidad para usar una plataforma externa y seguir sus instrucciones. La ausencia de un administrador institucional en la aplicaci&oacute;n no reemplaza esa autorizaci&oacute;n.</li>
        <li>El docente debe limitar el acceso a la informaci&oacute;n, verificar a los acudientes antes de enviarles datos, proteger sus credenciales y reportar incidentes a <strong>[correo de soporte]</strong>. No debe compartir historiales por canales abiertos ni usar los datos para prop&oacute;sitos ajenos al seguimiento escolar autorizado.</li>
      </ul>
      <h2>8. Conservaci&oacute;n, exportaci&oacute;n y cierre de cuentas</h2>
      <p>Los datos de cuenta se conservan mientras la cuenta est&eacute; activa y durante los plazos necesarios para cumplir obligaciones legales, proteger el servicio y atender reclamaciones. El docente que sea responsable de datos escolares debe definir y aplicar los plazos correspondientes a la finalidad y a las obligaciones legales o laborales aplicables. Si la Operadora act&uacute;a como encargada, devolver&aacute; o eliminar&aacute; esos datos seg&uacute;n las instrucciones y el acuerdo aplicable, salvo deber legal de conservaci&oacute;n. Cerrar una cuenta no garantiza por s&iacute; solo el borrado inmediato de respaldos o archivos sujetos a retenci&oacute;n.</p>
<h2>9. Disponibilidad, soporte y cambios</h2>
      <p>La Operadora procurar&aacute; mantener disponible la plataforma y realizar mantenimiento con medidas razonables para reducir interrupciones. No se promete disponibilidad ininterrumpida; se informar&aacute;n mantenimientos o interrupciones relevantes por los canales disponibles. Para soporte, reportes de seguridad o privacidad, use <strong>[correo de contacto y tel&eacute;fono de soporte]</strong>. Las actualizaciones de estos t&eacute;rminos indicar&aacute;n versi&oacute;n y fecha. Si un cambio requiere nueva aceptaci&oacute;n, se solicitar&aacute; antes de continuar con el uso afectado.</p>

            <h2>10. Propiedad intelectual y uso de contenidos</h2>
      <p>El software, dise&ntilde;o y materiales de la plataforma pertenecen a la Operadora o a sus licenciantes. Se concede al docente una autorizaci&oacute;n limitada, personal, revocable y no exclusiva para utilizarla conforme a estos t&eacute;rminos. El acceso no transfiere derechos de propiedad intelectual. El docente conserva los derechos sobre contenidos de los que sea titular; su tratamiento dentro de la aplicaci&oacute;n queda sujeto a la Pol&iacute;tica de Tratamiento y al rol que corresponda a cada parte.</p>
      <h2>11. Incumplimientos y terminaci&oacute;n</h2>
      <p>Ante un incumplimiento o incidente, la Operadora puede restringir temporalmente el acceso para proteger cuentas y datos, investigar y, cuando corresponda, terminar el acceso al servicio. La medida debe ser proporcional y no elimina derechos legales del usuario ni de los titulares de datos. El docente debe reportar sin demora cualquier acceso, divulgaci&oacute;n, p&eacute;rdida o modificaci&oacute;n no autorizada.</p>
<h2>12. Ley aplicable y canales de contacto</h2>
      <p>Estas condiciones se interpretan conforme a la Constituci&oacute;n Pol&iacute;tica y las leyes de la Rep&uacute;blica de Colombia. Para solicitudes sobre cuentas y el servicio, contacte a la Operadora: <strong>[nombre legal, direcci&oacute;n electr&oacute;nica o f&iacute;sica y tel&eacute;fono de la creadora]</strong>. Para solicitudes sobre los datos escolares incorporados, el docente debe facilitar los datos de contacto del responsable que determine finalidades y medios, y atender las solicitudes que correspondan a su rol legal.</p>
<h2>13. Referencias normativas</h2>
      <ul>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=4125" target="_blank" rel="noopener">Constituci&oacute;n Pol&iacute;tica de Colombia</a>, art&iacute;culos 15, 44 y 67.</li>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=49981" target="_blank" rel="noopener">Ley Estatutaria 1581 de 2012</a>, protecci&oacute;n de datos personales.</li>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=76608" target="_blank" rel="noopener">Decreto 1074 de 2015</a>, reglamentaci&oacute;n del sector Comercio, Industria y Turismo, incluido el r&eacute;gimen de protecci&oacute;n de datos.</li>
        <li><a href="https://www.funcionpublica.gov.co/eva/gestornormativo/norma.php?i=22106" target="_blank" rel="noopener">Ley 1098 de 2006</a>, C&oacute;digo de Infancia y Adolescencia.</li>
        <li><a href="https://www.corteconstitucional.gov.co/relatoria/2011/c-748-11.htm" target="_blank" rel="noopener">Corte Constitucional, Sentencia C-748 de 2011</a>, control previo de constitucionalidad de la Ley 1581.</li>
      </ul>
      <p class="legal-pending mt-4 mb-0"><strong>Importante:</strong> completar los campos de identidad y contacto antes de publicar la versi&oacute;n definitiva. La aceptaci&oacute;n de estos t&eacute;rminos por un docente no reemplaza los avisos ni las autorizaciones que correspondan a los titulares de datos de menores. La Operadora y el docente deben definir y documentar sus funciones reales sobre los datos escolares, as&iacute; como las medidas de seguridad y acuerdos de tratamiento aplicables.</p>
    </article>
  </main>
</body>
</html>
