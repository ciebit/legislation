<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Revogations\Storages\Databases;

use Ciebit\Legislation\Revogations\Collection;
use Ciebit\Legislation\Revogations\Mode;
use Ciebit\Legislation\Revogations\Revogation;
use Ciebit\Legislation\Revogations\Storages\Databases\Database;
use Ciebit\SqlHelper\Sql as SqlHelper;
use Exception;
use PDO;
use PDOStatement;

use function array_map;
use function error_log;
use function sprintf;

class Sql implements Database
{
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_ID = 'id';
    public const COLUMN_MODE = 'mode';
    public const COLUMN_REVOKED_DOCUMENT_ID = 'revoked_document_id';
    public const COLUMN_SUBSTITUTE_DOCUMENT_ID = 'substitute_document_id';

    private PDO $pdo;
    private SqlHelper $sqlHelper;
    private string $table;
    private int $totalItemsOfLastFindWithoutLimitations;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->sqlHelper = new SqlHelper;
        $this->table = 'cb_legislation_revogation';
        $this->totalItemsOfLastFindWithoutLimitations = 0;
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

    public function addFilterByMode(string $operator, Mode ...$modeList): self
    {
        $modeNumber = array_map(
            fn ($mode) => $mode->getValue(),
            $modeList
        );
        $this->addFilter(self::COLUMN_MODE, PDO::PARAM_INT, $operator, ...$modeNumber);
        return $this;
    }

    public function addFilterByRevokedDocumentId(string $operator, string ...$ids): self
    {
        $ids = array_map('intval', $ids);
        $this->addFilter(self::COLUMN_REVOKED_DOCUMENT_ID, PDO::PARAM_INT, $operator, ...$ids);
        return $this;
    }

    public function addFilterBySubstituteDocumentId(string $operator, string ...$idsList): self
    {
        $ids = array_map('intval', $idsList);
        $this->addFilter(self::COLUMN_SUBSTITUTE_DOCUMENT_ID, PDO::PARAM_INT, $operator, ...$ids);
        return $this;
    }

    public function addOrderBy(string $field, string $direction): self
    {
        $this->sqlHelper->addOrderBy($field, $direction);
        return $this;
    }

    private function bindValuesStoreAndUpdate(PDOStatement $statement, Revogation $revogation): self
    {
        $statement->bindValue(
            ':description',
            $revogation->getDescription(),
            PDO::PARAM_STR
        );
        $statement->bindValue(
            ':mode', 
            $revogation->getMode()->getValue(), 
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':revoked_document_id',
            $revogation->getRevokedDocumentId(),
            PDO::PARAM_INT
        );
        $statement->bindValue(
            ':substitute_document_id',
            $revogation->getSubstituteDocumentId(),
            PDO::PARAM_INT
        );

        return $this;
    }

    private function build(array $data): Revogation
    {
        return new Revogation(
            $data[self::COLUMN_REVOKED_DOCUMENT_ID],
            $data[self::COLUMN_SUBSTITUTE_DOCUMENT_ID],
            new Mode((int) $data[self::COLUMN_MODE]),
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
                self::COLUMN_DESCRIPTION,
                self::COLUMN_ID,
                self::COLUMN_MODE,
                self::COLUMN_REVOKED_DOCUMENT_ID,
                self::COLUMN_SUBSTITUTE_DOCUMENT_ID,
            )
        );

        if ($statement === false) {
            throw new Exception('ciebit.legislation.revogations.storages.database.sql.sintaxe-error', 2);
        }

        /** @var \PDOStatement $statement */

        $this->sqlHelper->bind($statement);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.revogations.storages.database.sql.find_error', 3);
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

    public function store(Revogation $revogation): string
    {
        $fields = implode('`,`', [
            self::COLUMN_DESCRIPTION,
            self::COLUMN_MODE,
            self::COLUMN_REVOKED_DOCUMENT_ID,
            self::COLUMN_SUBSTITUTE_DOCUMENT_ID,
        ]);

        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->table}
            (`{$fields}`)
            VALUES
            (
                :description, :mode, 
                :revoked_document_id,
                :substitute_document_id
            )"
        );

        $this->bindValuesStoreAndUpdate($statement, $revogation);

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