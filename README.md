# Webstas

Новый сайт webstas.ru: семантический PHP/HTML, CSS и минимальный vanilla JavaScript без frontend-фреймворков и внешних визуальных ресурсов.

## Требования

- PHP 8.1+ с `openssl`, `mbstring`, `json`, `session`;
- HTTPS;
- SMTP-сервер с TLS и хранением персональных данных в соответствии с фактической политикой оператора;
- Apache с `mod_rewrite`, `mod_headers`, `mod_expires` либо Nginx по примеру `docs/nginx.conf.example`.

## Настройка

Скопировать значения из `.env.example` в безопасное хранилище окружения хостинга. Сам файл `.env` приложение намеренно не читает: секреты должны передаваться PHP-FPM средствами панели, systemd, Docker secrets или конфигурации пула и находиться вне публичного каталога.

Обязательные переменные:

- `OPERATOR_FULL_NAME`, `OPERATOR_STATUS`, `OPERATOR_INN`, `OPERATOR_OGRN`, `OPERATOR_ADDRESS`;
- `HOSTING_PROVIDER`, `MAIL_PROVIDER` — фактические обработчики и поставщики инфраструктуры;
- `REQUEST_RETENTION`, `TECHNICAL_LOG_RETENTION`, `CONSENT_DURATION` — в примере заданы типовые сроки 12 месяцев для обращения и 30 дней для технических журналов; оператор должен обеспечить их фактическое соблюдение;
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_ENCRYPTION`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_FROM`, `FORM_TO_EMAIL`;
- `ALTCHA_HMAC_KEY` — минимум 32 случайных символа;
- `RATE_LIMIT_DIR`, `USED_CHALLENGE_DIR` — абсолютные пути к доступным для записи каталогам вне web-root.

## Локальный запуск

```bash
ALTCHA_HMAC_KEY='replace-with-a-random-local-key-at-least-32-chars' \
RATE_LIMIT_DIR='/tmp/webstas-rate' \
USED_CHALLENGE_DIR='/tmp/webstas-used' \
php -S 127.0.0.1:8765 -t .
```

SMTP без реальных переменных не отправляет сообщения и возвращает пользователю безопасную общую ошибку.

## Проверки

```bash
ALTCHA_HMAC_KEY='test-only-key-which-is-at-least-32-characters-long' php tests/run.php
node --check assets/js/site.js
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

`tests/rendered_audit.php` запускается для каталога HTML, полученного с локального сервера, и проверяет SEO-метаданные, JSON-LD, изображения, подписи контролов и незаполненное согласие.

Исследование, аудит ассетов, карта URL и действия перед запуском находятся в `docs/`.
