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
    ['the second sample booking is admitted', static function (): void {
        Assert::true(Sample::bookings()[1]->admits());
    }],
    ['the third sample booking is refused for a closed month in January', static function (): void {
        Assert::false(Sample::bookings()[2]->admits());
        Assert::same('the house is closed in month 01', Sample::bookings()[2]->reason());
    }],
    ['the fourth sample booking is refused for beds', static function (): void {
        Assert::false(Sample::bookings()[3]->admits());
        Assert::same('the house has 6 beds', Sample::bookings()[3]->reason());
    }],
    ['a booking outside the year says so', static function (): void {
        $booking = new Booking(
            new Stay('tbilisi-mtkvari', '2028-03-04', '2028-03-14', 3),
            Sample::house('tbilisi-mtkvari'),
            new Window(2027),
        );
        Assert::false($booking->admits());
        Assert::same('the stay falls outside the year 2027', $booking->reason());
    }],
    ['a country that already holds its days refuses one more night', static function (): void {
        $booking = new Booking(
            new Stay('tbilisi-mtkvari', '2027-03-04', '2027-03-20', 5),
            Sample::house('tbilisi-mtkvari'),
            new Window(2027),
            170,
        );
        Assert::false($booking->admits());
        Assert::same('the year holds no more days in Georgia', $booking->reason());
    }],
    ['the days left follow the country count', static function (): void {
        Assert::same(166, Sample::bookings()[0]->nightsLeft());
    }],
    ['fifteen houses live in the sample, one per country', static function (): void {
        Assert::count(15, Sample::houses());
        Assert::count(15, Sample::countries());
    }],
    ['every country in the sample belongs to the fifteen', static function (): void {
        foreach (Sample::countries() as $country) {
            Assert::true(in_array($country, Sample::COUNTRIES, true), $country . ' is outside the list');
        }
    }],
    ['no house stands outside the fifteen countries', static function (): void {
        Assert::count(15, Sample::countries());
    }],
    ['a stay that crosses New Year splits its nights', static function (): void {
        $stay = Sample::smartStay();
        Assert::same(51, $stay->nights());
        Assert::same(42, $stay->nightsInYear(2027));
        Assert::same(9, $stay->nightsInYear(2028));
        Assert::same(0, $stay->nightsInYear(2026));
    }],
    ['a crossing stay is refused by the year it starts in', static function (): void {
        $booking = new Booking(Sample::smartStay(), Sample::house('panama-casco'), new Window(2027));
        Assert::false($booking->admits());
        Assert::same('the stay falls outside the year 2027', $booking->reason());
    }],
    ['an unknown house raises', static function (): void {
        try {
            Sample::house('nowhere');
            Assert::true(false, 'expected a rejection');
        } catch (\RuntimeException) {
            Assert::true(true);
        }
    }],
    ['a house without a country is refused', static function (): void {
        try {
            new House('x', 'Y', '', 2, [1]);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
];
