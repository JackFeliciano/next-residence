<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * One night in one house. A stay knows the house, the two dates and how many
 * people sleep there, and it refuses a range that runs backwards.
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
