<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents\Storages\Databases;

use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Documents\Storages\Databases\Sql;
use Ciebit\Legislation\Documents\Storages\Storage;
use Ciebit\Legislation\Tests\Builds\BuildPdo;
use Ciebit\Legislation\Tests\Data\Document as DocumentData;
use Ciebit\Legislation\Tests\Settings\Database as SettingsDatabase;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function realpath;

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
        $pdo->exec("TRUNCATE TABLE `{$settings->getAttachmentTableName()}`");
        $pdo->exec("TRUNCATE TABLE `{$settings->getLabelTableName()}`");
        $pdo->exec("TRUNCATE TABLE `{$settings->getRevogationTableName()}`");
        $pathScriptsSql = realpath(__DIR__ . '/../../../../scripts/sql');
        $pdo->exec(file_get_contents($pathScriptsSql . '/dataDocumentDefault.sql'));
        $pdo->exec(file_get_contents($pathScriptsSql . '/dataAttachmentDefault.sql'));
        $pdo->exec(file_get_contents($pathScriptsSql . '/dataLabelDefault.sql'));
        $pdo->exec(file_get_contents($pathScriptsSql . '/dataRevogationDefault.sql'));
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

    public function testFindByLabelsId(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterByLabelsId('=', '22', '333')->find();
        $this->assertCount(2, $collection);
        $this->assertTrue($collection->hasWithId('1'));
        $this->assertTrue($collection->hasWithId('2'));
    }

    public function testFindBySearch(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addFilterBySearch('Decree 234/2020')->find();
        $this->assertCount(1, $collection);
        $this->assertEquals('3', $collection->getArrayObject()->offsetGet(0)->getId());
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

    public function testFindIntegrity(): void
    {
        $storage = $this->getStorage();
        $law = $storage
            ->addFilterById('=', '2')
            ->find()
            ->getArrayObject()
            ->offsetGet(0);

        $this->assertEquals('2', $law->getId());
        $this->assertEquals('Law 1.234/2020', $law->getTitle());
        $this->assertEquals('Description Law 1.234/2020', $law->getDescription());
        $this->assertEquals('2019-05-06 00:00:00', $law->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals('law-2020-1234', $law->getSlug());
        $this->assertEquals(1234, $law->getNumber());
        $this->assertEquals(2, $law->getStatus()->getValue());
        $this->assertEquals(['333','444'], $law->getLabelsId());
    }

    public function testFindWithLimit(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->setLimit(2)->find();
        $this->assertCount(2, $collection);
    }

    public function testFindWithOffsetAndLimit(): void
    {
        $storage = $this->getStorage();
        $collection = $storage
            ->setLimit(2)
            ->setOffset(2)
            ->find();
        $this->assertCount(2, $collection);
        $this->assertEquals('3', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testFindWithOrderBy(): void
    {
        $storage = $this->getStorage();
        $collection = $storage->addOrderBy(Storage::FIELD_DATE_TIME, 'DESC')->find();
        $this->assertEquals('5', $collection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testStore(): void
    {
        $storage = $this->getStorage();
        $data = DocumentData::getData();

        $id = $storage->store($data->getArrayObject()->offsetGet(0));
        $this->assertTrue($id !== '');
    }
}