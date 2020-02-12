<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Attachments;

use Ciebit\Legislation\Attachments\Collection;
use Ciebit\Legislation\Attachments\Attachment;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testCreate(): void
    {
        $attachment1 = new Attachment(
            '1', '2', 'Title 1', 'Description 1', '1'
        );
        $attachment2 = new Attachment(
            '3', '4', 'Title 2', 'Description 3', '2'
        );
        $collection = new Collection;
        $collection->add($attachment1, $attachment2);

        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasWithId('1'));
        $this->assertTrue($collection->hasWithId('2'));
        $this->assertEquals($attachment1, $collection->getById('1'));
        $this->assertEquals($attachment2, $collection->getById('2'));
    }
}
