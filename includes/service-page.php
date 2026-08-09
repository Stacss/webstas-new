<?php
declare(strict_types=1);
require_once __DIR__ . '/layout.php';

if (!isset($service)) {
    http_response_code(500);
    exit('Service configuration is missing.');
}

$path = '/services/' . $service['slug'] . '/';
$schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            '@id' => canonical($path) . '#service',
            'name' => $service['h1'],
            'description' => $service['description'],
            'provider' => ['@id' => SITE_URL . '/#business'],
            'areaServed' => [['@type' => 'City', 'name' => 'Кострома'], ['@type' => 'Country', 'name' => 'Россия']],
            'serviceType' => $service['serviceType'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => SITE_URL . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Услуги', 'item' => SITE_URL . '/#services'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $service['short'], 'item' => canonical($path)],
            ],
        ],
    ],
];

render_head(['title' => $service['title'], 'description' => $service['description'], 'path' => $path, 'schema' => $schema]);
render_header();
?>
<main id="content">
    <?php render_breadcrumbs(['Услуги' => '/#services', $service['short'] => null]); ?>
    <section class="page-hero"><div class="shell"><p class="eyebrow"><?= e($service['eyebrow']) ?></p><h1><?= e($service['h1']) ?></h1><p class="lead"><?= e($service['lead']) ?></p><a class="button button-dark" href="#request"><span>Обсудить задачу</span><b>↗</b></a></div></section>
    <section class="section section-soft"><div class="shell page-layout">
        <aside><h2>На странице</h2><nav aria-label="Содержание"><a href="#tasks">Какие задачи</a><a href="#result">Что входит</a><a href="#process">Как проходит работа</a><a href="#request">Оставить заявку</a></nav></aside>
        <article class="prose">
            <h2 id="tasks"><?= e($service['tasksTitle']) ?></h2>
            <p><?= e($service['tasksIntro']) ?></p>
            <ul class="feature-list"><?php foreach ($service['tasks'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
            <h2 id="result"><?= e($service['resultTitle']) ?></h2>
            <?php foreach ($service['result'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
            <div class="note"><strong>Важно:</strong> <?= e($service['note']) ?></div>
            <h2 id="process">Как строится работа</h2>
            <ol><li><strong>Разбор.</strong> Цель, текущая система, ограничения и критерии результата.</li><li><strong>Оценка.</strong> Состав работ, зависимости и порядок внедрения.</li><li><strong>Реализация.</strong> Разработка с промежуточной проверкой.</li><li><strong>Запуск.</strong> Тестирование, перенос и передача результата.</li></ol>
            <h2>Кому подходит</h2><p><?= e($service['audience']) ?></p>
        </article>
    </div></section>
    <section class="contact section"><div class="shell"><?php render_form($service['formHeading']); ?></div></section>
</main>
<?php render_footer(); ?>
