<?php
declare(strict_types=1);
require dirname(__DIR__) . '/includes/security.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['ok' => false], 405);
json_response(['ok' => true, 'csrf' => csrf_token()]);
