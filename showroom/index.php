<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

use NextResidence\App\Front;

$base = getenv('NEXT_BASE') ?: '/';

// The built-in server sends every request to this file, so the stylesheet needs
// its own branch before the router sees the path.
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$prefix = rtrim($base, '/');
if ($prefix !== '' && str_starts_with($path, $prefix)) {
    $path = substr($path, strlen($prefix));
}
if (preg_match('#^/assets/(site\.css|app\.js)$#', $path, $m) === 1) {
    $type = $m[1] === 'site.css' ? 'text/css' : 'application/javascript';
    header('Content-Type: ' . $type . '; charset=utf-8');
    header('Cache-Control: public, max-age=300');
    echo file_get_contents(__DIR__ . '/assets/' . $m[1]);

    return;
}

(new Front($base))->run();
