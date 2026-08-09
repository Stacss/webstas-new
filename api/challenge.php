<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/security.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['error' => 'Метод не поддерживается.'], 405);
try {
    if (!rate_limit_allows('challenge', 20, 600)) json_response(['error' => 'Слишком много запросов. Попробуйте позже.'], 429);
    json_response(create_altcha_challenge());
} catch (Throwable $exception) {
    error_log('ALTCHA configuration error: ' . $exception->getMessage());
    json_response(['error' => 'Проверка временно недоступна. Свяжитесь по телефону или email.'], 503);
}
