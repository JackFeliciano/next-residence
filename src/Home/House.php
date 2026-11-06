<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * A house a member has put in the network. It carries the country it stands in,
 * the city, how many people sleep there and the months it opens. The photograph
 * is a file name only. Nothing here says who owns it.
 */
final class House
{
    /** @param list<int> $openMonths */
    public function __construct(
        public readonly string $handle,
        public readonly string $city,
        public readonly string $country,
        public readonly int $beds,
        public readonly array $openMonths,
        public readonly string $photo = '',
    ) {
        if ($handle === '' || $city === '' || $country === '') {
            throw new \InvalidArgumentException('a house names itself, its city and its country');
        }
        if ($beds < 1) {
            throw new \InvalidArgumentException('a house has at least one bed');
        }
        if ($openMonths === []) {
            throw new \InvalidArgumentException('a house opens at least one month');
        }
        foreach ($openMonths as $month) {
            if ($month < 1 || $month > 12) {
                throw new \InvalidArgumentException('a month runs from one to twelve');
            }
        }
    }

    public function opensOn(string $day): bool
    {
        return in_array((int) substr($day, 5, 2), $this->openMonths, true);
    }

    public function fits(Stay $stay): bool
    {
        return $stay->house === $this->handle && $stay->people <= $this->beds && $this->opensOn($stay->from);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'handle' => $this->handle,
            'city' => $this->city,
            'country' => $this->country,
            'beds' => $this->beds,
            'open_months' => $this->openMonths,
            'photo' => $this->photo,
        ];
    }
}
