<?php
declare(strict_types=1);

function smtp_read($socket, array $expected): string
{
    $response = '';
    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (strlen($line) < 4 || $line[3] === ' ') break;
    }
    $code = (int)substr($response, 0, 3);
    if (!in_array($code, $expected, true)) {
        throw new RuntimeException('SMTP rejected a command with code ' . $code);
    }
    return $response;
}

function smtp_command($socket, string $command, array $expected): string
{
    if (str_contains($command, "\r") || str_contains($command, "\n")) {
        throw new RuntimeException('Invalid SMTP command.');
    }
    fwrite($socket, $command . "\r\n");
    return smtp_read($socket, $expected);
}

function send_smtp_mail(string $replyContact, string $name, string $message): void
{
    $host = env_value('SMTP_HOST');
    $port = (int)env_value('SMTP_PORT', '587');
    $encryption = strtolower(env_value('SMTP_ENCRYPTION', 'tls'));
    $username = env_value('SMTP_USERNAME');
    $passwordValue = getenv('SMTP_PASSWORD');
    $password = $passwordValue === false ? '' : $passwordValue;
    $from = env_value('SMTP_FROM', CONTACT_EMAIL);
    $to = env_value('FORM_TO_EMAIL', CONTACT_EMAIL);
    if ($host === '' || $username === '' || $password === '' || !filter_var($from, FILTER_VALIDATE_EMAIL) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('SMTP configuration is incomplete.');
    }
    $transport = $encryption === 'ssl' ? 'ssl://' : '';
    $context = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'peer_name' => $host, 'SNI_enabled' => true]]);
    $socket = stream_socket_client($transport . $host . ':' . $port, $errno, $error, 12, STREAM_CLIENT_CONNECT, $context);
    if ($socket === false) throw new RuntimeException('SMTP connection failed.');
    stream_set_timeout($socket, 12);
    smtp_read($socket, [220]);
    $serverName = preg_replace('/[^a-z0-9.-]/i', '', (string)($_SERVER['SERVER_NAME'] ?? 'webstas.ru')) ?: 'webstas.ru';
    smtp_command($socket, 'EHLO ' . $serverName, [250]);
    if ($encryption === 'tls') {
        smtp_command($socket, 'STARTTLS', [220]);
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) throw new RuntimeException('SMTP TLS failed.');
        smtp_command($socket, 'EHLO ' . $serverName, [250]);
    }
    smtp_command($socket, 'AUTH LOGIN', [334]);
    smtp_command($socket, base64_encode($username), [334]);
    smtp_command($socket, base64_encode($password), [235]);
    smtp_command($socket, 'MAIL FROM:<' . $from . '>', [250]);
    smtp_command($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
    smtp_command($socket, 'DATA', [354]);
    $subject = '=?UTF-8?B?' . base64_encode('Новая заявка с webstas.ru') . '?=';
    $body = "Новая заявка с сайта webstas.ru\n\nИмя: {$name}\nКонтакт: {$replyContact}\n\nСообщение:\n{$message}\n\nДата: " . date('c');
    $headers = [
        'Date: ' . date(DATE_RFC2822), 'From: Webstas site <' . $from . '>', 'To: <' . $to . '>',
        'Subject: ' . $subject, 'MIME-Version: 1.0', 'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64', 'X-Mailer: WebstasForm/1.0',
    ];
    $data = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($body), 76, "\r\n");
    $data = preg_replace('/(?m)^\./', '..', $data) ?? $data;
    fwrite($socket, $data . "\r\n.\r\n");
    smtp_read($socket, [250]);
    smtp_command($socket, 'QUIT', [221]);
    fclose($socket);
}
