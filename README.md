# NEXT Residence

NEXT Residence connects wealthy international families through a selective home exchange. Members book monthly stays in residences owned by other members across fifteen countries and can spend up to 182 days per calendar year across the network.

Each house has a name, city, country and availability window defined by month. Each booking identifies a house, member and month. The system accepts or rejects each booking and reports the first condition that fails.

A support desk covers tax residency, tax consulting, banking, financial services, insurance, health plans, home schooling, residence ships and investment property management.

NEXT Residence uses plain PHP 8.2, without dependencies or a database server.

## The three pieces

A house carries a name, a city, how many beds it holds and the months it opens.
A house in Kyoto opens in spring and autumn, so a traveller who wants it in
November learns it from the house and not from a support ticket.

A stay carries a house, two calendar days and a headcount. It refuses a range
that runs backwards, a range with no nights and a party of nobody, because each
of those arrives from a form somebody filled in badly.

A window is what the network promises: a traveller holds a stretch of days and
every stay has to fall inside it. The window reports how many nights are left
after a stay, so the next booking starts from the remainder.

## The booking

A booking puts a stay against a house and a window and answers yes only when all
three agree. When they do not, it names the first condition that failed:

    the stay names another house
    the house has 4 beds
    the house is closed in month 11
    the stay falls outside the window

A bare refusal teaches a traveller nothing. The reason is the whole point of the
class.

## Running it

    php tests/run.php      the suite, 27 checks, no dependencies
    php -S 127.0.0.1:8094 -t showroom showroom/index.php

Then open `http://127.0.0.1:8094/`. The prefix lives in NEXT_BASE, which
defaults to `/`.

Routes: `/` the network and three worked bookings, `/house/{handle}` one house,
`/health`.

## Layout

    src/Home/     House, Stay, Window, Booking, Sample
    src/App/      Router, Views, Front
    showroom/     the front controller and the stylesheet
    tests/        the runner, the assertions and five test files
    deploy/       the systemd unit and the nginx location

## The sample

Four invented houses and three invented bookings. One is admitted and two are
refused, one for a closed month and one for a party larger than the house. The
page shows all three so a reader sees the rule working in both directions.

## Licence

Mozilla Public License 2.0. See LICENSE.
