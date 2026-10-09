<?php
declare(strict_types=1);

function comportateMailConfig(): array
{
    $config = [
        'from' => trim((string) (getenv('MAIL_FROM') ?: '')),
        'from_name' => trim((string) (getenv('MAIL_FROM_NAME') ?: 'COMPORTATE')),
        'host' => trim((string) (getenv('SMTP_HOST') ?: '')),
        'port' => (int) (getenv('SMTP_PORT') ?: 0),
        'username' => trim((string) (getenv('SMTP_USERNAME') ?: '')),
        'password' => (string) (getenv('SMTP_PASSWORD') ?: ''),
        'encryption' => strtolower(trim((string) (getenv('SMTP_ENCRYPTION') ?: 'tls'))),
        'timeout' => max(5, min(60, (int) (getenv('SMTP_TIMEOUT') ?: 15))),
    ];
    if (!filter_var($config['from'], FILTER_VALIDATE_EMAIL) || $config['host'] === '' || $config['port'] < 1
        || $config['username'] === '' || $config['password'] === '' || !in_array($config['encryption'], ['tls', 'ssl'], true)) {
        throw new RuntimeException('El envío de correo no está configurado completamente.');
    }
    return $config;
}

function comportateSmtpResponse($socket): string
{
    $response = '';
    while (!feof($socket)) {
        $line = fgets($socket, 515);
        if ($line === false) break;
        $response .= $line;
        if (preg_match('/^\d{3} /', $line) === 1) break;
    }
    $meta = stream_get_meta_data($socket);
    if (($meta['timed_out'] ?? false) || trim($response) === '') {
        throw new RuntimeException('El servidor de correo no respondió.');
    }
    return trim($response);
}

function comportateSmtpCommand($socket, string $command, array $codes, string $step): string
{
    if (fwrite($socket, $command . "\r\n") === false) throw new RuntimeException('Falló un comando SMTP.');
    $response = comportateSmtpResponse($socket);
    if (!in_array((int) substr($response, 0, 3), $codes, true)) {
        throw new RuntimeException('El servidor SMTP rechazó el paso: ' . $step . ' (' . substr($response, 0, 180) . ')');
    }
    return $response;
}

function comportateSendSmtp(string $to, string $replyTo, string $subject, string $body): void
{
    $config = comportateMailConfig();
    $context = stream_context_create(['ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
        'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT,
    ]]);
    $transport = $config['encryption'] === 'ssl' ? 'ssl://' : 'tcp://';
    $socket = @stream_socket_client($transport . $config['host'] . ':' . $config['port'], $errno, $error, $config['timeout'], STREAM_CLIENT_CONNECT, $context);
    if (!$socket) throw new RuntimeException('No fue posible conectar con el servidor SMTP.');
    stream_set_timeout($socket, $config['timeout']);

    try {
        $greeting = comportateSmtpResponse($socket);
        if ((int) substr($greeting, 0, 3) !== 220) throw new RuntimeException('El servidor SMTP rechazó la conexión inicial.');
        $helo = preg_replace('/[^a-zA-Z0-9.-]/', '', (string) (gethostname() ?: 'localhost')) ?: 'localhost';
        comportateSmtpCommand($socket, 'EHLO ' . $helo, [250], 'EHLO');
        if ($config['encryption'] === 'tls') {
            comportateSmtpCommand($socket, 'STARTTLS', [220], 'STARTTLS');
            if (@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
                throw new RuntimeException('No se pudo establecer una conexión TLS segura.');
            }
            comportateSmtpCommand($socket, 'EHLO ' . $helo, [250], 'EHLO después de TLS');
        }
        comportateSmtpCommand($socket, 'AUTH LOGIN', [334], 'autenticación');
        comportateSmtpCommand($socket, base64_encode($config['username']), [334], 'usuario SMTP');
        comportateSmtpCommand($socket, base64_encode($config['password']), [235], 'clave SMTP');
        comportateSmtpCommand($socket, 'MAIL FROM:<' . $config['from'] . '>', [250], 'remitente');
        comportateSmtpCommand($socket, 'RCPT TO:<' . $to . '>', [250, 251], 'destinatario');
        comportateSmtpCommand($socket, 'DATA', [354], 'contenido');

        $safeName = 'COMPORTATE';
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: ' . '=?UTF-8?B?' . base64_encode($safeName) . '?= <' . $config['from'] . '>',
            'To: <' . $to . '>',
            'Reply-To: <' . $replyTo . '>',
            'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
            'X-Mailer: COMPORTATE contact form',
        ];
        $encodedBody = rtrim(chunk_split(base64_encode(str_replace(["\r\n", "\r"], "\n", $body)), 76, "\r\n"));
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $encodedBody;
        $message = preg_replace('/(?m)^\./', '..', $message) ?? $message;
        if (fwrite($socket, $message . "\r\n.\r\n") === false) throw new RuntimeException('No fue posible enviar el mensaje SMTP.');
        $accepted = comportateSmtpResponse($socket);
        if ((int) substr($accepted, 0, 3) !== 250) throw new RuntimeException('El servidor SMTP no aceptó el mensaje.');
        comportateSmtpCommand($socket, 'QUIT', [221], 'cierre');
    } finally {
        fclose($socket);
    }
}
