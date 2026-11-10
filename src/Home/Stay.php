<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * One stay in one house. A stay names the house, the two dates and how many
 * people sleep there, and it refuses a range that runs backwards. The network
 * fixes the dates, so a stay always runs from 3 July to 30 June.
 */
final class Stay
{
    public function __construct(
        public readonly string $house,
        public readonly string $from,
        public readonly string $to,
        public readonly int $people,
    ) {
        if ($house === '') {
            throw new \InvalidArgumentException('a stay names its house');
        }
        if (!self::isDay($from) || !self::isDay($to)) {
            throw new \InvalidArgumentException('a stay carries two calendar days');
        }
        if ($to <= $from) {
            throw new \InvalidArgumentException('a stay ends after it starts');
        }
        if ($people < 1) {
            throw new \InvalidArgumentException('a stay holds at least one person');
        }
    }

    public static function isDay(string $value): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1;
    }

    public function nights(): int
    {
        $from = new \DateTimeImmutable($this->from);
        $to = new \DateTimeImmutable($this->to);

        return (int) $from->diff($to)->days;
    }

    public function covers(string $day): bool
    {
        return $day >= $this->from && $day < $this->to;
    }

    /**
     * How many days of the stay fall inside one calendar year. Both end days
     * count, because a tax authority counts the day a member arrives and the
     * day a member leaves. A stay that crosses New Year splits, and each year
     * carries only its own share.
     */
    public function nightsInYear(int $year): int
    {
        $first = sprintf('%04d-01-01', $year);
        $last = sprintf('%04d-12-31', $year);
        $start = max($this->from, $first);
        $end = min($this->to, $last);
        if ($end < $start) {
            return 0;
        }

        return (int) (new \DateTimeImmutable($start))->diff(new \DateTimeImmutable($end))->days + 1;
    }

    /**
     * The nights this stay puts inside the twelve months that end on a given
     * day. A tax authority counts backwards from each day of presence, so a
     * member who spreads one country across two calendar years still has to
     * keep the rolling total under the limit.
     */
    public function nightsInTrailingYear(string $day): int
    {
        $end = new \DateTimeImmutable($day);
        $start = $end->modify('-1 year');

        $from = max($this->from, $start->format('Y-m-d'));
        $to = min($this->to, $end->format('Y-m-d'));
        if ($to <= $from) {
            return 0;
        }

        return (int) (new \DateTimeImmutable($from))->diff(new \DateTimeImmutable($to))->days;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'house' => $this->house,
            'from' => $this->from,
            'to' => $this->to,
            'nights' => $this->nights(),
            'people' => $this->people,
        ];
    }
}
