<?php

declare(strict_types=1);

use NextResidence\Home\Booking;
use NextResidence\Home\House;
use NextResidence\Home\Sample;
use NextResidence\Home\Stay;
use NextResidence\Home\Window;
use NextResidence\Tests\Assert;

return [
    ['the first sample booking fills a year in one country', static function (): void {
        Assert::true(Sample::bookings()[0]->admits());
        Assert::same('', Sample::bookings()[0]->reason());
        Assert::same(364, Sample::bookings()[0]->stay->nights());
    }],
    ['the second sample booking fills a year in one country', static function (): void {
        Assert::true(Sample::bookings()[1]->admits());
        Assert::same(364, Sample::bookings()[1]->stay->nights());
    }],
    ['a booking shorter than 364 days is refused', static function (): void {
        Assert::false(Sample::bookings()[2]->admits());
        Assert::same('a stay runs 364 days', Sample::bookings()[2]->reason());
    }],
    ['a party larger than the house is refused for beds', static function (): void {
        Assert::false(Sample::bookings()[3]->admits());
        Assert::same('the house has 4 beds', Sample::bookings()[3]->reason());
    }],
    ['a stay that runs into a third year is refused', static function (): void {
        $window = new Window(2027);
        $booking = new Booking(
            new Stay('sofia-vitosha', '2028-01-02', '2029-01-01', 3),
            Sample::house('sofia-vitosha'),
            $window,
        );
        Assert::false($booking->admits());
        Assert::same('a stay ends inside 2028', $booking->reason());
    }],
    ['a stay of exactly 364 nights is admitted', static function (): void {
        $booking = new Booking(
            new Stay('valletta-bastion', '2027-07-03', '2028-07-01', 2),
            Sample::house('valletta-bastion'),
            new Window(2027),
        );
        Assert::true($booking->admits());
        Assert::same(364, $booking->stay->nights());
        Assert::same(0, $booking->nightsLeft());
    }],
    ['a stay that begins before July is refused', static function (): void {
        $booking = new Booking(
            new Stay('tbilisi-mtkvari', '2027-05-04', '2028-05-02', 3),
            Sample::house('tbilisi-mtkvari'),
            new Window(2027),
        );
        Assert::false($booking->admits());
        Assert::same('a stay begins in the last 182 days of 2027', $booking->reason());
    }],
    ['a stay that runs into a third year is refused', static function (): void {
        $booking = new Booking(
            new Stay('sofia-vitosha', '2028-01-02', '2029-01-01', 3),
            Sample::house('sofia-vitosha'),
            new Window(2027),
        );
        Assert::false($booking->admits());
        Assert::same('a stay ends inside 2028', $booking->reason());
    }],
    ['a country that already holds a stay refuses another', static function (): void {
        $booking = new Booking(
            new Stay('tbilisi-mtkvari', '2027-09-12', '2028-09-10', 5),
            Sample::house('tbilisi-mtkvari'),
            new Window(2027),
            1,
        );
        Assert::false($booking->admits());
        Assert::same('the member already holds a stay in this country', $booking->reason());
    }],
    ['the stay the page shows in full runs 364 days across two years', static function (): void {
        $stay = Sample::smartStay();
        Assert::same(364, $stay->nights());
        Assert::same(42, $stay->nightsInYear(2027));
        Assert::same(322, $stay->nightsInYear(2028));
        Assert::same(0, $stay->nightsInYear(2029));
    }],
    ['the smart stay is admitted by the year it starts in', static function (): void {
        $booking = new Booking(Sample::smartStay(), Sample::house('tbilisi-mtkvari'), new Window(2027));
        Assert::true($booking->admits());
        Assert::same(0, $booking->nightsLeft());
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
            new House('x', 'Y', '', 2, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
            Assert::true(false, 'expected a rejection');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['fifteen houses live in the sample, one per country', static function (): void {
        Assert::count(15, Sample::houses());
        Assert::count(15, Sample::countries());
    }],
    ['no house stands outside the fifteen countries', static function (): void {
        foreach (Sample::countries() as $country) {
            Assert::true(in_array($country, Sample::COUNTRIES, true), $country . ' is outside the list');
        }
    }],
];
