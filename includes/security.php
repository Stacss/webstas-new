<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name('webstas_session');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function csrf_token(): string
{
    start_secure_session();
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool
{
    start_secure_session();
    return isset($_SESSION['csrf_token']) && is_string($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function altcha_secret(): string
{
    $secret = env_value('ALTCHA_HMAC_KEY');
    if (strlen($secret) < 32) {
        throw new RuntimeException('ALTCHA_HMAC_KEY must contain at least 32 characters.');
    }
    return $secret;
}

function create_altcha_challenge(int $maxNumber = 12000): array
{
    $expires = time() + 600;
    $nonce = bin2hex(random_bytes(16));
    $salt = $nonce . '?expires=' . $expires;
    $number = random_int(2500, $maxNumber);
    $challenge = hash('sha256', $salt . $number);
    $signature = hash_hmac('sha256', $challenge, altcha_secret());
    return ['algorithm' => 'SHA-256', 'challenge' => $challenge, 'maxnumber' => $maxNumber, 'salt' => $salt, 'signature' => $signature];
}

function verify_altcha_payload(string $encoded): bool
{
    if ($encoded === '' || strlen($encoded) > 4096) {
        return false;
    }
    $decoded = base64_decode($encoded, true);
    if ($decoded === false) {
        return false;
    }
    $payload = json_decode($decoded, true);
    if (!is_array($payload)) {
        return false;
    }
    foreach (['algorithm', 'challenge', 'number', 'salt', 'signature'] as $key) {
        if (!array_key_exists($key, $payload)) {
            return false;
        }
    }
    if ($payload['algorithm'] !== 'SHA-256' || !is_int($payload['number']) || $payload['number'] < 0 || $payload['number'] > 12000) {
        return false;
    }
    if (!is_string($payload['salt']) || !is_string($payload['challenge']) || !is_string($payload['signature'])) {
        return false;
    }
    if (!preg_match('/\?expires=(\d+)$/', $payload['salt'], $match) || (int)$match[1] < time()) {
        return false;
    }
    $expectedChallenge = hash('sha256', $payload['salt'] . $payload['number']);
    $expectedSignature = hash_hmac('sha256', $payload['challenge'], altcha_secret());
    if (!hash_equals($expectedChallenge, $payload['challenge']) || !hash_equals($expectedSignature, $payload['signature'])) {
        return false;
    }
    $fingerprint = hash('sha256', $payload['signature']);
    $usedDirectory = env_value('USED_CHALLENGE_DIR', dirname(__DIR__) . '/storage/used-challenges');
    if (!is_dir($usedDirectory) && !mkdir($usedDirectory, 0700, true) && !is_dir($usedDirectory)) {
        return false;
    }
    prune_security_files($usedDirectory, 1800);
    $usedPath = rtrim($usedDirectory, '/') . '/' . $fingerprint;
    $handle = @fopen($usedPath, 'x');
    if ($handle === false) return false;
    fwrite($handle, (string)time());
    fclose($handle);
    @chmod($usedPath, 0600);
    return true;
}

function client_ip(): string
{
    return substr((string)($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 0, 64);
}

function rate_limit_allows(string $scope, int $limit = 5, int $window = 900): bool
{
    $directory = env_value('RATE_LIMIT_DIR', dirname(__DIR__) . '/storage/rate-limit');
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        return false;
    }
    prune_security_files($directory, max(3600, $window * 2));
    $key = hash_hmac('sha256', $scope . '|' . client_ip(), altcha_secret());
    $path = rtrim($directory, '/') . '/' . $key . '.json';
    $handle = fopen($path, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) fclose($handle);
        return false;
    }
    $raw = stream_get_contents($handle);
    $attempts = json_decode($raw ?: '[]', true);
    $attempts = array_values(array_filter(is_array($attempts) ? $attempts : [], static fn($value): bool => is_int($value) && $value > time() - $window));
    $allowed = count($attempts) < $limit;
    $attempts[] = time();
    rewind($handle);
    ftruncate($handle, 0);
    fwrite($handle, json_encode($attempts));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    @chmod($path, 0600);
    return $allowed;
}

function prune_security_files(string $directory, int $maxAge): void
{
    if (random_int(1, 100) !== 1) return;
    foreach (glob(rtrim($directory, '/') . '/*') ?: [] as $file) {
        if (is_file($file) && filemtime($file) !== false && filemtime($file) < time() - $maxAge) @unlink($file);
    }
}

function clean_line(string $value, int $max): string
{
    $value = trim(preg_replace('/[\r\n\0]+/u', ' ', $value) ?? '');
    return mb_substr($value, 0, $max);
}
