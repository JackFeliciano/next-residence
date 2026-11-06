# NEXT Residence

NEXT Residence is a selective home exchange network for international families. Members book stays in houses held by the network across fifteen countries. The project models houses, stays, booking windows and booking decisions, and provides a small web demo of the rules.

## The stay model

Every stay runs exactly 364 days in one country and crosses a calendar year boundary. It begins within the last 182 days of one calendar year and ends within the first 182 days of the next.

This structure keeps a member below the 183-day threshold that a country uses to claim tax residence. Each country can hold one stay at a time for a member.

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
