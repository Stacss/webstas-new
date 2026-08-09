<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/layout.php';

$path = '/portfolio/spart/';
$schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => SITE_URL . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'SPART', 'item' => canonical($path)],
            ],
        ],
        [
            '@type' => 'SoftwareApplication',
            '@id' => canonical($path) . '#software',
            'name' => 'SPART',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => canonical($path),
            'description' => 'B2B-платформа автозапчастей: внутренний операционный контур, публичный интернет-магазин, клиентский кабинет, склад, заказы, финансы и интеграции.',
            'author' => ['@type' => 'Person', 'name' => 'Станислав'],
            'workExample' => ['@type' => 'WebSite', 'name' => 'АВТОиностранец', 'url' => 'https://s.ainec.ru/'],
        ],
    ],
];

render_head([
    'title' => 'SPART — B2B-платформа автозапчастей на Laravel 12',
    'description' => 'Кейс Webstas: SPART — Laravel-платформа для автозапчастей со складом, заказами, кассами, каталогом, кабинетом и интеграциями Ozon, Drom и поставщиков.',
    'path' => $path,
    'schema' => $schema,
]);
render_header();
render_breadcrumbs(['SPART' => null]);
?>
<main id="content">
    <section class="page-hero project-hero">
        <div class="shell">
            <p class="eyebrow">Флагманский проект · B2B · автозапчасти</p>
            <h1>SPART — одна система для магазина, склада и команды</h1>
            <p class="lead">Собственная Laravel‑платформа, которая объединяет внутренние операции бизнеса автозапчастей и публичную витрину с каталогом и личным кабинетом клиента.</p>
        </div>
    </section>

    <div class="shell">
        <figure class="project-cover">
            <img src="/assets/images/portfolio/spart-ainec-platform.webp" alt="Публичный магазин АВТОиностранец, работающий на платформе SPART" width="1024" height="640">
        </figure>

        <dl class="project-summary" aria-label="Краткие сведения о SPART">
            <div><dt>Назначение</dt><dd>B2B и внутренняя платформа</dd></div>
            <div><dt>Backend</dt><dd>PHP 8.2 · Laravel 12</dd></div>
            <div><dt>Управление</dt><dd>Orchid Platform 14</dd></div>
        </dl>

        <div class="project-content">
            <section>
                <p class="eyebrow">Контекст проекта</p>
                <h2>От витрины товара до движения на складе</h2>
                <p class="lead">SPART создаётся не как отдельный сайт или типовая CMS. Это единая предметная система, в которой публичная продажа связана с номенклатурой, поставщиками, складскими документами, заказами, оплатами и работой сотрудников.</p>
                <p>Публичное внедрение платформы работает для магазина «АВТОиностранец» на <a class="text-link" href="https://s.ainec.ru/" target="_blank" rel="noopener">s.ainec.ru <span>↗</span></a>. Покупатель видит каталог, карточки товаров и личный кабинет, а команда управляет операционным контуром через отдельную Orchid‑панель.</p>
                <figure class="project-screen">
                    <img src="/assets/images/portfolio/spart-admin-product.webp" alt="Экран редактирования товара в административной панели SPART с OEM, штрихкодами и изображениями" width="1800" height="972" loading="lazy">
                    <figcaption>Карточка товара во внутренней панели: основные значения, OEM‑номера, аналоги, штрихкоды, маркировка, фотографии и видео.</figcaption>
                </figure>
            </section>

            <section>
                <p class="eyebrow">Что внутри</p>
                <h2>Ключевые контуры платформы</h2>
                <div class="capability-grid">
                    <article class="capability-card"><h3>Каталог и номенклатура</h3><p>Товары, бренды и алиасы, категории, характеристики, OEM‑коды, штрихкоды, изображения, маркировка и автомобильные справочники.</p></article>
                    <article class="capability-card"><h3>Склад</h3><p>Приёмки, движения и остатки, адресные ячейки, инвентаризация, списания, возвраты поставщику, ценники и история товара.</p></article>
                    <article class="capability-card"><h3>Заказы и клиенты</h3><p>Заказы и позиции со статусами, документы, возвраты, платежи, автомобили и юридические лица клиентов, внутренние задания.</p></article>
                    <article class="capability-card"><h3>Публичный магазин</h3><p>Поиск по названию, артикулу, OEM и штрихкоду, бренды и категории, карточка товара, регистрация, подтверждение email и история заказов.</p></article>
                    <article class="capability-card"><h3>Финансы и кассы</h3><p>Платежи, кассовые смены, фискальные чеки, расходы и контроль кассы; обмен заданиями KKMServer.</p></article>
                    <article class="capability-card"><h3>Поставщики и каналы продаж</h3><p>Агрегация предложений поставщиков, прайсы и расчёт цен, синхронизация товаров, цен и остатков с Ozon, выгрузка для Drom.</p></article>
                </div>
            </section>

            <section>
                <p class="eyebrow">Интеграции</p>
                <h2>Система связывает внешние сервисы</h2>
                <p class="lead">SPART работает как центральный слой между товарными данными, поставщиками, маркетплейсами, кассовым оборудованием и коммуникациями с клиентом.</p>
                <ul class="stack-list" aria-label="Интеграции SPART">
                    <li>Ozon</li><li>Drom</li><li>API поставщиков</li><li>KKMServer</li><li>Dadata</li><li>SMS‑gate</li><li>S3 / Minio</li>
                </ul>
            </section>

            <section>
                <p class="eyebrow">Архитектура</p>
                <h2>Актуальный технологический стек</h2>
                <p>Актуальная кодовая база SPART использует Laravel 12 и PHP 8.2. Стек дополняют отдельные интерфейсы для команды и покупателей, realtime‑обновления, очереди и прикладные инструменты для документов и товарных данных.</p>
                <ul class="stack-list" aria-label="Технологии SPART">
                    <li>PHP 8.2</li><li>Laravel 12</li><li>Orchid 14</li><li>Sanctum</li><li>Reverb · Echo</li><li>Vue 3</li><li>Stimulus</li><li>Vite 5</li><li>MySQL / SQLite</li><li>Redis · queues</li><li>DomPDF</li><li>Laravel Excel</li><li>Intervention Image</li><li>OpenAPI</li><li>PHPUnit 11</li>
                </ul>
            </section>

            <section>
                <p class="eyebrow">Инженерная часть</p>
                <h2>Долгие операции не блокируют работу</h2>
                <p class="lead">Импорт прайсов, обновление предложений, синхронизация маркетплейсов, генерация изображений и документов выполняются через фоновые очереди. Планировщик контролирует регламентные задачи, напоминания и обновление sitemap.</p>
                <div class="project-note"><strong>Контролируемость изменений.</strong> В проекте используются Form Request‑валидация, разграничение административной и клиентской аутентификации, Sanctum для API, аудит изменений, feature/unit‑тесты и автоматическое форматирование кода.</div>
            </section>

            <section>
                <p class="eyebrow">Результат</p>
                <h2>Одна модель данных вместо набора разрозненных инструментов</h2>
                <p class="lead">SPART показывает основной профиль Webstas: разработку систем, где публичный интерфейс — только видимая часть, а основная ценность находится в связанной бизнес‑логике и автоматизации ежедневной работы.</p>
                <div class="project-links">
                    <a class="button button-dark" href="/#request"><span>Обсудить похожую задачу</span><b aria-hidden="true">↗</b></a>
                    <a class="text-link" href="https://s.ainec.ru/" target="_blank" rel="noopener">Открыть внедрение <span>↗</span></a>
                </div>
            </section>
        </div>
    </div>

    <section class="contact section section-soft"><div class="shell"><?php render_form('Нужна система для процессов бизнеса?'); ?></div></section>
</main>
<?php render_footer(); ?>
