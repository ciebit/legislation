<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Storages\Databases;

use Ciebit\Legislation\Tests\Builds\BuildPdo;
use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;
use Ciebit\Legislation\DocumentWithNumber;
use Ciebit\Legislation\Storages\Databases\Sql;
use Ciebit\Legislation\Storages\Storage;
use Ciebit\Legislation\Tests\Settings\Database as SettingsDatabase;
use Ciebit\Legislation\Tests\Data\Document as DocumentData;
use DateTime;
use Exception;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

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
    }

    protected function setUp(): void
    {
        $this->restoreDefaultDatabaseState();
    }

    public function testFind(): void
    {
        $storage = $this->getStorage();
        $collection = DocumentData::getData();

        foreach($collection as $document) {
            $storage->store($document);
        }

        $collectionTwo = $storage->find();
        $this->assertCount($collection->count(), $collectionTwo);        
    }

    public function testStore(): void
    {
        $storage = $this->getStorage();
        $data = DocumentData::getData();

        $id = $storage->store($data->getArrayObject()->offsetGet(0));
        $this->assertTrue($id !== '');
    }
}