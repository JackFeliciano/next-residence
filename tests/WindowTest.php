<?php

declare(strict_types=1);

use NextResidence\Home\Stay;
use NextResidence\Home\Window;
use NextResidence\Tests\Assert;

return [
    ['a window counts its nights', static function (): void {
        Assert::same(30, (new Window('2026-11-01', '2026-12-01'))->nights());
    }],
    ['a window admits a stay inside it', static function (): void {
        $window = new Window('2026-11-01', '2026-12-01');
        Assert::true($window->admits(new Stay('h', '2026-11-04', '2026-11-14', 2)));
    }],
    ['a window refuses a stay that starts before it', static function (): void {
        $window = new Window('2026-11-01', '2026-12-01');
        Assert::false($window->admits(new Stay('h', '2026-10-30', '2026-11-10', 2)));
    }],
    ['a window refuses a stay that ends after it', static function (): void {
        $window = new Window('2026-11-01', '2026-12-01');
        Assert::false($window->admits(new Stay('h', '2026-11-25', '2026-12-05', 2)));
    }],
    ['the nights left shrink with the stay', static function (): void {
        $window = new Window('2026-11-01', '2026-12-01');
        Assert::same(20, $window->left(new Stay('h', '2026-11-04', '2026-11-14', 2)));
    }],
    ['a window with no nights is refused', static function (): void {
        try {
            new Window('2026-11-01', '2026-11-01');
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
];
