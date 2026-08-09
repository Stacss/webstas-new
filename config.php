<?php
declare(strict_types=1);

function env_value(string $key, string $default = ''): string
{
    $value = getenv($key);
    return $value === false || $value === '' ? $default : trim($value);
}

const SITE_URL = 'https://webstas.ru';
const SITE_NAME = 'Webstas';
const CONTACT_PHONE = '+7 910 950-20-70';
const CONTACT_PHONE_URI = '+79109502070';
const CONTACT_EMAIL = 'info@webstas.ru';
const CONTACT_CITY = 'Кострома';
const TELEGRAM_URL = 'https://t.me/postnikovstas';

function operator_data(): array
{
    return [
        'name' => env_value('OPERATOR_FULL_NAME', 'Постников С.А.'),
        'status' => env_value('OPERATOR_STATUS', 'ИП'),
        'inn' => env_value('OPERATOR_INN', '182908129976'),
        'ogrn' => env_value('OPERATOR_OGRN', '318440100007565'),
        'address' => env_value('OPERATOR_ADDRESS', 'Кострома'),
        'email' => CONTACT_EMAIL,
        'request_retention' => env_value('REQUEST_RETENTION', '12 месяцев с даты последнего содержательного взаимодействия по обращению'),
        'technical_log_retention' => env_value('TECHNICAL_LOG_RETENTION', '30 календарных дней'),
        'consent_duration' => env_value('CONSENT_DURATION', 'в течение 12 месяцев с даты последнего содержательного взаимодействия по обращению либо до отзыва согласия, если он наступит раньше'),
        'hosting_provider' => env_value('HOSTING_PROVIDER', 'TimeWeb'),
        'mail_provider' => env_value('MAIL_PROVIDER', 'TimeWeb'),
    ];
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function canonical(string $path): string
{
    return SITE_URL . ($path === '/' ? '/' : '/' . trim($path, '/') . '/');
}
