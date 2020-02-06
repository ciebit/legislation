<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Storages\Databases;

use Ciebit\Legislation\Tests\Builds\BuildPdo;
use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;
use Ciebit\Legislation\DocumentWithNumber;
use Ciebit\Legislation\Storages\Databases\Sql;
use Ciebit\Legislation\Tests\Settings\Database as SettingsDatabase;
use Ciebit\Legislation\Tests\Data\Document as DocumentData;
use DateTime;
use Exception;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
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

    public function testStore(): void
    {
        $pdo = BuildPdo::build();
        $storage = new Sql($pdo);
        $data = DocumentData::getData();

        $id = $storage->store($data->getArrayObject()->offsetGet(0));
        $this->assertTrue($id !== '');
    }
}