<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Storages\Databases;

use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;
use Ciebit\Legislation\DocumentWithNumber;
use Ciebit\Legislation\Factories\Factory;
use Ciebit\Legislation\Status;
use Ciebit\Legislation\Storages\Databases\Database;
use Ciebit\SqlHelper\Sql as SqlHelper;
use DateTime;
use Exception;
use PDO;
use PDOStatement;

use function get_class;
use function error_log;
use function sprintf;

class Sql implements Database
{
    private const COLUMN_DATE_TIME = 'date_time';
    private const COLUMN_DESCRIPTION = 'description';
    private const COLUMN_ID = 'id';
    private const COLUMN_NUMBER = 'number';
    private const COLUMN_SLUG = 'slug';
    private const COLUMN_STATUS = 'status';
    private const COLUMN_TITLE = 'title';
    private const COLUMN_TYPE = 'type';

    private PDO $pdo;
    private SqlHelper $sqlHelper;
    private string $table;
    private int $totalItemsOfLastFindWithoutLimitations;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->sqlHelper = new SqlHelper;
        $this->table = 'cb_legislation_document';
        $this->totalItemsOfLastFindWithoutLimitations = 0;
    }

    private function bindValuesStoreAndUpdate(PDOStatement $statement, Document $document): self
    {
        $statement->bindValue(
            ':date_time', 
            $document->getDateTime()->format('Y-m-d H:i:s'), 
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':description',
            $document->getDescription(),
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':number',
            ($document instanceof DocumentWithNumber) ? $document->getNumber() : 0,
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':slug',
            $document->getSlug(),
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':status', 
            $document->getStatus()->getValue(), 
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':title',
            $document->getTitle(),
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':type',
            get_class($document),
            PDO::PARAM_STR
        );

        return $this;
    }

    private function build(array $data): Document
    {
        $dataStandart = $data;
        $dataStandart['dateTime'] = new DateTime($data[self::COLUMN_DATE_TIME]);
        $dataStandart['status'] = new Status((int) $data[self::COLUMN_STATUS]);
        $dataStandart['number'] = (int) $data[self::COLUMN_NUMBER];

        return (new Factory)
            ->setType($dataStandart[self::COLUMN_TYPE])
            ->setData($dataStandart)
            ->create();
    }

    public function find(): Collection
    {
        $statement = $this->pdo->prepare(
            sprintf(
                "SELECT SQL_CALC_FOUND_ROWS
                    `%s`,
                    `%s`,
                    `%s`,
                    `%s`,
                    `%s`,
                    `%s`,
                    `%s`,
                    `%s`
                FROM `{$this->table}`
                {$this->sqlHelper->generateSqlJoin()}
                WHERE {$this->sqlHelper->generateSqlFilters()}
                {$this->sqlHelper->generateSqlOrder()}
                {$this->sqlHelper->generateSqlLimit()}",
                self::COLUMN_DATE_TIME,
                self::COLUMN_DESCRIPTION,
                self::COLUMN_ID,
                self::COLUMN_SLUG,
                self::COLUMN_STATUS,
                self::COLUMN_NUMBER,
                self::COLUMN_TITLE,
                self::COLUMN_TYPE,
            )
        );

        if ($statement === false) {
            throw new Exception('ciebit.legislation.storages.database.sql.sintaxe-error', 2);
        }

        /** @var \PDOStatement $statement */

        $this->sqlHelper->bind($statement);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.storages.database.sql.find_error', 3);
        }

        $this->updateTotalItemsWithoutFilters();

        $collection = new Collection;

        while ($data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $collection->add(
                $this->build($data)
            );
        }

        return $collection;
    }

    public function getTotalItemsOfLastFindWithoutLimit(): int
    {
        return $this->totalItemsOfLastFindWithoutLimitations;
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
            self::COLUMN_TYPE,
        ]);

        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->table}
            (`{$fields}`)
            VALUES
            (
                :date_time, :description, :number, 
                :slug, :status, :title, :type 
            )"
        );

        $this->bindValuesStoreAndUpdate($statement, $document);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.storages.database.sql.store_error', 1);
        }

        return $this->pdo->lastInsertId();
    }


    private function updateTotalItemsWithoutFilters(): self
    {
        $this->totalItemsOfLastFindWithoutLimitations = (int) $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
        return $this;
    }
}