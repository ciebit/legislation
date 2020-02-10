<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use Ciebit\Legislation\Documents\Bill;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Tests\Documents\Document as DocumentTest;
use DateTime;

class BillTest extends DocumentTest
{
    private const NUMBER = 567;

    public function testCreate(): void
    {
        $bill = new Bill(
            parent::TITLE,
            new DateTime(parent::DATE_TIME),
            new Status(parent::STATUS),
            self::NUMBER,
            parent::SLUG,
            parent::DESCRIPTION,
            parent::LABELS_IS,
            parent::ID,
        );

        $this->assertEquals(parent::DATE_TIME, $bill->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals(parent::DESCRIPTION, $bill->getDescription());
        $this->assertEquals(parent::ID, $bill->getId());
        $this->assertEquals(parent::LABELS_IS, $bill->getLabelsId());
        $this->assertEquals(self::NUMBER, $bill->getNumber());
        $this->assertEquals(parent::SLUG, $bill->getSlug());
        $this->assertEquals(parent::STATUS, $bill->getStatus()->getValue());
        $this->assertEquals(parent::TITLE, $bill->getTitle());
    }
}
