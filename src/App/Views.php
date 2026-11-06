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
        $body = '<h1>' . $this->h('NEXT Residence Network') . '</h1>' . "\n";
        $body .= '<p class="led">' . $this->h('NEXT Residence gives wealthy international families access to privately owned homes across fifteen countries through a selective home exchange. Members book stays month by month in other members\' residences and spend up to 182 days per calendar year across the network. A dedicated support desk helps members coordinate the financial, legal, educational and practical demands of international living.') . '</p>' . "\n";
        $body .= '<h2>' . $this->h('The Fifteen Countries') . '</h2>' . "\n";
        $body .= '<p>' . $this->h('The network connects member-owned residences across Georgia, Panama, Malta, England, Italy, Spain, Austria, Czechia, Hungary, Romania, Slovenia, Croatia, Serbia, Greece and Bulgaria.') . '</p>' . "\n";
        $body .= '<h2>Houses in the Network</h2>' . "\n";
        $body .= '<table><thead><tr><th>House</th><th>City</th><th>Beds</th><th>Open months</th></tr></thead><tbody>' . "\n";
        foreach (Sample::houses() as $house) {
            $body .= $this->houseRow($house);
        }
        $body .= '</tbody></table>' . "\n";
        $body .= '<h2>Support Desk</h2>' . "\n" . '<ul class="services">' . "\n";
        foreach ([
            ['Tax residency', 'Guidance on tax residency across countries and the consequences of international mobility.'],
            ['Tax consulting', 'Tax consulting for members with homes and interests across multiple jurisdictions.'],
            ['Banking and financial services', 'Access to banking and financial services for an international lifestyle.'],
            ['Insurance plans', 'Insurance plans that support members and their international lives.'],
            ['Health plans', 'Health plans for members and their families across countries.'],
            ['Home schooling', 'Schooling support for children who study at home.'],
            ['Residence ships', 'Residence ship options for members who extend their lives beyond land-based homes.'],
            ['Property investment management', 'Management of members\' own properties as investments.'],
        ] as [$serviceName, $serviceLine]) {
            $body .= '<li><strong>' . $this->h($serviceName) . '</strong> ' . $this->h($serviceLine) . '</li>' . "\n";
        }
        $body .= '</ul>' . "\n";
        $body .= '<h2>' . $this->h('The 182 Day Rule') . '</h2>' . "\n";
        $body .= '<p>' . $this->h('Members can spend up to 182 days per calendar year across the residence network. The system counts booked days separately for each country and tracks the calendar year. It warns members before they cross a limit, helping them understand the tax residency consequences of their travel.') . '</p>' . "\n";
        $body .= '<h2>The Window and Three Bookings</h2>' . "\n";
        $body .= '<p>The window runs from 2026-11-01 to 2026-12-01. One booking fits. The other two are refused, and each refusal names the condition that failed.</p>' . "\n";
        $body .= '<table><thead><tr><th>Stay</th><th>House</th><th>Nights</th><th>Answer</th></tr></thead><tbody>' . "\n";
        foreach (Sample::bookings() as $booking) {
            $body .= $this->bookingRow($booking);
        }

        return $this->page('NEXT Residence Network', $body . '</tbody></table>' . "\n");
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
