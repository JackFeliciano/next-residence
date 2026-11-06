<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * One stay in one house. A stay names the house, the two dates and how many
 * people sleep there, and it refuses a range that runs backwards. A stay can
 * cross a year boundary, because members plan twelve months at a time.
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
     * How many of the stay's nights fall inside one calendar year. A stay that
     * crosses New Year splits, and each year carries only its own share.
     */
    public function nightsInYear(int $year): int
    {
        $start = max($this->from, sprintf('%04d-01-01', $year));
        $end = min($this->to, sprintf('%04d-01-01', $year + 1));
        if ($end <= $start) {
            return 0;
        }

        return (int) (new \DateTimeImmutable($start))->diff(new \DateTimeImmutable($end))->days;
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
