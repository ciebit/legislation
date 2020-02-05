<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests;

use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Law;
use Ciebit\Legislation\Constitution;
use Ciebit\Legislation\Status;
use Ciebit\Legislation\Tests\Document as DocumentTest;
use DateTime;

class CollectionTest extends DocumentTest
{
    public function testCreate(): void
    {
        $collection = new Collection;
        $collection->add(
            new Law(
                'Title',
                new DateTime,
                Status::ACTIVE(),
                123,
                'title-123',
                'description',
                '1',
            ),
            new Constitution(
                'Title',
                new DateTime,
                Status::ACTIVE(),
                'title-123',
                'description',
                '2',
            ),
        );

        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasDocumentWithId('1'));
        $this->assertTrue($collection->hasDocumentWithId('2'));
        $this->assertInstanceOf(Law::class, $collection->getById('1'));
        $this->assertInstanceOf(Constitution::class, $collection->getById('2'));
    }
}
