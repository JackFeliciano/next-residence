<?php

declare(strict_types=1);

namespace NextResidence\App;

use NextResidence\Home\Sample;

/**
 * The front controller. The network page is the whole show, so the routes stay
 * four: the network, a house, the photograph credits and the health line.
 */
final class Front
{
    public function __construct(private readonly string $base)
    {
    }

    public function run(): void
    {
        $router = new Router($this->base);
        $views = new Views($this->base);

        $router->get('/', static fn (): array => [200, ['html' => $views->home()]]);

        $router->get('/house/{handle}', static function (string $handle) use ($views): array {
            try {
                $house = Sample::house($handle);
            } catch (\RuntimeException) {
                return [404, ['html' => $views->notFound($handle)]];
            }

            return [200, ['html' => $views->house($house)]];
        });

        $router->get('/credits', static fn (): array => [200, ['html' => $views->credits()]]);

        $router->get('/health', static fn (): array => [
            200, ['json' => ['ok' => true, 'houses' => count(Sample::houses())]],
        ]);

        [$status, $body] = $router->handle(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/',
        );

        http_response_code($status);
        if (isset($body['json'])) {
            header('Content-Type: application/json');
            echo json_encode($body['json'], JSON_UNESCAPED_SLASHES), "\n";

            return;
        }
        header('Content-Type: text/html; charset=utf-8');
        echo $body['html'] ?? $views->notFound((string) ($body['path'] ?? '/'));
    }
}
