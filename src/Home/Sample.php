<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * Houses and stays, invented. Every house stands in one of the fifteen
 * countries the network covers, and no house stands in a country outside that
 * list. The page runs five bookings against them so a member sees two admitted
 * and three refused, and each refusal names the condition that produced it.
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
            new House('tbilisi-mtkvari', 'Tbilisi', 'Georgia', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/01-tbilisi-mtkvari.jpg'),
            new House('panama-casco', 'Panama City', 'Panama', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/02-panama-casco.jpg'),
            new House('valletta-bastion', 'Valletta', 'Malta', 5, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/03-valletta-bastion.jpg'),
            new House('london-courtyard', 'London', 'England', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/04-london-courtyard.jpg'),
            new House('como-lake', 'Como', 'Italy', 8, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/05-como-lake.jpg'),
            new House('barcelona-gothic', 'Barcelona', 'Spain', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/06-barcelona-gothic.jpg'),
            new House('vienna-ring', 'Vienna', 'Austria', 5, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/07-vienna-ring.jpg'),
            new House('prague-old-town', 'Prague', 'Czechia', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/08-prague-old-town.jpg'),
            new House('budapest-buda', 'Budapest', 'Hungary', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/09-budapest-buda.jpg'),
            new House('bucharest-herastrau', 'Bucharest', 'Romania', 5, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/10-bucharest-herastrau.jpg'),
            new House('ljubljana-river', 'Ljubljana', 'Slovenia', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/11-ljubljana-river.jpg'),
            new House('split-diocletian', 'Split', 'Croatia', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/12-split-diocletian.jpg'),
            new House('belgrade-danube', 'Belgrade', 'Serbia', 4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/13-belgrade-danube.jpg'),
            new House('athens-plaka', 'Athens', 'Greece', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/14-athens-plaka.jpg'),
            new House('sofia-vitosha', 'Sofia', 'Bulgaria', 5, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'assets/photos/15-sofia-vitosha.jpg'),
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
            new Booking(new Stay('tbilisi-mtkvari', '2027-07-03', '2028-06-30', 4), self::house('tbilisi-mtkvari'), $year),
            new Booking(new Stay('vienna-ring', '2027-07-03', '2028-06-30', 4), self::house('vienna-ring'), $year),
            new Booking(new Stay('athens-plaka', '2027-07-02', '2028-06-30', 3), self::house('athens-plaka'), $year),
            new Booking(new Stay('panama-casco', '2027-07-03', '2028-06-30', 9), self::house('panama-casco'), $year),
            new Booking(new Stay('sofia-vitosha', '2027-07-03', '2028-06-30', 3), self::house('sofia-vitosha'), $year),
        ];
    }

    /** The smart stay the page shows in full: 3 July to 30 June, one country. */
    public static function smartStay(): Stay
    {
        return new Stay('tbilisi-mtkvari', '2027-07-03', '2028-06-30', 4);
    }
}
