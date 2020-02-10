<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use Ciebit\Legislation\Documents\Ordinance;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Tests\Documents\Document as DocumentTest;
use DateTime;

class OrdinanceTest extends DocumentTest
{
    private const NUMBER = 234;

    public function testCreate(): void
    {
        $ordinance = new Ordinance(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            self::NUMBER,
            parent::SLUG,
            parent::DESCRIPTION,
            parent::LABELS_IS,
            parent::ID,
        );

        $this->assertEquals(parent::DATE_TIME, $ordinance->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $ordinance->getDescription());
        $this->assertEquals(parent::ID, $ordinance->getId());
        $this->assertEquals(parent::LABELS_IS, $ordinance->getLabelsId());
        $this->assertEquals(self::NUMBER, $ordinance->getNumber());
        $this->assertEquals(parent::SLUG, $ordinance->getSlug());
        $this->assertEquals(parent::STATUS, $ordinance->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $ordinance->getTitle());
    }
}
