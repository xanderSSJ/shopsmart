<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? $path : '/';

$file = __DIR__ . '/public' . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/public/index.php';
