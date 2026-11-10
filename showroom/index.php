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

// The fifteen photographs. The licence files stay out of reach.
if (preg_match('#^/assets/photos/([0-9]{2}-[a-z0-9-]+\.jpg)$#', $path, $m) === 1) {
    $file = __DIR__ . '/assets/photos/' . $m[1];
    if (is_file($file)) {
        header('Content-Type: image/jpeg');
        header('Cache-Control: public, max-age=86400');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        return;
    }
}


(new Front($base))->run();
