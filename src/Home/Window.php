<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * The rule the network runs on. A member may spend 182 days per calendar year
 * across the whole network, and the window counts those days per country,
 * because a tax authority reads the days per country and not the total. It
 * refuses a stay that overruns the year or pushes one country past its limit.
 */
final class Window
{
    public const DAYS_PER_YEAR = 182;

    public function __construct(
        public readonly int $year,
        public readonly int $limit = self::DAYS_PER_YEAR,
    ) {
        if ($year < 2000 || $year > 2100) {
            throw new \InvalidArgumentException('a window names one calendar year');
        }
        if ($limit < 1 || $limit > 366) {
            throw new \InvalidArgumentException('a limit runs from one day to a full year');
        }
    }

    public function start(): string
    {
        return sprintf('%04d-01-01', $this->year);
    }

    public function end(): string
    {
        return sprintf('%04d-01-01', $this->year + 1);
    }

    public function admits(Stay $stay, int $alreadyInCountry = 0): bool
    {
        if ($stay->from < $this->start() || $stay->to > $this->end()) {
            return false;
        }

        return $alreadyInCountry + $stay->nightsInYear($this->year) <= $this->limit;
    }

    public function left(Stay $stay, int $alreadyInCountry = 0): int
    {
        return max(0, $this->limit - $alreadyInCountry - $stay->nightsInYear($this->year));
    }
}
