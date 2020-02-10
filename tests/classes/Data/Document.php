<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Data;

use Ciebit\Legislation\Documents\Collection;
use Ciebit\Legislation\Documents\Constitution;
use Ciebit\Legislation\Documents\Decree;
use Ciebit\Legislation\Documents\Law;
use Ciebit\Legislation\Documents\Ordinance;
use Ciebit\Legislation\Documents\Status;
use DateTime;

class Document
{
    private static ?Collection $collection = null;

    public static function getData(): Collection
    {
        if (self::$collection instanceof Collection) {
            return self::$collection;
        }

        return self::$collection = (new Collection)->add(
            new Constitution(
                'Constitution 2019',
                new DateTime('2019-01-02'),
                Status::ACTIVE(),
                'constitution-2019',
                'Description Constitution 2019'
            ),
            new Law(
                'Law 1.234/2020',
                new DateTime('2020-02-06'),
                Status::ACTIVE(),
                1234,
                'law-2020-1234',
                'Description Law 1.234/2020'
            ),
            new Decree(
                'Decree 234/2020',
                new DateTime('2020-01-02'),
                Status::DRAFT(),
                234,
                'decree-2020-234',
                'Description Decree 234/2020'
            ),
            new Ordinance(
                'Ordinance 55/2020',
                new DateTime('2019-12-20'),
                Status::DRAFT(),
                55,
                'ordinance-2020-55',
                'Description Ordinance 234/2020'
            ),
        );
    }
}
