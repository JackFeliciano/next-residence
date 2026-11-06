<?php

declare(strict_types=1);

use NextResidence\Home\Stay;
use NextResidence\Tests\Assert;

return [
    ['a stay counts its nights', static function (): void {
        Assert::same(10, (new Stay('lisbon-alfama', '2026-11-04', '2026-11-14', 5))->nights());
    }],
    ['a stay that ends where it starts is refused', static function (): void {
        try {
            new Stay('lisbon-alfama', '2026-11-04', '2026-11-04', 5);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay that runs backwards is refused', static function (): void {
        try {
            new Stay('lisbon-alfama', '2026-11-14', '2026-11-04', 5);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay with nobody in it is refused', static function (): void {
        try {
            new Stay('lisbon-alfama', '2026-11-04', '2026-11-14', 0);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay covers its first night and not its last day', static function (): void {
        $stay = new Stay('lisbon-alfama', '2026-11-04', '2026-11-14', 5);
        Assert::true($stay->covers('2026-11-04'));
        Assert::true($stay->covers('2026-11-13'));
        Assert::false($stay->covers('2026-11-14'));
    }],
];
