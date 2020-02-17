<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Attachments\Storages\Databases;

use Ciebit\Legislation\Attachments\Collection;
use Ciebit\Legislation\Attachments\Attachment;
use Ciebit\Legislation\Attachments\Storages\Databases\Database;
use Ciebit\SqlHelper\Sql as SqlHelper;
use Exception;
use PDO;
use PDOStatement;

use function array_map;
use function error_log;
use function sprintf;

class Sql implements Database
{
    public const COLUMN_ID = 'id';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_DOCUMENT_ID = 'document_id';
    public const COLUMN_FILE_ID = 'file_id';
    public const COLUMN_TITLE = 'title';

    private PDO $pdo;
    private SqlHelper $sqlHelper;
    private string $table;
    private int $totalItemsOfLastFindWithoutLimitations;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->sqlHelper = new SqlHelper;
        $this->table = 'cb_legislation_attachment';
        $this->totalItemsOfLastFindWithoutLimitations = 0;
    }

    public function __clone()
    {
        $this->sqlHelper = clone $this->sqlHelper;
    }

    private function addFilter(string $fieldName, int $type, string $operator, ...$value): self
    {
        $field = "`{$this->table}`.`{$fieldName}`";
        $this->sqlHelper->addFilterBy($field, $type, $operator, ...$value);
        return $this;
    }

    public function addFilterById(string $operator, string ...$ids): self
    {
        $ids = array_map('intval', $ids);
        $this->addFilter(self::COLUMN_ID, PDO::PARAM_INT, $operator, ...$ids);
        return $this;
    }

    public function addFilterByDocumentId(string $operator, string ...$ids): self
    {
        $ids = array_map('intval', $ids);
        $this->addFilter(self::COLUMN_DOCUMENT_ID, PDO::PARAM_INT, $operator, ...$ids);
        return $this;
    }

    public function addFilterByFileId(string $operator, string ...$idsList): self
    {
        $ids = array_map('intval', $idsList);
        $this->addFilter(self::COLUMN_FILE_ID, PDO::PARAM_INT, $operator, ...$ids);
        return $this;
    }

    public function addOrderBy(string $field, string $direction): self
    {
        $this->sqlHelper->addOrderBy($field, $direction);
        return $this;
    }

    private function bindValuesStoreAndUpdate(PDOStatement $statement, Attachment $attachment): self
    {
        $statement->bindValue(
            ':description',
            $attachment->getDescription(),
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':document_id',
            $attachment->getDocumentId(),
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':file_id',
            $attachment->getFileId(),
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':title',
            $attachment->getTitle(),
            PDO::PARAM_STR
        );

        return $this;
    }

    private function build(array $data): Attachment
    {
        return new Attachment(
            $data[self::COLUMN_DOCUMENT_ID],
            $data[self::COLUMN_FILE_ID],
            $data[self::COLUMN_TITLE],
            $data[self::COLUMN_DESCRIPTION],
            $data[self::COLUMN_ID],
        );
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
                    `%s`
                FROM `{$this->table}`
                {$this->sqlHelper->generateSqlJoin()}
                WHERE {$this->sqlHelper->generateSqlFilters()}
                {$this->sqlHelper->generateSqlOrder()}
                {$this->sqlHelper->generateSqlLimit()}",
                self::COLUMN_DOCUMENT_ID,
                self::COLUMN_FILE_ID,
                self::COLUMN_TITLE,
                self::COLUMN_DESCRIPTION,
                self::COLUMN_ID,
            )
        );

        if ($statement === false) {
            throw new Exception('ciebit.legislation.attachments.storages.database.sql.sintaxe-error', 100);
        }

        /** @var \PDOStatement $statement */

        $this->sqlHelper->bind($statement);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.attachments.storages.database.sql.find_error', 101);
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

    public function store(Attachment $attachment): string
    {
        $fields = implode('`,`', [
            self::COLUMN_DESCRIPTION,
            self::COLUMN_DOCUMENT_ID,
            self::COLUMN_FILE_ID,
            self::COLUMN_TITLE,
        ]);

        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->table}
            (`{$fields}`)
            VALUES
            (
                :description,
                :document_id,
                :file_id,
                :title
            )"
        );

        $this->bindValuesStoreAndUpdate($statement, $attachment);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.attachemnts.storages.database.sql.store_error', 102);
        }

        return $this->pdo->lastInsertId();
    }


    private function updateTotalItemsWithoutFilters(): self
    {
        $this->totalItemsOfLastFindWithoutLimitations = (int) $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
        return $this;
    }
}