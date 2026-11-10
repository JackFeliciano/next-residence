<?php

declare(strict_types=1);

use NextResidence\Home\Stay;
use NextResidence\Tests\Assert;

return [
    ['a stay counts its nights', static function (): void {
        Assert::same(10, (new Stay('tbilisi-mtkvari', '2027-11-04', '2027-11-14', 5))->nights());
    }],
    ['a stay that ends where it starts is refused', static function (): void {
        try {
            new Stay('tbilisi-mtkvari', '2027-11-04', '2027-11-04', 5);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay that runs backwards is refused', static function (): void {
        try {
            new Stay('tbilisi-mtkvari', '2027-11-14', '2027-11-04', 5);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay with nobody in it is refused', static function (): void {
        try {
            new Stay('tbilisi-mtkvari', '2027-11-04', '2027-11-14', 0);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a stay covers its first night and not its last day', static function (): void {
        $stay = new Stay('tbilisi-mtkvari', '2027-11-04', '2027-11-14', 5);
        Assert::true($stay->covers('2027-11-04'));
        Assert::true($stay->covers('2027-11-13'));
        Assert::false($stay->covers('2027-11-14'));
    }],
    ['a stay that crosses New Year counts each day once', static function (): void {
        $stay = new Stay('panama-casco', '2027-12-28', '2028-01-04', 3);
        Assert::same(7, $stay->nights());
        Assert::same(4, $stay->nightsInYear(2027));
        Assert::same(4, $stay->nightsInYear(2028));
    }],
];
