<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests;

use Ciebit\Legislation\Constitution;
use Ciebit\Legislation\Status;
use Ciebit\Legislation\Tests\Document as DocumentTest;
use DateTime;
use TypeError;

class ConstitutionTest extends DocumentTest
{
    public function testCreate(): void
    {
        $constitution = new Constitution(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            parent::SLUG,
            parent::DESCRIPTION,
            parent::ID,
            parent::FILES_ID
        );

        $this->assertEquals(parent::DATE_TIME, $constitution->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $constitution->getDescription());
        $this->assertEquals(parent::FILES_ID, $constitution->getFilesId());
        $this->assertEquals(parent::ID, $constitution->getId());
        $this->assertEquals(parent::SLUG, $constitution->getSlug());
        $this->assertEquals(parent::STATUS, $constitution->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $constitution->getTitle());
    }

    public function testExceptionFileId(): void
    {
        $this->expectException(TypeError::class);

        new Constitution(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            parent::SLUG,
            parent::DESCRIPTION,
            parent::ID,
            ['1', '2', 0]
        );
    }
}
