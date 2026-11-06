<?php

declare(strict_types=1);

use NextResidence\Home\Stay;
use NextResidence\Home\Window;
use NextResidence\Tests\Assert;

return [
    ['the window runs two calendar years', static function (): void {
        $window = new Window(2027);
        Assert::same('2027-01-01', $window->start());
        Assert::same('2029-01-01', $window->end());
    }],
    ['a stay opens on the 183rd day and closes on the 182nd', static function (): void {
        $window = new Window(2027);
        Assert::same('2027-07-03', $window->opensOn());
        Assert::same('2028-07-02', $window->closesOn());
    }],
    ['a full year in one country is admitted', static function (): void {
        $window = new Window(2027);
        Assert::true($window->admits(new Stay('tbilisi-mtkvari', '2027-09-12', '2028-09-10', 2)));
    }],
    ['the earliest admitted stay starts on the opening day', static function (): void {
        $window = new Window(2027);
        Assert::true($window->admits(new Stay('tbilisi-mtkvari', '2027-07-03', '2028-07-01', 2)));
    }],
    ['the latest admitted stay starts in the last days of the year', static function (): void {
        $window = new Window(2027);
        Assert::true($window->admits(new Stay('tbilisi-mtkvari', '2027-12-20', '2028-12-18', 2)));
    }],
    ['one day short of a year is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-09-12', '2028-09-09', 2);
        Assert::false($window->admits($stay));
        Assert::same('a stay runs 364 days', $window->refusal($stay));
    }],
    ['a stay that starts in June is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-06-02', '2028-05-31', 2);
        Assert::false($window->admits($stay));
        Assert::same('a stay begins in the last 182 days of 2027', $window->refusal($stay));
    }],
    ['a stay that runs into a third year is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2028-01-02', '2029-01-01', 2);
        Assert::false($window->admits($stay));
        Assert::same('a stay ends inside 2028', $window->refusal($stay));
    }],
    ['a second stay in the same country is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-09-12', '2028-09-10', 2);
        Assert::same('the member already holds a stay in this country', $window->refusal($stay, 1));
    }],
    ['a year outside the calendar is refused', static function (): void {
        try {
            new Window(1990);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
];

