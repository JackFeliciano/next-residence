<?php

declare(strict_types=1);

namespace NextResidence\Home;

/**
 * A stay placed against a house and a window. The booking answers yes only when
 * all three agree, and it says which one disagreed, because a traveller who is
 * told no learns nothing from a bare refusal.
 */
final class Booking
{
    public function __construct(
        public readonly Stay $stay,
        public readonly House $house,
        public readonly Window $window,
    ) {
    }

    public function admits(): bool
    {
        return $this->house->fits($this->stay) && $this->window->admits($this->stay);
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
        if (!$this->house->opensOn($this->stay->from)) {
            return 'the house is closed in month ' . substr($this->stay->from, 5, 2);
        }
        if (!$this->window->admits($this->stay)) {
            return 'the stay falls outside the window';
        }

        return '';
    }

    public function nightsLeft(): int
    {
        return $this->window->left($this->stay);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'stay' => $this->stay->toArray(),
            'house' => $this->house->toArray(),
            'window' => ['from' => $this->window->from, 'to' => $this->window->to, 'nights' => $this->window->nights()],
            'admitted' => $this->admits(),
            'reason' => $this->reason(),
            'nights_left' => $this->nightsLeft(),
        ];
    }
}
