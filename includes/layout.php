<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';

function render_head(array $page): void
{
    $title = $page['title'] ?? 'Разработка сайтов и веб-сервисов в Костроме — Webstas';
    $description = $page['description'] ?? 'Webstas: B2B-платформы и веб-системы на Laravel, автоматизация, интеграции и публичные интерфейсы. Кострома, работа по России.';
    $path = $page['path'] ?? '/';
    $image = SITE_URL . ($page['image'] ?? '/assets/images/portfolio/spart-ainec-platform.webp');
    $imageWidth = (string) ($page['imageWidth'] ?? 1024);
    $imageHeight = (string) ($page['imageHeight'] ?? 640);
    ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1">
    <link rel="canonical" href="<?= e(canonical($path)) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="Webstas">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:url" content="<?= e(canonical($path)) ?>">
    <meta property="og:image" content="<?= e($image) ?>">
    <meta property="og:image:width" content="<?= e($imageWidth) ?>">
    <meta property="og:image:height" content="<?= e($imageHeight) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#1c2539">
    <link rel="icon" href="/assets/images/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="/assets/css/site.css?v=5">
    <?php if (!empty($page['schema'])): ?>
    <script type="application/ld+json"><?= json_encode($page['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($page['bodyClass'] ?? '') ?>">
<a class="skip-link" href="#content">Перейти к содержанию</a>
<?php
}

function render_header(): void
{
    ?>
<header class="site-header" data-header>
    <div class="header-inner shell">
        <a class="brand" href="/" aria-label="Webstas — главная"><span>WEB</span>stas<i>.</i></a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-menu" data-menu-toggle>
            <span class="visually-hidden">Открыть меню</span><span></span><span></span>
        </button>
        <nav class="main-nav" id="main-menu" aria-label="Основная навигация" data-menu>
            <a href="/#services">Услуги</a>
            <a href="/#portfolio">Кейсы</a>
            <a href="/#process">Процесс</a>
            <a href="/regions/kostroma/">Кострома</a>
            <a href="/#contact">Контакты</a>
        </nav>
        <a class="button button-light header-cta" href="/#request"><span>Обсудить задачу</span><b aria-hidden="true">↗</b></a>
    </div>
</header>
<?php
}

function render_breadcrumbs(array $items): void
{
    ?>
<nav class="breadcrumbs shell" aria-label="Хлебные крошки">
    <ol>
        <li><a href="/">Главная</a></li>
        <?php foreach ($items as $label => $url): ?>
            <li><?php if ($url): ?><a href="<?= e($url) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?></li>
        <?php endforeach; ?>
    </ol>
</nav>
<?php
}

function render_form(string $heading = 'Расскажите о задаче'): void
{
    ?>
<div class="request-card" id="request">
    <div>
        <p class="eyebrow">Начнём с короткого разговора</p>
        <h2><?= e($heading) ?></h2>
        <p>Опишите проект в свободной форме. Станислав уточнит детали и предложит следующий шаг — без автоматических рассылок.</p>
        <div class="direct-contact">
            <a href="tel:<?= CONTACT_PHONE_URI ?>"><?= CONTACT_PHONE ?></a>
            <a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a>
        </div>
    </div>
    <form class="request-form" action="/api/submit.php" method="post" data-request-form novalidate>
        <input type="hidden" name="csrf_token" value="" data-csrf>
        <input type="hidden" name="form_started" value="" data-form-started>
        <div class="honeypot" aria-hidden="true"><label>Ваш сайт<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <label><span class="field-label">Как к вам обращаться *<span aria-hidden="true"></span></span><input name="name" type="text" autocomplete="name" minlength="2" maxlength="80" required></label>
        <label><span class="field-label">Телефон или email *<span aria-hidden="true"></span></span><input name="contact" type="text" autocomplete="email" maxlength="120" required aria-describedby="contact-hint"><small id="contact-hint">Например, +7 900 000-00-00 или name@company.ru</small></label>
        <label><span class="field-label">Что нужно сделать *<span aria-hidden="true"></span></span><textarea name="message" rows="5" minlength="10" maxlength="3000" required placeholder="Новый сайт, доработка, интеграция или аудит..."></textarea></label>
        <div class="altcha-box" data-altcha>
            <button class="altcha-check" type="button" data-altcha-button><span data-altcha-icon aria-hidden="true"></span><span data-altcha-label>Подтвердить, что вы не робот</span></button>
            <input type="hidden" name="altcha" value="" data-altcha-payload>
        </div>
        <label class="checkbox"><input name="consent" type="checkbox" value="yes" required><span>Я даю <a href="/consent/" target="_blank">согласие на обработку персональных данных</a><span aria-hidden="true">*</span></span></label>
        <button class="button button-accent submit-button" type="submit"><span>Отправить заявку</span><b aria-hidden="true">↗</b></button>
        <p class="form-status" role="status" aria-live="polite" data-form-status></p>
    </form>
</div>
<?php
}

function render_footer(): void
{
    ?>
<footer class="site-footer">
    <div class="shell footer-grid">
        <div><a class="brand brand-footer" href="/"><span>WEB</span>stas<i>.</i></a><p>Laravel-системы, автоматизация и сайты<br>Кострома · удалённо по России</p></div>
        <div><h2>Услуги</h2><a href="/services/laravel-development/">Laravel-разработка</a><a href="/portfolio/spart/">Платформа SPART</a><a href="/services/docpart-development/">Доработка Docpart</a><a href="/services/site-speed/">Скорость сайта</a><a href="/services/seo-audit/">SEO-аудит</a></div>
        <div><h2>Информация</h2><a href="/company-details/">Об исполнителе</a><a href="/privacy/">Персональные данные</a><a href="/consent/">Согласие</a><a href="/cookies/">Cookies</a><button class="link-button" type="button" data-cookie-settings>Настроить cookies</button></div>
        <div><h2>Связаться</h2><a href="tel:<?= CONTACT_PHONE_URI ?>"><?= CONTACT_PHONE ?></a><a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a><a href="<?= TELEGRAM_URL ?>" rel="noopener" target="_blank">Telegram</a></div>
    </div>
    <div class="shell footer-bottom"><span>© <?= date('Y') ?> Webstas</span><span>На сайте не указаны цены: объём и условия определяются после обсуждения задачи.</span></div>
</footer>
<div class="cookie-panel" data-cookie-panel hidden>
    <div class="cookie-summary">
        <strong>Настройки cookies</strong>
        <p>Необходимые cookies работают всегда. Аналитика и маркетинг по умолчанию выключены и не загружаются без вашего выбора.</p>
    </div>
    <div class="cookie-details" data-cookie-details hidden>
        <label><input type="checkbox" checked disabled> Необходимые — сессия, безопасность формы, сохранение выбора</label>
        <label><input type="checkbox" data-cookie-category="analytics"> Аналитические — сейчас на сайте не подключены</label>
        <label><input type="checkbox" data-cookie-category="marketing"> Маркетинговые — сейчас на сайте не подключены</label>
    </div>
    <div class="cookie-actions">
        <button type="button" class="button button-small button-accent" data-cookie-accept>Принять все</button>
        <button type="button" class="button button-small button-outline" data-cookie-reject>Только необходимые</button>
        <button type="button" class="text-button" data-cookie-customize>Настроить</button>
        <button type="button" class="button button-small button-light" data-cookie-save hidden>Сохранить выбор</button>
    </div>
</div>
<script src="/assets/js/site.js?v=2" defer></script>
</body>
</html>
<?php
}

function local_business_schema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        '@id' => SITE_URL . '/#business',
        'name' => 'Webstas',
        'url' => SITE_URL . '/',
        'telephone' => CONTACT_PHONE,
        'email' => CONTACT_EMAIL,
        'founder' => ['@type' => 'Person', 'name' => 'Станислав'],
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Кострома', 'addressRegion' => 'Костромская область', 'addressCountry' => 'RU'],
        'areaServed' => [['@type' => 'City', 'name' => 'Кострома'], ['@type' => 'Country', 'name' => 'Россия']],
        'knowsAbout' => ['Laravel', 'Orchid Platform', 'SPART', 'B2B-платформы', 'складской учёт', 'автоматизация магазинов автозапчастей', 'API-интеграции', 'Docpart', 'оптимизация скорости сайтов'],
        'sameAs' => [TELEGRAM_URL],
    ];
}
