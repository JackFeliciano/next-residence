<?php

declare(strict_types=1);

$sep = chr(92);

spl_autoload_register(static function (string $class) use ($sep): void {
    $prefix = 'NextResidence' . $sep . 'Tests' . $sep;
    if (str_starts_with($class, $prefix)) {
        $file = __DIR__ . '/tests/' . str_replace($sep, '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($file)) {
            require $file;
        }

        return;
    }
});

spl_autoload_register(static function (string $class) use ($sep): void {
    $prefix = 'NextResidence' . $sep;
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/src/' . str_replace($sep, '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
