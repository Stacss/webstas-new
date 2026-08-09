<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/layout.php';
$operator = operator_data();
render_head(['title' => 'Сведения об исполнителе и контакты — Webstas', 'description' => 'Контактные и юридические сведения исполнителя услуг Webstas в Костроме.', 'path' => '/company-details/']);
render_header();
?>
<main id="content"><?php render_breadcrumbs(['Сведения об исполнителе' => null]); ?>
<section class="section section-soft"><div class="shell page-layout"><aside><h2>Связаться</h2><p><a href="tel:<?= CONTACT_PHONE_URI ?>"><?= CONTACT_PHONE ?></a><br><a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a><br>Кострома, Россия</p></aside><article class="prose">
<div class="legal-meta"><p><strong>Бренд:</strong> Webstas</p><p><strong>Исполнитель:</strong> <?= e($operator['status'] . ' ' . $operator['name']) ?></p><p><strong>ИНН:</strong> <?= e($operator['inn']) ?></p><p><strong>ОГРН/ОГРНИП:</strong> <?= e($operator['ogrn']) ?></p><p><strong>Адрес:</strong> <?= e($operator['address']) ?></p><p><strong>Режим работы:</strong> по предварительной договорённости</p><p><strong>Телефон:</strong> <?= CONTACT_PHONE ?></p><p><strong>Email:</strong> <?= CONTACT_EMAIL ?></p></div>
<h2>Об услугах</h2><p>Разработка и доработка сайтов и веб‑сервисов, Laravel, собственная платформа SPART для автозапчастей, Docpart, интеграции, Telegram‑боты, оптимизация скорости и технический SEO‑аудит. Конкретные условия, объём, стоимость и сроки определяются по итогам обсуждения и закрепляются в договоре.</p>
<p>Веб‑разработка не относится к лицензируемому виду деятельности сама по себе. Если фактическая деятельность исполнителя включает лицензируемые направления, сведения о лицензии должны быть добавлены владельцем.</p>
</article></div></section></main><?php render_footer(); ?>
