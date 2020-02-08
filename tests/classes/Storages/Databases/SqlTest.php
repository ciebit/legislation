<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Storages\Databases;

use Ciebit\Legislation\Status;
use Ciebit\Legislation\Tests\Builds\BuildPdo;
use Ciebit\Legislation\Storages\Databases\Sql;
use Ciebit\Legislation\Storages\Storage;
use Ciebit\Legislation\Tests\Settings\Database as SettingsDatabase;
use Ciebit\Legislation\Tests\Data\Document as DocumentData;
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
        $pdo->exec("TRUNCATE TABLE `{$settings->getDocumentTableName()}`");
        $pdo->exec(file_get_contents(__DIR__ . '/../../../scripts/sql/dataDocumentDefault.sql'));
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

    public function testFindById(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterById('=', '1')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('1', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindBySlug(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterBySlug('=', 'decree-2020-234')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('3', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindByStatus(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByStatus('=', Status::ANALYZE())->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('2', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testOrderBy(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addOrderBy(Storage::FIELD_DATE_TIME, 'DESC')->find();
        $this->assertEquals('4', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testStore(): void
    {
        $storage = $this->getStorage();
        $data = DocumentData::getData();

        $id = $storage->store($data->getArrayObject()->offsetGet(0));
        $this->assertTrue($id !== '');
    }
}