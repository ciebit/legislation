<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Attachments\Storages\Databases;

use Ciebit\Legislation\Attachments\Attachment;
use Ciebit\Legislation\Attachments\Storages\Databases\Sql;
use Ciebit\Legislation\Attachments\Storages\Storage;
use Ciebit\Legislation\Tests\Builds\BuildPdo;
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
        $pdo->exec("TRUNCATE TABLE `{$settings->getAttachmentTableName()}`");
        $pdo->exec(file_get_contents(__DIR__ . '/../../../../scripts/sql/dataAttachmentDefault.sql'));
    }

    protected function setUp(): void
    {
        $this->restoreDefaultDatabaseState();
    }

    public function testFind(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->find();
        $this->assertCount(5, $collection);
    }

    public function testFindById(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterById('=', '1')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('1', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindByDocumentId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByDocumentId('=', '3')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('3', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindByFileId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByFileId('=', '6')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('2', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testOrderBy(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addOrderBy(Storage::FIELD_FILE_ID, 'DESC')->find();
        $this->assertEquals('5', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testStore(): void
    {
        $storage = $this->getStorage();
        $attachment = new Attachment('1', '2');

        $id = $storage->store($attachment);
        $this->assertTrue($id !== '');
    }
}