<?php
declare(strict_types=1);

putenv('ALTCHA_HMAC_KEY=test-only-key-which-is-at-least-32-characters-long');
$temp = sys_get_temp_dir() . '/webstas-tests-' . bin2hex(random_bytes(5));
mkdir($temp, 0700, true);
putenv('RATE_LIMIT_DIR=' . $temp . '/rate');
putenv('USED_CHALLENGE_DIR=' . $temp . '/used');
require dirname(__DIR__) . '/includes/security.php';

$tests = 0;
function check(bool $condition, string $message): void
{
    global $tests;
    $tests++;
    if (!$condition) throw new RuntimeException('FAIL: ' . $message);
}

$challenge = create_altcha_challenge(3000);
check($challenge['algorithm'] === 'SHA-256', 'challenge algorithm');
check($challenge['maxnumber'] === 3000, 'challenge upper bound');
$solution = null;
for ($number = 0; $number <= 3000; $number++) {
    if (hash('sha256', $challenge['salt'] . $number) === $challenge['challenge']) { $solution = $number; break; }
}
check(is_int($solution), 'challenge can be solved');
$payload = base64_encode(json_encode([
    'algorithm' => $challenge['algorithm'], 'challenge' => $challenge['challenge'], 'number' => $solution,
    'salt' => $challenge['salt'], 'signature' => $challenge['signature'],
], JSON_THROW_ON_ERROR));
check(verify_altcha_payload($payload), 'valid ALTCHA solution passes');
check(!verify_altcha_payload($payload), 'ALTCHA replay is rejected');

$tampered = json_decode(base64_decode($payload), true, 512, JSON_THROW_ON_ERROR);
$tampered['number']++;
check(!verify_altcha_payload(base64_encode(json_encode($tampered, JSON_THROW_ON_ERROR))), 'tampered ALTCHA fails');
check(clean_line("Subject\r\nBcc: victim@example.com", 80) === 'Subject Bcc: victim@example.com', 'header newlines removed');
check(rate_limit_allows('test', 2, 60), 'rate attempt one');
check(rate_limit_allows('test', 2, 60), 'rate attempt two');
check(!rate_limit_allows('test', 2, 60), 'rate attempt three blocked');

echo "OK: {$tests} backend checks passed\n";
