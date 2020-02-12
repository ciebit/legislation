<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use Ciebit\Legislation\Documents\Constitution;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Tests\Documents\Document as DocumentTest;
use DateTime;

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
            parent::LABELS_IS,
            parent::ID,
        );

        $this->assertEquals(parent::DATE_TIME, $constitution->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $constitution->getDescription());
        $this->assertEquals(parent::ID, $constitution->getId());
        $this->assertEquals(parent::LABELS_IS, $constitution->getLabelsId());
        $this->assertEquals(parent::SLUG, $constitution->getSlug());
        $this->assertEquals(parent::STATUS, $constitution->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $constitution->getTitle());
    }
}
