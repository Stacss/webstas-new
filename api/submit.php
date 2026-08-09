<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/security.php';
require dirname(__DIR__) . '/includes/smtp.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok' => false, 'message' => 'Метод не поддерживается.'], 405);
if (!rate_limit_allows('form', 5, 900)) json_response(['ok' => false, 'message' => 'Слишком много попыток. Попробуйте через 15 минут.'], 429);
if (!verify_csrf((string)($_POST['csrf_token'] ?? ''))) json_response(['ok' => false, 'message' => 'Сессия формы устарела. Обновите страницу.'], 403);
if ((string)($_POST['website'] ?? '') !== '') json_response(['ok' => true, 'message' => 'Спасибо. Заявка принята.']);
$started = (int)($_POST['form_started'] ?? 0);
if ($started < 1 || time() - $started < 3 || time() - $started > 7200) json_response(['ok' => false, 'message' => 'Обновите страницу и заполните форму ещё раз.'], 422);
if (($_POST['consent'] ?? '') !== 'yes') json_response(['ok' => false, 'message' => 'Для отправки необходимо согласие на обработку персональных данных.'], 422);

$name = clean_line((string)($_POST['name'] ?? ''), 80);
$contact = clean_line((string)($_POST['contact'] ?? ''), 120);
$message = trim(mb_substr((string)($_POST['message'] ?? ''), 0, 3000));
$phoneLike = preg_match('/^\+?[0-9 ()-]{7,24}$/', $contact) === 1;
$emailLike = filter_var($contact, FILTER_VALIDATE_EMAIL) !== false;
if (mb_strlen($name) < 2 || (!$phoneLike && !$emailLike) || mb_strlen($message) < 10) {
    json_response(['ok' => false, 'message' => 'Проверьте имя, контакт и описание задачи.'], 422);
}
try {
    if (!verify_altcha_payload((string)($_POST['altcha'] ?? ''))) json_response(['ok' => false, 'message' => 'Подтвердите проверку ALTCHA ещё раз.'], 422);
    start_secure_session();
    $submissionHash = hash('sha256', mb_strtolower($name . '|' . $contact . '|' . $message));
    if (isset($_SESSION['last_submission']) && hash_equals((string)$_SESSION['last_submission'], $submissionHash)) {
        json_response(['ok' => false, 'message' => 'Эта заявка уже была отправлена.'], 409);
    }
    send_smtp_mail($contact, $name, $message);
    $_SESSION['last_submission'] = $submissionHash;
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    json_response(['ok' => true, 'message' => 'Заявка отправлена. Станислав свяжется с вами по указанному контакту.']);
} catch (Throwable $exception) {
    error_log('Webstas form delivery failed: ' . get_class($exception));
    json_response(['ok' => false, 'message' => 'Не удалось отправить заявку. Позвоните или напишите на info@webstas.ru.'], 503);
}
