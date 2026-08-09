<?php
declare(strict_types=1);

$directory = $argv[1] ?? '';
if (!is_dir($directory)) throw new RuntimeException('Rendered page directory is missing.');
$files = glob(rtrim($directory, '/') . '/*.html') ?: [];
if (count($files) < 10) throw new RuntimeException('Not enough rendered pages to audit.');
$checks = 0;
foreach ($files as $file) {
    $html = file_get_contents($file);
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    if (!$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING)) throw new RuntimeException("Invalid HTML in {$file}");
    $xpath = new DOMXPath($dom);
    $required = ['//title', '//meta[@name="description"]', '//link[@rel="canonical"]', '//h1', '//html[@lang="ru"]'];
    foreach ($required as $query) {
        if ($xpath->query($query)->length !== 1) throw new RuntimeException("Expected exactly one {$query} in {$file}");
        $checks++;
    }
    foreach ($xpath->query('//script[@type="application/ld+json"]') as $script) {
        json_decode($script->textContent, true, 512, JSON_THROW_ON_ERROR);
        $checks++;
    }
    foreach ($xpath->query('//img') as $image) {
        if (!$image->hasAttribute('alt') || !$image->hasAttribute('width') || !$image->hasAttribute('height')) {
            throw new RuntimeException("Image metadata missing in {$file}");
        }
        if (str_starts_with($image->getAttribute('src'), 'http')) throw new RuntimeException("External image in {$file}");
        $checks++;
    }
    $ids = [];
    foreach ($xpath->query('//*[@id]') as $element) {
        $id = $element->getAttribute('id');
        if (isset($ids[$id])) throw new RuntimeException("Duplicate id {$id} in {$file}");
        $ids[$id] = true;
    }
    foreach ($xpath->query('//button') as $button) {
        if (trim($button->textContent) === '' && !$button->hasAttribute('aria-label')) throw new RuntimeException("Unlabelled button in {$file}");
    }
    foreach ($xpath->query('//form//input[not(@type="hidden")]|//form//textarea') as $control) {
        $labelled = false;
        for ($parent = $control->parentNode; $parent; $parent = $parent->parentNode) {
            if ($parent instanceof DOMElement && $parent->tagName === 'label') { $labelled = true; break; }
            if ($parent instanceof DOMElement && $parent->tagName === 'form') break;
        }
        if (!$labelled) throw new RuntimeException("Unlabelled form control in {$file}");
    }
    if ($xpath->query('//input[@name="consent" and @checked]')->length > 0) throw new RuntimeException("Consent is pre-checked in {$file}");
    $checks += 3;
}
echo 'OK: ' . count($files) . " rendered pages, {$checks} SEO/HTML checks passed\n";
