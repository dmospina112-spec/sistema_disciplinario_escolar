<?php
declare(strict_types=1);

require_once __DIR__ . '/app/backend/config.php';
require_once __DIR__ . '/app/backend/session.php';
require_once __DIR__ . '/app/backend/contact-mailer.php';

ini_set('display_errors', '0');
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');

function contactResponse(int $status, string $message): never
{
    http_response_code($status);
    echo json_encode(['success' => $status >= 200 && $status < 300, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    loadLocalEnv();
    ensureAppSessionStarted();
} catch (Throwable $exception) {
    contactResponse(500, 'No fue posible iniciar el servicio. Inténtalo nuevamente más tarde.');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    contactResponse(405, 'Envía el formulario para continuar.');
}

$submittedToken = (string) ($_POST['csrf_token'] ?? '');
$sessionToken = (string) ($_SESSION['comportate_contact_csrf'] ?? '');
if ($sessionToken === '' || $submittedToken === '' || !hash_equals($sessionToken, $submittedToken)) {
    contactResponse(403, 'La sesión del formulario venció. Recarga la página e inténtalo nuevamente.');
}
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    contactResponse(200, '');
}
if (time() - (int) ($_SESSION['comportate_contact_started'] ?? time()) < 2) {
    contactResponse(429, 'Espera un momento y vuelve a enviar el formulario.');
}
if ((int) ($_SESSION['comportate_contact_last_sent'] ?? 0) > time() - 60) {
    contactResponse(429, 'Ya recibimos una solicitud recientemente. Espera un minuto antes de volver a enviar.');
}

$name = trim((string) ($_POST['full_name'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$plan = trim((string) ($_POST['plan'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$acceptedPrivacy = (string) ($_POST['privacy'] ?? '') === '1';
$promotionActive = in_array((int) (new DateTimeImmutable('now', new DateTimeZone('America/Bogota')))->format('n'), [1, 2], true);
$plans = [
    'mensual' => 'Suscripción mensual — $15.000 COP por mes',
    'anual' => 'Suscripción anual “Pague 10, lleve 12” — $150.000 COP por 12 meses',
    'todos' => 'Deseo recibir información sobre todos los planes',
];
if ($promotionActive) $plans['promocion'] = 'Promoción de temporada — $12.000 COP el primer mes; siguientes mensualidades a $15.000 COP';

$hasHeaderBreak = static fn(string $value): bool => preg_match('/[\r\n]/', $value) === 1;
if ($name === '' || mb_strlen($name, 'UTF-8') > 120 || $hasHeaderBreak($name)
    || $phone === '' || mb_strlen($phone, 'UTF-8') > 40 || $hasHeaderBreak($phone)
    || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 254 || $hasHeaderBreak($email)
    || !isset($plans[$plan]) || $message === '' || mb_strlen($message, 'UTF-8') > 3000 || !$acceptedPrivacy) {
    contactResponse(422, 'Revisa los campos obligatorios, el formato de correo y la autorización de tratamiento de datos.');
}

$safeName = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $name) ?? $name;
$safePhone = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $phone) ?? $phone;
$safeMessage = preg_replace('/\x00/', '', $message) ?? $message;
$receivedAt = (new DateTimeImmutable('now', new DateTimeZone('America/Bogota')))->format('Y-m-d H:i:s T');
$contactRecipient = 'comportateweb@gmail.com';
$contactBody = "Nueva solicitud de información — COMPORTATE\n\n"
    . "Nombre completo: {$safeName}\nTeléfono: {$safePhone}\nCorreo electrónico: {$email}\n"
    . "Plan de interés: {$plans[$plan]}\nFecha de recepción: {$receivedAt}\n\nMensaje:\n{$safeMessage}\n\n"
    . "La persona autorizó el tratamiento de sus datos para atender esta solicitud.";

try {
    comportateSendSmtp($contactRecipient, $email, 'Nueva solicitud de información — COMPORTATE', $contactBody);
    $_SESSION['comportate_contact_last_sent'] = time();
    $confirmation = "Hola {$safeName},\n\nGracias por contactar a COMPORTATE. Recibimos tu solicitud de información sobre: {$plans[$plan]}. Este formulario no crea una cuenta de usuario. La administradora revisará tu solicitud y se pondrá en contacto contigo.\n\nCOMPORTATE\ncomportateweb@gmail.com\n1212121212";
    try {
        comportateSendSmtp($email, $contactRecipient, 'Recibimos tu solicitud — COMPORTATE', $confirmation);
    } catch (Throwable $confirmationError) {
        error_log('COMPORTATE: el correo de confirmación no pudo enviarse.');
    }
    contactResponse(200, '¡Gracias por contactar a COMPORTATE! Tu solicitud ha sido enviada correctamente. Nos pondremos en contacto contigo.');
} catch (Throwable $exception) {
    error_log('COMPORTATE: falló el envío del formulario de contacto.');
    contactResponse(502, 'No fue posible enviar tu solicitud por correo. Tus datos siguen en el formulario; inténtalo de nuevo más tarde.');
}
