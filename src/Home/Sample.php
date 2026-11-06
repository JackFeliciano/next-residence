<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * Four houses and one traveller, invented. The page runs three bookings against
 * them so a reader sees one accepted and two refused with a reason.
 */
final class Sample
{
    /** @return list<House> */
    public static function houses(): array
    {
        return [
            new House('lisbon-alfama', 'Lisbon', 6, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]),
            new House('madeira-cliff', 'Funchal', 4, [4, 5, 6, 7, 8, 9, 10]),
            new House('kyoto-machiya', 'Kyoto', 5, [3, 4, 5, 9, 10]),
            new House('valparaiso-hill', 'Valparaiso', 8, [1, 2, 3, 11, 12]),
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

    /** @return list<Booking> */
    public static function bookings(): array
    {
        $window = new Window('2026-11-01', '2026-12-01');

        return [
            new Booking(new Stay('lisbon-alfama', '2026-11-04', '2026-11-14', 5), self::house('lisbon-alfama'), $window),
            new Booking(new Stay('kyoto-machiya', '2026-11-10', '2026-11-20', 4), self::house('kyoto-machiya'), $window),
            new Booking(new Stay('madeira-cliff', '2026-11-08', '2026-11-12', 6), self::house('madeira-cliff'), $window),
        ];
    }
}
