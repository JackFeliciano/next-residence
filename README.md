# NEXT Residence

NEXT Residence is a network of homes for perpetual traveler families living the Flag Theory way of life. A member takes one house in one country for a year at a time and lives abroad without becoming tax resident there. The network spans fifteen countries. The project models houses, stays, booking windows and booking decisions, and provides a small web demo of the rules.

## The stay model

Every stay runs from 3 July to 30 June of the following year, 364 days in one country. The dates are fixed. July 3 to December 31 gives 182 days in one calendar year, and January 1 to June 30 gives 181 days in the next, or 182 when the year begins with a leap day. The four days between June 30 and July 3 let the network clean and repair the house. The member therefore spends no more than 182 days in either year, which keeps them under the 183-day rule tax authorities apply. A member holds one stay per country at a time.

The model uses five main types:

    House carries a handle, city, country, bed count and photograph.
    Stay carries a house, two calendar days and a headcount.
    Window holds the two calendar years a stay may touch.
    Booking puts a stay against a house and a window.
    Sample provides the example houses and bookings used by the demo.

A booking checks the conditions in order and names the first condition that refuses it. This gives the member a clear reason for the decision.

The refusal reasons cover a stay that names another house, a house with 4 beds, an incorrect stay length, a start outside the permitted part of the year, an end outside the following year, and an existing stay held by the member in the same country.

## Project structure

    src/
      Home/
        House
        Stay
        Window
        Booking
        Sample
      App/
        Router
        Views
        Front
    showroom/
      front controller
      stylesheet
    tests/
      runner
      assertions
      four test files
    deploy/
      systemd unit
      nginx location

src/Home/ contains the booking model and its rules. src/App/ contains the router and presentation layer. showroom/ contains the web entry point and stylesheet. tests/ contains the test runner, assertions and four test files. deploy/ contains the systemd unit and nginx location.

## Requirements

The project uses plain PHP 8.2. It has no framework, database, Composer setup or external dependencies.

## Run the tests

Run the complete test suite from the project root:

    php tests/run.php

The suite contains 41 checks and needs no dependencies.

## Run the demo

Start the built-in PHP server:

    php -S 127.0.0.1:8094 -t showroom showroom/index.php

The demo uses NEXT_BASE as its URL prefix. It defaults to /.

The available routes are:

    / displays the network and five worked bookings.
    /house/{handle} displays one house.
    /health provides the health route.

## Sample data

The sample contains fifteen houses in fifteen countries and five bookings. Two bookings are admitted and three are refused. The worked examples show how the booking rules handle both outcomes.

## Licence

NEXT Residence is licensed under the Mozilla Public License 2.0. See the licence file for the terms.
