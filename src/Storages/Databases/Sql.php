<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Storages\Databases;

use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;
use Ciebit\Legislation\DocumentWithNumber;
use Ciebit\Legislation\Storages\Storage;
use Ciebit\Legislation\Storages\Databases\Database;
use Ciebit\SqlHelper\Sql as SqlHelper;
use DateTime;
use Exception;
use PDO;
use PDOStatement;

use function array_map;
use function count;
use function intval;

class Sql implements Database
{
    private const COLUMN_DATE_TIME = 'date_time';
    private const COLUMN_TITLE = 'title';
    private const COLUMN_DESCRIPTION = 'description';
    private const COLUMN_ID = 'id';
    private const COLUMN_NUMBER = 'number';
    private const COLUMN_SLUG = 'slug';
    private const COLUMN_STATUS = 'status';

    private PDO $pdo;
    private string $table;

    public function __construct(PDO $pdo)
    {
        $this->table = 'cb_legislation';
        $this->pdo = $pdo;
    }

    private function bindValuesStoreAndUpdate(PDOStatement $statement, Document $document): self
    {
        $statement->bindValue(':date_time', $document->getDateTime()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->bindValue(':status', $document->getStatus()->getValue(), PDO::PARAM_INT);
        $statement->bindValue(':title', $document->getTitle(), PDO::PARAM_STR);
        $statement->bindValue(':slug', $document->getSlug(), PDO::PARAM_STR);
        $statement->bindValue(':description', $document->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(
            ':number', 
            ($document instanceof DocumentWithNumber) ? $document->getNumber() : 0, 
            PDO::PARAM_INT
        );

        return $this;
    }

    public function setTable(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    public function store(Document $document): string
    {
        $fields = implode('`,`', [
            self::COLUMN_DATE_TIME,
            self::COLUMN_DESCRIPTION,
            self::COLUMN_NUMBER,
            self::COLUMN_SLUG,
            self::COLUMN_STATUS,
            self::COLUMN_TITLE,
        ]);

        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->table}
            (`{$fields}`)
            VALUES
            (
                :date_time, :description, :number, 
                :slug, :status, :title 
            )"
        );

        $this->bindValuesStoreAndUpdate($statement, $document);

        $this->pdo->beginTransaction();

        if ($statement->execute() === false) {
            throw new Exception('ciebit.legislation.storages.database.sql.store_error', 3);
        }

        return $this->pdo->lastInsertId();
    }
}