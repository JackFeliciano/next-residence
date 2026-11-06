<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * The rule the network runs on. A member books one stay of 364 days in one
 * country: it begins in the last 182 days of one calendar year and ends in the
 * first 182 days of the next. That keeps the member under the 183 days a tax
 * authority counts in any twelve months, so the member never becomes tax
 * resident in the country that holds the house. A shorter stay leaves days
 * unused in both years, and the network refuses it.
 */
final class Window
{
    public const DAYS_PER_STAY = 364;
    public const DAYS_PER_HALF = 182;

    public function __construct(
        public readonly int $year,
    ) {
        if ($year < 2000 || $year > 2100) {
            throw new \InvalidArgumentException('a window names one calendar year');
        }
    }

    public function start(): string
    {
        return sprintf('%04d-01-01', $this->year);
    }

    public function end(): string
    {
        return sprintf('%04d-01-01', $this->year + 2);
    }

    /** The first day a stay may book: the 183rd day of the year. */
    public function opensOn(): string
    {
        return sprintf('%04d-07-03', $this->year);
    }

    /** The last day a stay may book: the 182nd day of the following year. */
    public function closesOn(): string
    {
        return sprintf('%04d-07-02', $this->year + 1);
    }

    /** How many days of the stay fall in each of the two calendar years. */
    public function admits(Stay $stay, int $alreadyInCountry = 0): bool
    {
        return $this->refusal($stay, $alreadyInCountry) === '';
    }

    public function refusal(Stay $stay, int $alreadyInCountry = 0): string
    {
        if ($stay->nights() < self::DAYS_PER_STAY) {
            return 'a stay runs 364 days';
        }
        if ($stay->from < $this->opensOn()) {
            return 'a stay begins in the last 182 days of ' . $this->year;
        }
        if ($stay->to >= sprintf('%04d-01-01', $this->year + 2)) {
            return 'a stay ends inside ' . ($this->year + 1);
        }
        if ($alreadyInCountry > 0) {
            return 'the member already holds a stay in this country';
        }

        return '';
    }
}
