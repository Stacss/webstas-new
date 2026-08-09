<?php
declare(strict_types=1);
http_response_code(404);
require __DIR__ . '/includes/layout.php';
render_head(['title' => 'Страница не найдена — Webstas', 'description' => 'Запрошенная страница не найдена.', 'path' => '/404/', 'bodyClass' => 'not-found']);
render_header();
?>
<main id="content"><section class="page-hero section"><div class="shell"><p class="eyebrow">Ошибка 404</p><h1>Такой страницы нет</h1><p class="lead">Возможно, адрес изменился при обновлении сайта.</p><a class="button button-dark" href="/"><span>На главную</span><b>↗</b></a></div></section></main>
<?php render_footer(); ?>
