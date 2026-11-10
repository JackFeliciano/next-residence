<?php

declare(strict_types=1);

namespace NextResidence\App;

use NextResidence\Home\Booking;
use NextResidence\Home\House;
use NextResidence\Home\Sample;
use NextResidence\Home\Stay;

/**
 * The HTML. The base prefix is written into a base element so every relative
 * link keeps working behind a location block. The page reads as a residence
 * catalog: a search bar, filter chips, a grid of houses and the rules beneath.
 * The prose comes from the copy file written by the writing hand.
 */
final class Views
{
    /** @var array<string, mixed> */
    private array $copy;

    private int $year = 2027;

    public function __construct(private readonly string $base)
    {
        $file = dirname(__DIR__, 2) . '/copy/next.json';
        $this->copy = is_file($file)
            ? (array) json_decode((string) file_get_contents($file), true)
            : [];
    }

    private function h(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private function c(string $key, string $fallback = ''): string
    {
        $value = $this->copy[$key] ?? $fallback;

        return is_string($value) ? $value : $fallback;
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
            . '<body>' . "\n" . '<main>' . "\n" . $body . '</main>' . "\n"
            . '<script src="assets/app.js"></script>' . "\n"
            . '</body>' . "\n</html>\n";
    }

    public function home(): string
    {
        $body = '<header class="top">' . "\n"
            . '<span class="brand">NEXT Residence</span>' . "\n"
            . '<span class="count">' . count(Sample::houses()) . ' houses in ' . count(Sample::COUNTRIES) . ' countries</span>' . "\n"
            . '</header>' . "\n";

        $body .= '<form class="search" role="search" onsubmit="return false">' . "\n"
            . '<input id="q" type="search" autocomplete="off" placeholder="' . $this->h($this->c('placeholder', 'Search')) . '">' . "\n"
            . '</form>' . "\n";

        $body .= '<nav class="chips" aria-label="Filters">' . "\n";
        foreach (Sample::COUNTRIES as $country) {
            $body .= '<button type="button" class="chip" data-country="' . $this->h($country) . '">' . $this->h($country) . '</button>' . "\n";
        }
        $body .= '</nav>' . "\n";

        $body .= '<p class="countryline">' . $this->h($this->c('countryline')) . '</p>' . "\n";

        $body .= '<div class="grid" id="grid">' . "\n";
        foreach (Sample::houses() as $house) {
            $body .= $this->card($house);
        }
        $body .= '</div>' . "\n";

        $body .= '<p class="empty" id="empty" hidden>' . $this->h($this->c('empty')) . '</p>' . "\n";

        $body .= '<p class="creditline"><a href="credits">Photographs</a>: Wikimedia Commons, each file under its own licence.</p>' . "\n";

        $body .= '<p class="creditline"><a href="../credits">Photographs</a>: Wikimedia Commons, each file under its own licence.</p>' . "\n";

        $body .= '<section class="rules">' . "\n";
        $body .= '<h2>Annual stays, and the year they cross</h2>' . "\n";
        $body .= '<p>' . $this->h($this->c('stays')) . '</p>' . "\n";
        $body .= '<h2>182 days, counted per country</h2>' . "\n";
        $body .= '<p>' . $this->h($this->c('rule')) . '</p>' . "\n";
        $body .= '</section>' . "\n";

        $body .= '<section class="desk">' . "\n";
        $body .= '<h2>The support desk</h2>' . "\n";
        $body .= '<p>' . $this->h($this->c('support')) . '</p>' . "\n";
        $body .= '</section>' . "\n";

        $body .= '<section class="bookings">' . "\n";
        $body .= '<h2>Five bookings against the year 2027</h2>' . "\n";
        $body .= '<p class="note">Each request asks for a full year in one country. The booking answers with the first condition that failed.</p>' . "\n";
        $body .= '<table><thead><tr><th>Stay</th><th>House</th><th>Country</th><th>Nights</th><th>Answer</th></tr></thead><tbody>' . "\n";
        foreach (Sample::bookings() as $booking) {
            $body .= $this->bookingRow($booking);
        }
        $body .= '</tbody></table>' . "\n";
        $body .= '<h2>One smart stay, read day by day</h2>' . "\n";
        $body .= $this->crossingRow(Sample::smartStay(), 2027) . "\n";
        $body .= '</section>' . "\n";

        return $this->page('NEXT Residence', $body);
    }

    private function card(House $house): string
    {

        return '<article class="card" data-city="' . $this->h($house->city) . '" data-country="' . $this->h($house->country) . '" data-name="' . $this->h($house->handle) . '">' . "\n"
            . '<figure class="photo"><img src="' . $this->h($house->photo) . '" alt="' . $this->h($house->city . ', ' . $house->country) . '" loading="lazy" width="1200" height="900">'
            . '<figcaption>' . $this->h($house->country) . '</figcaption></figure>' . "\n"
            . '<h3>' . $this->h($house->city) . '</h3>' . "\n"
            . '<p class="where">' . $this->h($house->country) . ' <span class="dot">.</span> ' . $house->beds . ' beds</p>' . "\n"
            . '<p class="span">' . $this->h($this->stayLine($house)) . '</p>' . "\n"
            . '<a class="open" href="house/' . $this->h($house->handle) . '">See the house</a>' . "\n"
            . '</article>' . "\n";
    }

    /** The stay window each card carries, so a house reads as bookable. */
    private function stayLine(House $house): string
    {
        return 'One stay, 3 July to 30 June, in ' . $this->year . ' and ' . ($this->year + 1);
    }

    private function bookingRow(Booking $booking): string
    {
        $answer = $booking->admits()
            ? '<span class="yes">admitted</span>'
            : '<span class="no">' . $this->h($booking->reason()) . '</span>';

        return '<tr><td>' . $this->h($booking->stay->from) . ' to ' . $this->h($booking->stay->to)
            . '</td><td>' . $this->h($booking->house->city)
            . '</td><td>' . $this->h($booking->house->country)
            . '</td><td>' . $booking->stay->nights()
            . '</td><td>' . $answer . '</td></tr>' . "\n";
    }

    private function crossingRow(Stay $stay, int $year): string
    {
        return '<p class="crossing">This stay runs from ' . $this->h($stay->from) . ' to ' . $this->h($stay->to) . ', with '
            . $stay->nightsInYear($year) . ' days in ' . $year . ' and '
            . $stay->nightsInYear($year + 1) . ' days in ' . ($year + 1) . '.</p>';
    }

    public function house(House $house): string
    {
        $body = '<header class="top">' . "\n"
            . '<span class="brand"><a href="./">NEXT Residence</a></span>' . "\n"
            . '</header>' . "\n";
        $body .= '<figure class="photo big"><img src="' . $this->h($house->photo) . '" alt="' . $this->h($house->city . ', ' . $house->country) . '" width="1200" height="900">'
            . '<figcaption>' . $this->h($house->country) . '</figcaption></figure>' . "\n";
        $body .= '<h1>' . $this->h($house->city) . '</h1>' . "\n";
        $body .= '<p class="where">' . $this->h($house->country) . ' <span class="dot">.</span> ' . $house->beds . ' beds</p>' . "\n";
        $body .= '<p class="months">One stay, from 3 July to 30 June, in ' . $this->year . ' and ' . ($this->year + 1) . '.</p>' . "\n";
        $body .= '<p>Members book this house through the network office. The calendar runs twelve months at a time, and a stay may cross into the next calendar year. Each calendar year holds 182 days in this country.</p>' . "\n";

        return $this->page($house->city . ', ' . $house->country, $body);
    }

    /**
     * The photograph credits. Wikimedia licences ask for the author and the
     * licence wherever the picture appears, so the page carries both.
     */
    public function credits(): string
    {
        $file = dirname(__DIR__, 2) . '/showroom/assets/photos/credits.json';
        $items = is_file($file) ? (array) json_decode((string) file_get_contents($file), true) : [];

        $body = '<header class="top">' . "\n"
            . '<span class="brand"><a href="../">NEXT Residence</a></span>' . "\n"
            . '</header>' . "\n";

        $body .= '<div class="credits">' . "\n";
        $body .= '<h1>Photographs</h1>' . "\n";
        $body .= '<p class="back">Every photograph on this site comes from Wikimedia Commons and stays under the licence the author chose. The house names each work, its author, the licence, and the page it came from.</p>' . "\n";
        $body .= '<ul>' . "\n";
        foreach ($items as $item) {
            $title = str_replace('File:', '', (string) ($item['title'] ?? ''));
            $body .= '<li>' . $this->h($title) . ', ' . $this->h((string) ($item['artist'] ?? ''))
                . '. ' . $this->h((string) ($item['license'] ?? '')) . '. '
                . '<a href="' . $this->h((string) ($item['url'] ?? '#')) . '">Wikimedia Commons</a></li>' . "\n";
        }
        $body .= '</ul>' . "\n";
        $body .= '</div>' . "\n";

        return $this->page('Photographs', $body);
    }

    public function notFound(string $path): string
    {
        return $this->page('Not found', '<h1>Not found</h1><p>' . $this->h($path) . '</p>');
    }
}
