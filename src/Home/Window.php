<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * The rule the network runs on. A family moves between houses as it moves
 * between countries, and a window is the promise that a gap between two stays
 * stays short. The window holds the nights it grants and refuses a stay that
 * overruns what is left.
 */
final class Window
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
    ) {
        if (!Stay::isDay($from) || !Stay::isDay($to) || $to <= $from) {
            throw new \InvalidArgumentException('a window needs two ordered days');
        }
    }

    public function nights(): int
    {
        return (int) (new \DateTimeImmutable($this->from))->diff(new \DateTimeImmutable($this->to))->days;
    }

    public function admits(Stay $stay): bool
    {
        return $stay->from >= $this->from && $stay->to <= $this->to;
    }

    public function left(Stay $stay): int
    {
        return max(0, $this->nights() - $stay->nights());
    }
}
