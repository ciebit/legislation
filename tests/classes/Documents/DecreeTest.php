<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use Ciebit\Legislation\Documents\Decree;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Tests\Documents\Document as DocumentTest;
use DateTime;

class DecreeTest extends DocumentTest
{
    private const NUMBER = 765;

    public function testCreate(): void
    {
        $decree = new Decree(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            self::NUMBER,
            parent::SLUG,
            parent::DESCRIPTION,
            parent::ID,
        );

        $this->assertEquals(parent::DATE_TIME, $decree->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $decree->getDescription());
        $this->assertEquals(parent::ID, $decree->getId());
        $this->assertEquals(self::NUMBER, $decree->getNumber());
        $this->assertEquals(parent::SLUG, $decree->getSlug());
        $this->assertEquals(parent::STATUS, $decree->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $decree->getTitle());
    }
}
