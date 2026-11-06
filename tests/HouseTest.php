<?php

declare(strict_types=1);

use NextResidence\Home\House;
use NextResidence\Home\Stay;
use NextResidence\Tests\Assert;

return [
    ['a house says which months it opens', static function (): void {
        $house = new House('madeira-cliff', 'Funchal', 4, [4, 5, 6]);
        Assert::true($house->opensOn('2026-05-10'));
        Assert::false($house->opensOn('2026-11-10'));
    }],
    ['a house fits a stay that names it', static function (): void {
        $house = new House('madeira-cliff', 'Funchal', 4, [11]);
        Assert::true($house->fits(new Stay('madeira-cliff', '2026-11-08', '2026-11-12', 4)));
    }],
    ['a house refuses a stay that names another house', static function (): void {
        $house = new House('madeira-cliff', 'Funchal', 4, [11]);
        Assert::false($house->fits(new Stay('kyoto-machiya', '2026-11-08', '2026-11-12', 4)));
    }],
    ['a house refuses more people than beds', static function (): void {
        $house = new House('madeira-cliff', 'Funchal', 3, [11]);
        Assert::false($house->fits(new Stay('madeira-cliff', '2026-11-08', '2026-11-12', 4)));
    }],
    ['a house refuses a closed month', static function (): void {
        $house = new House('kyoto-machiya', 'Kyoto', 5, [3, 4, 5]);
        Assert::false($house->fits(new Stay('kyoto-machiya', '2026-11-10', '2026-11-20', 4)));
    }],
    ['a month outside the calendar is refused', static function (): void {
        try {
            new House('x', 'Y', 2, [13]);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
];
