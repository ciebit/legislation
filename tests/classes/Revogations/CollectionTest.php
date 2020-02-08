<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Revogations;

use Ciebit\Legislation\Revogations\Collection;
use Ciebit\Legislation\Revogations\Revogation;
use Ciebit\Legislation\Revogations\Mode;
use Ciebit\Legislation\Tests\Document as DocumentTest;

class CollectionTest extends DocumentTest
{
    public function testCreate(): void
    {
        $revolgation1 = new Revogation(
            '1', '2', Mode::INTEGRAL(), 'Description 1', '1'
        );
        $revolgation2 = new Revogation(
            '3', '4', Mode::PARTIAL(), 'Description 2', '2'
        );
        $collection = new Collection;
        $collection->add($revolgation1, $revolgation2);

        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasRevogationWithId('1'));
        $this->assertTrue($collection->hasRevogationWithId('2'));
        $this->assertEquals($revolgation1, $collection->getById('1'));
        $this->assertEquals($revolgation2, $collection->getById('2'));
    }
}
