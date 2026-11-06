<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * A stay placed against a house and a year. The booking answers yes only when
 * all three agree, and it says which one disagreed, because a member who is
 * told no learns nothing from a bare refusal. The house is read first, so a
 * closed month or a party too large reaches the member before the calendar.
 */
final class Booking
{
    public function __construct(
        public readonly Stay $stay,
        public readonly House $house,
        public readonly Window $window,
        public readonly int $daysAlreadyInCountry = 0,
    ) {
    }

    public function admits(): bool
    {
        return $this->house->fits($this->stay)
            && $this->window->admits($this->stay, $this->daysAlreadyInCountry);
    }

    /** The first condition that failed, or an empty string when nothing did. */
    public function reason(): string
    {
        if ($this->stay->house !== $this->house->handle) {
            return 'the stay names another house';
        }
        if ($this->stay->people > $this->house->beds) {
            return 'the house has ' . $this->house->beds . ' beds';
        }
        return $this->window->refusal($this->stay, $this->daysAlreadyInCountry);
    }

    /** Nights the stay leaves unused in the two calendar years it touches. */
    public function nightsLeft(): int
    {
        return $this->stay->nightsInYear($this->window->year)
            + $this->stay->nightsInYear($this->window->year + 1) - Window::DAYS_PER_STAY;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'stay' => $this->stay->toArray(),
            'house' => $this->house->toArray(),
            'window' => ['year' => $this->window->year, 'days' => Window::DAYS_PER_STAY],
            'admitted' => $this->admits(),
            'reason' => $this->reason(),
            'nights_left' => $this->nightsLeft(),
        ];
    }
}
