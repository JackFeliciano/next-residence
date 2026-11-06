<?php

declare(strict_types=1);

namespace NextResidence\App;

use NextResidence\Home\Booking;
use NextResidence\Home\House;
use NextResidence\Home\Sample;

/**
 * The HTML. The base prefix is written into a base element so every relative
 * link keeps working behind a location block.
 */
final class Views
{
    public function __construct(private readonly string $base)
    {
    }

    private function h(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function page(string $title, string $body): string
    {
        return '<!doctype html>' . "\n"
            . '<html lang="en">' . "\n"
            . '<head>' . "\n"
            . '<meta charset="utf-8">' . "\n"
            . '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n"
            . '<title>' . $this->h($title) . '</title>' . "\n"
            . '<base href="' . $this->h($this->base) . '">' . "\n"
            . '<link rel="stylesheet" href="assets/site.css">' . "\n"
            . '</head>' . "\n"
            . '<body>' . "\n<main>
" . $body . '</main>' . "\n</body>\n</html>\n";
    }

    public function home(): string
    {
        $body = '<h1>The network</h1>' . "\n";
        $body .= '<p class="lead">Four houses on four coasts. A family moves between them as the year moves, and the network keeps the gap between two stays short.</p>' . "\n";
        $body .= '<table><thead><tr><th>House</th><th>City</th><th>Beds</th><th>Open months</th></tr></thead><tbody>' . "\n";
        foreach (Sample::houses() as $house) {
            $body .= $this->houseRow($house);
        }
        $body .= '</tbody></table>' . "\n";
        $body .= '<h2>Three bookings against one window</h2>' . "\n";
        $body .= '<p class="lead">The window runs from 2026-11-01 to 2026-12-01. One booking fits. The other two are refused, and each refusal names the condition that failed.</p>' . "\n";
        $body .= '<table><thead><tr><th>Stay</th><th>House</th><th>Nights</th><th>Answer</th></tr></thead><tbody>' . "\n";
        foreach (Sample::bookings() as $booking) {
            $body .= $this->bookingRow($booking);
        }

        return $this->page('The network', $body . '</tbody></table>' . "\n");
    }

    private function houseRow(House $house): string
    {
        return '<tr><td>' . $this->h($house->handle) . '</td><td>' . $this->h($house->city)
            . '</td><td>' . $house->beds . '</td><td>' . $this->h(implode(' ', $house->openMonths)) . '</td></tr>' . "\n";
    }

    private function bookingRow(Booking $booking): string
    {
        $answer = $booking->admits()
            ? '<span class="yes">admitted</span>'
            : '<span class="no">' . $this->h($booking->reason()) . '</span>';

        return '<tr><td>' . $this->h($booking->stay->from) . ' to ' . $this->h($booking->stay->to)
            . '</td><td>' . $this->h($booking->stay->house)
            . '</td><td>' . $booking->stay->nights()
            . '</td><td>' . $answer . '</td></tr>' . "\n";
    }

    public function notFound(string $path): string
    {
        return $this->page('Not found', '<h1>Not found</h1><p>' . $this->h($path) . '</p>');
    }
}
