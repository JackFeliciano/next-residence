<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * Houses and stays, invented. Every house stands in one of the fifteen
 * countries the network covers, and no house stands in a country outside that
 * list. The page runs four bookings against them so a member sees some
 * admitted and some refused with the condition that refused them.
 */
final class Sample
{
    /** The fifteen countries the network covers. */
    public const COUNTRIES = [
        'Georgia', 'Panama', 'Malta', 'England', 'Italy', 'Spain', 'Austria',
        'Czechia', 'Hungary', 'Romania', 'Slovenia', 'Croatia', 'Serbia',
        'Greece', 'Bulgaria',
    ];

    /** @return list<House> */
    public static function houses(): array
    {
        return [
            new House('tbilisi-mtkvari', 'Tbilisi', 'Georgia', 6, [3, 4, 5, 6, 9, 10], 'house-1.jpg'),
            new House('panama-casco', 'Panama City', 'Panama', 4, [1, 2, 3, 11, 12], 'house-2.jpg'),
            new House('valletta-bastion', 'Valletta', 'Malta', 5, [4, 5, 6, 7, 8, 9], 'house-3.jpg'),
            new House('london-courtyard', 'London', 'England', 6, [1, 2, 6, 7, 8, 12], 'house-4.jpg'),
            new House('como-lake', 'Como', 'Italy', 8, [5, 6, 7, 8, 9], 'house-5.jpg'),
            new House('barcelona-gothic', 'Barcelona', 'Spain', 4, [3, 4, 5, 9, 10, 11], 'house-6.jpg'),
            new House('vienna-ring', 'Vienna', 'Austria', 5, [1, 2, 3, 11, 12], 'house-7.jpg'),
            new House('prague-old-town', 'Prague', 'Czechia', 6, [4, 5, 6, 9, 10], 'house-8.jpg'),
            new House('budapest-buda', 'Budapest', 'Hungary', 4, [3, 4, 5, 10, 11], 'house-9.jpg'),
            new House('bucharest-herastrau', 'Bucharest', 'Romania', 5, [5, 6, 7, 8, 9], 'house-10.jpg'),
            new House('ljubljana-river', 'Ljubljana', 'Slovenia', 4, [6, 7, 8, 9], 'house-11.jpg'),
            new House('split-diocletian', 'Split', 'Croatia', 6, [5, 6, 7, 8, 9, 10], 'house-12.jpg'),
            new House('belgrade-danube', 'Belgrade', 'Serbia', 4, [4, 5, 6, 9, 10], 'house-13.jpg'),
            new House('athens-plaka', 'Athens', 'Greece', 6, [3, 4, 5, 6, 9, 10, 11], 'house-14.jpg'),
            new House('sofia-vitosha', 'Sofia', 'Bulgaria', 5, [6, 7, 8, 9, 12], 'house-15.jpg'),
        ];
    }

    public static function house(string $handle): House
    {
        foreach (self::houses() as $house) {
            if ($house->handle === $handle) {
                return $house;
            }
        }

        throw new \RuntimeException('no house called ' . $handle);
    }

    /** @return list<string> */
    public static function cities(): array
    {
        return array_map(static fn (House $house): string => $house->city, self::houses());
    }

    /** @return list<string> */
    public static function countries(): array
    {
        $seen = [];
        foreach (self::houses() as $house) {
            $seen[$house->country] = true;
        }

        return array_keys($seen);
    }

    /** @return list<Booking> */
    public static function bookings(): array
    {
        $year = new Window(2027);

        return [
            new Booking(new Stay('tbilisi-mtkvari', '2027-03-04', '2027-03-20', 5), self::house('tbilisi-mtkvari'), $year),
            new Booking(new Stay('sofia-vitosha', '2027-07-02', '2027-07-12', 4), self::house('sofia-vitosha'), $year),
            new Booking(new Stay('como-lake', '2027-01-08', '2027-01-18', 4), self::house('como-lake'), $year),
            new Booking(new Stay('athens-plaka', '2027-09-01', '2027-09-15', 8), self::house('athens-plaka'), $year),
        ];
    }

    /** A smart stay that runs from October into the next calendar year. */
    public static function smartStay(): Stay
    {
        return new Stay('panama-casco', '2027-11-20', '2028-01-10', 4);
    }
}
