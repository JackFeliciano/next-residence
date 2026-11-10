<?php

declare(strict_types=1);

use NextResidence\Home\House;
use NextResidence\Home\Stay;
use NextResidence\Tests\Assert;

$all = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

return [
    ['a house opens all twelve months, because a stay runs a full year', static function () use ($all): void {
        $house = new House('valletta-bastion', 'Valletta', 'Malta', 4, $all);
        Assert::same(12, count($house->openMonths));
    }],
    ['a house that keeps a month closed is refused', static function (): void {
        try {
            new House('valletta-bastion', 'Valletta', 'Malta', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException $e) {
            Assert::same('a stay runs a full year, so a house opens all twelve months', $e->getMessage());
        }
    }],
    ['a house fits a stay that names it', static function () use ($all): void {
        $house = new House('valletta-bastion', 'Valletta', 'Malta', 4, $all);
        Assert::true($house->fits(new Stay('valletta-bastion', '2027-11-08', '2028-11-06', 4)));
    }],
    ['a house refuses a stay that names another house', static function () use ($all): void {
        $house = new House('valletta-bastion', 'Valletta', 'Malta', 4, $all);
        Assert::false($house->fits(new Stay('athens-plaka', '2027-11-08', '2028-11-06', 4)));
    }],
    ['a house refuses more people than beds', static function () use ($all): void {
        $house = new House('valletta-bastion', 'Valletta', 'Malta', 3, $all);
        Assert::false($house->fits(new Stay('valletta-bastion', '2027-11-08', '2028-11-06', 4)));
    }],
    ['a month outside the calendar is refused', static function (): void {
        try {
            new House('x', 'Y', 'Z', 2, [13]);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['the house hands its country and photograph to the page', static function () use ($all): void {
        $house = new House('sofia-vitosha', 'Sofia', 'Bulgaria', 5, $all, 'assets/photos/15-sofia-vitosha.jpg');
        $array = $house->toArray();
        Assert::same('Bulgaria', $array['country']);
        Assert::same('assets/photos/15-sofia-vitosha.jpg', $array['photo']);
    }],
];
