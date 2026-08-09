<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/layout.php';

$schema = local_business_schema();
$schema['@graph'] = [
    local_business_schema(),
    [
        '@type' => 'WebSite',
        '@id' => SITE_URL . '/#website',
        'url' => SITE_URL . '/',
        'name' => 'Webstas',
        'inLanguage' => 'ru-RU',
    ],
    [
        '@type' => 'SoftwareApplication',
        '@id' => SITE_URL . '/portfolio/spart/#software',
        'name' => 'SPART',
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'Web',
        'description' => 'B2B-платформа автозапчастей: публичный магазин, клиентский кабинет, номенклатура, склад, заказы, финансы и интеграции.',
        'url' => SITE_URL . '/portfolio/spart/',
        'author' => ['@type' => 'Person', 'name' => 'Станислав'],
    ],
    [
        '@type' => 'FAQPage',
        'mainEntity' => [
            ['@type' => 'Question', 'name' => 'Можно доработать уже работающий сайт?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Да. Работа начинается с изучения кода, инфраструктуры и задачи. После аудита определяется безопасный план доработки без обещаний до знакомства с проектом.']],
            ['@type' => 'Question', 'name' => 'Работает ли Webstas только в Костроме?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Webstas находится в Костроме и работает с заказчиками по России удалённо. Обсуждения, демонстрации и передача результата проходят онлайн.']],
            ['@type' => 'Question', 'name' => 'Какие Laravel-системы разрабатывает Webstas?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Внутренние B2B-платформы, административные панели, личные кабинеты, каталоги, складской и заказной контур, интеграции и фоновые процессы. Пример — собственная платформа автозапчастей SPART.']],
        ],
    ],
];
unset($schema['@type'], $schema['@id'], $schema['name'], $schema['url'], $schema['telephone'], $schema['email'], $schema['founder'], $schema['address'], $schema['areaServed'], $schema['knowsAbout'], $schema['sameAs']);

render_head([
    'title' => 'Laravel-системы и автоматизация бизнеса — Webstas',
    'description' => 'Разработка B2B-платформ и веб-систем на Laravel: каталоги, склады, заказы, кабинеты и интеграции. SPART для рынка автозапчастей. Кострома, вся Россия.',
    'path' => '/',
    'schema' => $schema,
    'bodyClass' => 'home',
]);
render_header();
?>
<main id="content">
    <section class="hero" id="hero">
        <div class="hero-art" aria-hidden="true"><span class="orbit orbit-one"></span><span class="orbit orbit-two"></span><span class="code-card"><i>&lt;/&gt;</i><b>Процесс</b><em>→</em><b>Система</b></span></div>
        <div class="shell hero-inner">
            <div class="hero-copy">
                <p class="eyebrow eyebrow-inverse"><span>✦</span> Webstas · Кострома <span>✦</span></p>
                <h1>Laravel‑системы<br>для <mark>процессов бизнеса</mark></h1>
                <p class="hero-lead">Проектирую и развиваю B2B‑платформы, внутренние кабинеты и публичные сервисы: бизнес‑логика, данные, очереди, API‑интеграции и интерфейсы в одной системе.</p>
                <div class="hero-actions">
                    <a class="button button-light" href="#request"><span>Обсудить проект</span><b aria-hidden="true">↗</b></a>
                    <a class="text-link text-link-light" href="#portfolio">Смотреть кейсы <span aria-hidden="true">↓</span></a>
                </div>
            </div>
            <div class="hero-meta" aria-label="Ключевые направления">
                <span>Laravel 12</span><span>SPART</span><span>Orchid</span><span>API &amp; queues</span>
            </div>
        </div>
    </section>

    <section class="intro section" id="about">
        <div class="shell split-heading">
            <div><p class="eyebrow">О Webstas</p><h2>Не просто сайт. Рабочий инструмент для команды и клиентов</h2></div>
            <div class="intro-copy"><p>Меня зовут Станислав. С 2017 года я занимаюсь frontend- и backend‑разработкой: превращаю процессы компании в понятную модель данных, интерфейсы и автоматические сценарии.</p><p>Флагманский проект — SPART, B2B‑платформа для автозапчастей со складом, заказами, финансами, каталогом, клиентским кабинетом и интеграциями. Также разрабатываю сайты и точечно развиваю существующие Laravel‑системы.</p><a class="text-link" href="#spart">Изучить SPART <span>↓</span></a></div>
        </div>
    </section>

    <section class="services section section-soft" id="services">
        <div class="shell">
            <div class="section-head"><div><p class="eyebrow">Направления</p><h2>От внутренней логики до публичного интерфейса</h2></div><p>Основной фокус — системы, которые хранят данные, связывают участников процесса и снимают повторяющуюся ручную работу.</p></div>
            <div class="service-grid">
                <a class="service-card service-card-featured" href="/services/laravel-development/"><span class="service-number">01</span><div class="service-icon" aria-hidden="true">{ }</div><h3>B2B‑системы на Laravel</h3><p>Административные панели, кабинеты, роли, документы, фоновые процессы и сложная бизнес‑логика.</p><span class="card-link">Подробнее <b>↗</b></span></a>
                <a class="service-card" href="#spart"><span class="service-number">02</span><div class="service-icon" aria-hidden="true">SP</div><h3>Автоматизация автозапчастей</h3><p>Каталог, поставщики, склад, заказы, кассы, маркетплейсы и публичный магазин на платформе SPART.</p><span class="card-link">Смотреть кейс <b>↓</b></span></a>
                <a class="service-card" href="/services/landing-page/"><span class="service-number">03</span><div class="service-icon" aria-hidden="true">↳</div><h3>Лендинги</h3><p>Посадочные страницы с ясным оффером, адаптивной версткой и подготовкой к рекламе.</p><span class="card-link">Подробнее <b>↗</b></span></a>
                <a class="service-card" href="/services/corporate-site/"><span class="service-number">04</span><div class="service-icon" aria-hidden="true">▦</div><h3>Корпоративные сайты</h3><p>Структура, которая объясняет услуги, подтверждает компетенции и помогает обратиться.</p><span class="card-link">Подробнее <b>↗</b></span></a>
                <a class="service-card" href="/services/site-speed/"><span class="service-number">05</span><div class="service-icon" aria-hidden="true">↯</div><h3>Скорость сайта</h3><p>Диагностика узких мест frontend и backend, оптимизация загрузки и Core Web Vitals.</p><span class="card-link">Подробнее <b>↗</b></span></a>
                <a class="service-card" href="/services/seo-audit/"><span class="service-number">06</span><div class="service-icon" aria-hidden="true">⌁</div><h3>Технический SEO‑аудит</h3><p>Индексация, структура, метаданные, микроразметка и технические ошибки.</p><span class="card-link">Подробнее <b>↗</b></span></a>
            </div>
        </div>
    </section>

    <section class="cases section" id="portfolio">
        <div class="shell">
            <div class="section-head"><div><p class="eyebrow">Подтверждённые работы</p><h2>Проекты, а не обещания</h2></div><p>Реальные проекты Webstas с конкретным описанием разработанной функциональности. Без выдуманных метрик и отзывов.</p></div>
            <div class="case-list">
                <article class="case-card case-wide case-spart" id="spart"><div class="case-media"><img src="/assets/images/portfolio/spart-ainec-platform.webp" alt="Публичная витрина магазина АВТОиностранец — внедрение платформы SPART" width="1024" height="640" loading="lazy"></div><div class="case-copy"><p class="case-tags"><span>Laravel 12</span><span>Orchid 14</span><span>B2B</span></p><h3>SPART / s.ainec.ru</h3><p>Собственная B2B‑платформа автозапчастей и её рабочее внедрение для «АВТОиностранец». В одной системе связаны публичный магазин, клиентский кабинет, номенклатура, склад, заказы, оплаты, кассы и внутренние задания.</p><ul class="case-points"><li>Поиск по артикулу, OEM и штрихкоду</li><li>Приёмки, остатки, ячейки и инвентаризация</li><li>Ozon, Drom, поставщики и кассовое оборудование</li><li>Очереди, импорт, PDF, аудит и realtime‑сценарии</li></ul><a class="text-link" href="/portfolio/spart/">Разобрать проект <span>→</span></a></div><figure class="case-admin-shot"><img src="/assets/images/portfolio/spart-admin-product.webp" alt="Административная панель SPART: редактирование товара, OEM, штрихкоды и изображения" width="1800" height="972" loading="lazy"><figcaption>Внутренняя Orchid‑панель SPART · карточка номенклатуры</figcaption></figure></article>
                <article class="case-card case-wide"><div class="case-media"><img src="/assets/images/portfolio/express-zapchasti.webp" alt="Адаптивный лендинг Express Запчасти на экранах компьютера, ноутбука, планшета и телефона" width="1024" height="768" loading="lazy"></div><div class="case-copy"><p class="case-tags"><span>Laravel</span><span>SEO</span><span>Telegram</span></p><h3>Express Запчасти</h3><p>Лендинг магазина автозапчастей в Костроме: административная панель для заявок, уведомления в Telegram, SEO‑подготовка и развёртывание.</p><dl><div><dt>Тип</dt><dd>Полный цикл</dd></div><div><dt>Дата</dt><dd>Май 2023</dd></div></dl></div></article>
                <article class="case-card"><div class="case-media"><img src="/assets/images/portfolio/docpart-telegram-bot.webp" alt="Панель управления Telegram-ботом для платформы Docpart и экран бота на смартфоне" width="1024" height="768" loading="lazy"></div><div class="case-copy"><p class="case-tags"><span>Docpart</span><span>Telegram API</span></p><h3>Бот для АВТОиностранец</h3><p>Уведомления о заказах и позициях, рассылки, документы для клиента и управление базой знаний.</p></div></article>
                <article class="case-card"><div class="case-media"><img src="/assets/images/portfolio/like-feedback.webp" alt="Адаптивный интерфейс сервиса Like Feedback на четырёх устройствах" width="1024" height="768" loading="lazy"></div><div class="case-copy"><p class="case-tags"><span>Laravel</span><span>Интеграции</span></p><h3>Like Feedback</h3><p>Сервис работы с отзывами: личный кабинет, несколько компаний в аккаунте и уведомления по email и в Telegram.</p></div></article>
            </div>
        </div>
    </section>

    <section class="why section section-dark">
        <div class="shell why-grid">
            <div><p class="eyebrow eyebrow-inverse">Подход</p><h2>Сначала процесс.<br>Затем модель.<br>Потом код.</h2></div>
            <ol class="principles">
                <li><span>01</span><div><h3>Прямой контакт</h3><p>Задачу принимает и реализует один специалист. Меньше потерь смысла между брифом и кодом.</p></div></li>
                <li><span>02</span><div><h3>Предметная модель</h3><p>Пользователи, товары, документы, статусы и исключения описываются до того, как становятся кодом.</p></div></li>
                <li><span>03</span><div><h3>Развиваемая система</h3><p>Модули, очереди, интеграции, аудит и тесты закладываются так, чтобы продукт можно было безопасно расширять.</p></div></li>
            </ol>
        </div>
    </section>

    <section class="process section" id="process">
        <div class="shell">
            <div class="section-head"><div><p class="eyebrow">Процесс</p><h2>Понятные этапы без магии</h2></div><p>Точный состав работ, сроки и стоимость появляются только после оценки задачи.</p></div>
            <ol class="process-grid">
                <li><span>01</span><h3>Контекст</h3><p>Цели, пользователи, ограничения, текущий сайт и необходимые интеграции.</p></li>
                <li><span>02</span><h3>План</h3><p>Структура решения, границы работ, ожидаемый результат и критерии приёмки.</p></li>
                <li><span>03</span><h3>Разработка</h3><p>Интерфейс и программная часть с промежуточными демонстрациями.</p></li>
                <li><span>04</span><h3>Проверка и запуск</h3><p>Адаптивность, формы, скорость, базовое SEO и перенос на рабочий сервер.</p></li>
            </ol>
        </div>
    </section>

    <section class="local section section-accent">
        <div class="shell local-grid"><div><p class="eyebrow">Кострома · Россия</p><h2>Рядом — если вы в Костроме.<br>На связи — если вы в другом городе.</h2></div><div><p>Локальным компаниям — понимание рынка Костромы и возможность личной встречи по договорённости. Проекты из других регионов ведутся удалённо: созвоны, демонстрации и передача материалов онлайн.</p><a class="button button-dark" href="/regions/kostroma/"><span>Разработка в Костроме</span><b>↗</b></a></div></div>
    </section>

    <section class="faq section">
        <div class="shell faq-grid"><div><p class="eyebrow">FAQ</p><h2>До первой встречи</h2></div><div class="accordion">
            <details><summary>Можно доработать уже работающий сайт?<span>+</span></summary><p>Да. Сначала изучаются код, инфраструктура и задача. После аудита определяется безопасный план изменений.</p></details>
            <details><summary>Работаете только в Костроме?<span>+</span></summary><p>Нет. Webstas находится в Костроме и работает с заказчиками по России удалённо.</p></details>
            <details><summary>Какие Laravel‑системы вы разрабатываете?<span>+</span></summary><p>Внутренние B2B‑платформы, административные панели, личные кабинеты, каталоги, складской и заказной контур, интеграции и фоновые процессы. SPART показывает этот подход на действующем проекте автозапчастей.</p></details>
            <details><summary>Почему на сайте нет цен и сроков?<span>+</span></summary><p>Для нестандартной разработки они зависят от объёма, состояния исходного проекта и интеграций. Оценка без изучения задачи была бы неточной.</p></details>
        </div></div>
    </section>

    <section class="contact section section-soft" id="contact"><div class="shell"><?php render_form(); ?></div></section>
</main>
<?php render_footer(); ?>
