<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Revogations\Storages\Databases;

use Ciebit\Legislation\Tests\Builds\BuildPdo;
use Ciebit\Legislation\Revogations\Mode;
use Ciebit\Legislation\Revogations\Revogation;
use Ciebit\Legislation\Revogations\Storages\Databases\Sql;
use Ciebit\Legislation\Revogations\Storages\Storage;
use Ciebit\Legislation\Tests\Settings\Database as SettingsDatabase;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

class SqlTest extends TestCase
{
    private function getStorage(): Storage
    {
        $pdo = BuildPdo::build();
        return new Sql($pdo);
    }

    private function restoreDefaultDatabaseState(): void
    {
        $settings = new SettingsDatabase;
        $pdo = BuildPdo::build();
        $pdo->exec("TRUNCATE TABLE `{$settings->getRevogationTableName()}`");
        $pdo->exec(file_get_contents(__DIR__ . '/../../../../scripts/sql/dataRevogationDefault.sql'));
    }

    protected function setUp(): void
    {
        $this->restoreDefaultDatabaseState();
    }

    public function testFind(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->find();
        $this->assertCount(4, $collection);
    }

    public function testFindByDocumentId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByDocumentId('=', '3')->find();
        $this->assertCount(3, $collection);
    }

    public function testFindById(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterById('=', '1')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('1', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindByMode(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByMode('=', Mode::INTEGRAL())->find();
        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasWithId('1'));
        $this->assertTrue($collection->hasWithId('4'));
    }

    public function testFindByRevokedDocumentId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByRevokedDocumentId('=', '3')->find();
        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasWithId('2'));
        $this->assertTrue($collection->hasWithId('3'));
    }

    public function testFindBySubstituteDocumentId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterBySubstituteDocumentId('=', '4')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('2', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testOrderBy(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addOrderBy(Storage::FIELD_SUBSTITUTE_DOCUMENT_ID, 'DESC')->find();
        $revogation = $collection->getArrayObject()->offsetGet(0);
        $this->assertEquals('5', $revogation->getSubstituteDocumentId());
    }

    public function testStore(): void
    {
        $storage = $this->getStorage();
        $revogation = new Revogation(
            '1', '2', Mode::INTEGRAL(), 'Description 01'
        );

        $id = $storage->store($revogation);
        $this->assertTrue($id !== '');
    }
}