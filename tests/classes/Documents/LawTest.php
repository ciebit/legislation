<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use Ciebit\Legislation\Documents\Law;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Tests\Documents\Document as DocumentTest;
use DateTime;

class LawTest extends DocumentTest
{
    private const NUMBER = 567;

    public function testCreate(): void
    {
        $law = new Law(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            self::NUMBER,
            parent::SLUG,
            parent::DESCRIPTION,
            parent::ID,
        );

        $this->assertEquals(parent::DATE_TIME, $law->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $law->getDescription());
        $this->assertEquals(parent::ID, $law->getId());
        $this->assertEquals(self::NUMBER, $law->getNumber());
        $this->assertEquals(parent::SLUG, $law->getSlug());
        $this->assertEquals(parent::STATUS, $law->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $law->getTitle());
    }
}
