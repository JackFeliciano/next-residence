<?php

declare(strict_types=1);

use NextResidence\App\Router;
use NextResidence\Tests\Assert;

return [
    ['a plain route matches', static function (): void {
        $router = new Router('/next/');
        $router->get('/health', static fn (): array => [200, ['html' => 'ok']]);
        Assert::same(200, $router->handle('GET', '/next/health')[0]);
    }],
    ['a placeholder carries its value', static function (): void {
        $router = new Router('/');
        $seen = '';
        $router->get('/house/{handle}', static function (string $h) use (&$seen): array {
            $seen = $h;

            return [200, ['html' => 'house']];
        });
        $router->handle('GET', '/house/lisbon-alfama');
        Assert::same('lisbon-alfama', $seen);
    }],
    ['an unknown path is a 404 that names it', static function (): void {
        $router = new Router('/');
        [$status, $body] = $router->handle('GET', '/nowhere');
        Assert::same(404, $status);
        Assert::same('/nowhere', $body['path']);
    }],
];
