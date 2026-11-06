<?php

declare(strict_types=1);

use NextResidence\Home\Booking;
use NextResidence\Home\House;
use NextResidence\Home\Sample;
use NextResidence\Home\Stay;
use NextResidence\Home\Window;
use NextResidence\Tests\Assert;

return [
    ['the first sample booking is admitted', static function (): void {
        Assert::true(Sample::bookings()[0]->admits());
        Assert::same('', Sample::bookings()[0]->reason());
    }],
    ['the second sample booking is refused for a closed month', static function (): void {
        Assert::false(Sample::bookings()[1]->admits());
        Assert::same('the house is closed in month 11', Sample::bookings()[1]->reason());
    }],
    ['the third sample booking is refused for beds', static function (): void {
        Assert::false(Sample::bookings()[2]->admits());
        Assert::same('the house has 4 beds', Sample::bookings()[2]->reason());
    }],
    ['a booking outside the window says so', static function (): void {
        $booking = new Booking(
            new Stay('lisbon-alfama', '2026-12-04', '2026-12-14', 3),
            Sample::house('lisbon-alfama'),
            new Window('2026-11-01', '2026-12-01'),
        );
        Assert::false($booking->admits());
        Assert::same('the stay falls outside the window', $booking->reason());
    }],
    ['the nights left follow the window', static function (): void {
        Assert::same(20, Sample::bookings()[0]->nightsLeft());
    }],
    ['four houses live in the sample', static function (): void {
        Assert::count(4, Sample::houses());
    }],
    ['an unknown house raises', static function (): void {
        try {
            Sample::house('nowhere');
            Assert::true(false, 'expected a rejection');
        } catch (\RuntimeException) {
            Assert::true(true);
        }
    }],
];
