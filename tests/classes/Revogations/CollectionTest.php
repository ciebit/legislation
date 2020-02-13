<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Revogations;

use Ciebit\Legislation\Revogations\Collection;
use Ciebit\Legislation\Revogations\Revogation;
use Ciebit\Legislation\Revogations\Mode;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testCreate(): void
    {
        $revolgation1 = new Revogation(
            '11', '12', Mode::INTEGRAL(), 'Description 1', '1'
        );
        $revolgation2 = new Revogation(
            '13', '14', Mode::PARTIAL(), 'Description 2', '2'
        );
        $collection = new Collection;
        $collection->add($revolgation1, $revolgation2);

        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasWithId('1'));
        $this->assertTrue($collection->hasWithId('2'));
        $this->assertEquals($revolgation1, $collection->getById('1'));
        $this->assertEquals($revolgation2, $collection->getById('2'));

        $this->assertTrue($collection->hasRevokedWithDocumentId('11'));
        $this->assertTrue($collection->hasRevokedWithDocumentId('13'));
        $this->assertFalse($collection->hasRevokedWithDocumentId('14'));
        $this->assertFalse($collection->hasRevokedWithDocumentId('12'));
        $this->assertEquals($revolgation1, $collection->getByRevokedDocumentId('11'));
        $this->assertEquals($revolgation2, $collection->getByRevokedDocumentId('13'));

        $this->assertTrue($collection->hasSubstituteWithDocumentId('12'));
        $this->assertTrue($collection->hasSubstituteWithDocumentId('14'));
        $this->assertFalse($collection->hasSubstituteWithDocumentId('11'));
        $this->assertFalse($collection->hasSubstituteWithDocumentId('13'));
        $this->assertEquals($revolgation1, $collection->getBySubstituteDocumentId('12'));
        $this->assertEquals($revolgation2, $collection->getBySubstituteDocumentId('14'));
    }
}
