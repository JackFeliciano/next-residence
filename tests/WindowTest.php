<?php

declare(strict_types=1);

use NextResidence\Home\Booking;
use NextResidence\Home\House;
use NextResidence\Home\Stay;
use NextResidence\Home\Window;
use NextResidence\Tests\Assert;

return [
    ['the window grants 182 days', static function (): void {
        Assert::same(182, (new Window(2027))->limit);
        Assert::same('2027-01-01', (new Window(2027))->start());
        Assert::same('2028-01-01', (new Window(2027))->end());
    }],
    ['the window admits a stay inside the year', static function (): void {
        $window = new Window(2027);
        Assert::true($window->admits(new Stay('tbilisi-mtkvari', '2027-03-01', '2027-04-01', 2)));
    }],
    ['the window refuses a stay that ends after the year', static function (): void {
        $window = new Window(2027);
        Assert::false($window->admits(new Stay('panama-casco', '2027-12-20', '2028-01-10', 2)));
    }],
    ['the window counts only the nights of its own year', static function (): void {
        $stay = new Stay('panama-casco', '2027-12-20', '2028-01-10', 2);
        Assert::same(12, $stay->nightsInYear(2027));
        Assert::same(9, $stay->nightsInYear(2028));
    }],
    ['a country holding 175 days takes 7 more and not 8', static function (): void {
        $window = new Window(2027);
        Assert::true($window->admits(new Stay('athens-plaka', '2027-05-01', '2027-05-08', 2), 175));
        Assert::false($window->admits(new Stay('athens-plaka', '2027-05-01', '2027-05-09', 2), 175));
    }],
    ['the days left fall as the country count rises', static function (): void {
        $window = new Window(2027);
        $stay = new Stay('athens-plaka', '2027-05-01', '2027-05-11', 2);
        Assert::same(172, $window->left($stay));
        Assert::same(102, $window->left($stay, 70));
    }],
    ['a limit outside the calendar is refused', static function (): void {
        try {
            new Window(2027, 400);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a booking names the country in its refusal', static function (): void {
        $booking = new Booking(
            new Stay('athens-plaka', '2027-05-01', '2027-05-09', 2),
            new House('athens-plaka', 'Athens', 'Greece', 6, [5]),
            new Window(2027),
            175,
        );
        Assert::same('the year holds no more days in Greece', $booking->reason());
    }],
];
