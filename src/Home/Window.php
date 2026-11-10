<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * The rule the network runs on. Every stay begins on 3 July and ends on 30
 * June of the following year, and the network keeps the four days between one
 * stay and the next to clean and repair the house. July 3 to December 31 puts
 * 182 days in the first calendar year, and January 1 to June 30 puts 182 in the
 * second. That keeps the member under the 183 days a tax authority counts, so
 * the member never becomes tax resident in the country that holds the house.
 * A stay on any other dates is refused.
 */
final class Window
{
    public const DAYS_PER_STAY = 364;
    public const DAYS_PER_HALF = 182;
    public const OPENS = '07-03';
    public const CLOSES = '06-30';

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
        return sprintf('%04d-', $this->year) . self::OPENS;
    }

    /** The last day a stay covers: 30 June of the following year. */
    public function closesOn(): string
    {
        return sprintf('%04d-', $this->year + 1) . self::CLOSES;
    }

    /** How many days of the stay fall in each of the two calendar years. */
    public function admits(Stay $stay, int $alreadyInCountry = 0): bool
    {
        return $this->refusal($stay, $alreadyInCountry) === '';
    }

    public function refusal(Stay $stay, int $alreadyInCountry = 0): string
    {
        if ($stay->from !== $this->opensOn()) {
            return 'a stay begins on 3 July ' . $this->year;
        }
        if ($stay->to !== $this->closesOn()) {
            return 'a stay ends on 30 June ' . ($this->year + 1);
        }
        if ($alreadyInCountry > 0) {
            return 'the member already holds a stay in this country';
        }

        return '';
    }
}
