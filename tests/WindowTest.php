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
    ['a stay opens on 3 July and closes on 30 June', static function (): void {
        $window = new Window(2027);
        Assert::same('2027-07-03', $window->opensOn());
        Assert::same('2028-06-30', $window->closesOn());
    }],
    ['the fixed dates carry 182 days in each calendar year', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-07-03', '2028-06-30', 2);
        Assert::true($window->admits($stay));
        Assert::same('', $window->refusal($stay));
        Assert::same(182, $stay->nightsInYear(2027));
        Assert::same(182, $stay->nightsInYear(2028));
    }],
    ['the fixed dates hold across a leap year', static function (): void {
        $window = new Window(2028);
        $stay = new Stay('tbilisi-mtkvari', '2028-07-03', '2029-06-30', 2);
        Assert::same('', $window->refusal($stay));
        Assert::same(182, $stay->nightsInYear(2028));
        Assert::same(181, $stay->nightsInYear(2029));
    }],
    ['a stay that starts before 3 July is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-07-02', '2028-06-30', 2);
        Assert::false($window->admits($stay));
        Assert::same('a stay begins on 3 July 2027', $window->refusal($stay));
    }],
    ['a stay that ends after 30 June is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-07-03', '2028-07-01', 2);
        Assert::false($window->admits($stay));
        Assert::same('a stay ends on 30 June 2028', $window->refusal($stay));
    }],
    ['a second stay in the same country is refused', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('tbilisi-mtkvari', '2027-07-03', '2028-06-30', 2);
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

