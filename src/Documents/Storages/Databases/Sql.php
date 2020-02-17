<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Storages\Databases;

use Ciebit\Legislation\Documents\Collection;
use Ciebit\Legislation\Documents\Document;
use Ciebit\Legislation\Documents\DocumentWithNumber;
use Ciebit\Legislation\Documents\Factories\Factory;
use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Documents\Storages\Databases\Database;
use Ciebit\SqlHelper\Sql as SqlHelper;
use DateTime;
use Exception;
use PDO;
use PDOStatement;

use function array_map;
use function error_log;
use function sprintf;

class Sql implements Database
{
    private const COLUMN_ASSOCIATION_LABELS_DOCUMENT_ID = 'document_id';
    private const COLUMN_ASSOCIATION_LABELS_ID = 'id';
    private const COLUMN_ASSOCIATION_LABELS_LABEL_ID = 'label_id';
    private const COLUMN_DATE_TIME = 'date_time';
    private const COLUMN_DESCRIPTION = 'description';
    private const COLUMN_ID = 'id';
    private const COLUMN_LABELS_ID = 'labels_id';
    private const COLUMN_NUMBER = 'number';
    private const COLUMN_SLUG = 'slug';
    private const COLUMN_STATUS = 'status';
    private const COLUMN_TITLE = 'title';
    private const COLUMN_TYPE = 'type';

    private PDO $pdo;
    private SqlHelper $sqlHelper;
    private string $table;
    private string $tableLabelAssociation;
    private int $totalItemsOfLastFindWithoutLimitations;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->sqlHelper = new SqlHelper;
        $this->table = 'cb_legislation_document';
        $this->tableLabelAssociation = 'cb_legislation_label';
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

    public function addFilterByLabelsId(string $operator, string ...$id): self
    {
        $fieldDocumentId = '`' . self::COLUMN_ASSOCIATION_LABELS_DOCUMENT_ID . '`';
        $fieldId = '`' . self::COLUMN_ID . '`';
        $fieldLabelId = '`' . self::COLUMN_ASSOCIATION_LABELS_LABEL_ID . '`';
        $tableAssociation = "`{$this->tableLabelAssociation}`";

        $ids = array_map('intval', $id);
        $this->sqlHelper->addFilterBy("{$tableAssociation}.{$fieldLabelId}", PDO::PARAM_INT, $operator, ...$ids);
        $this->sqlHelper->addSqlJoin(
            "INNER JOIN {$this->tableLabelAssociation}
            ON {$this->tableLabelAssociation}.{$fieldDocumentId} = `{$this->table}`.{$fieldId}"
        );

        return $this;
    }

    public function addFilterBySearch(string ...$search): self
    {
        $fieldTitle = self::COLUMN_TITLE;
        $fieldDescription = self::COLUMN_DESCRIPTION;
        $querys = [];

        if (count($search) == 0) {
            return $this;
        }

        $i = 0;
        foreach($search as $searchItem) {
            $keyTitle = ':search_title_field_'. $i;
            $keyDescription = ':search_description_field_'. $i;
            $value = '%' . $searchItem . '%';

            $querys[] = "(`{$this->table}`.`{$fieldTitle}` LIKE {$keyTitle} 
                OR `{$this->table}`.`{$fieldDescription}` LIKE {$keyDescription})";

            $this->sqlHelper->addBind($keyTitle, PDO::PARAM_STR, $value);
            $this->sqlHelper->addBind($keyDescription, PDO::PARAM_STR, $value);

            $i++;
        }

        $this->sqlHelper->addSqlFilter('(' . implode(' OR ', $querys). ')');

        return $this;
    }

    public function addFilterBySlug(string $operator, string ...$slug): self
    {
        $this->addFilter(self::COLUMN_SLUG, PDO::PARAM_STR, $operator, ...$slug);
        return $this;
    }

    public function addFilterByStatus(string $operator, Status ...$status): self
    {
        $statusNumber = array_map(
            fn($status) => $status->getValue(),
            $status
        );
        $this->addFilter(self::COLUMN_STATUS, PDO::PARAM_INT, $operator, ...$statusNumber);
        return $this;
    }

    public function addOrderBy(string $field, string $direction): self
    {
        $this->sqlHelper->addOrderBy($field, $direction);
        return $this;
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
            $document::getType(),
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
        $dataStandart['labelsId'] = $data[self::COLUMN_LABELS_ID] 
            ? explode(',', $data[self::COLUMN_LABELS_ID]) 
            : [];

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
                    `{$this->table}`.`%s`, 
                    `{$this->table}`.`%s`, 
                    `{$this->table}`.`%s`,
                    `{$this->table}`.`%s`, 
                    `{$this->table}`.`%s`, 
                    `{$this->table}`.`%s`,
                    `{$this->table}`.`%s`,
                    `{$this->table}`.`%s`,
                    (
                        SELECT GROUP_CONCAT(`{$this->tableLabelAssociation}`.`%s`)
                        FROM  `{$this->tableLabelAssociation}`
                        WHERE `{$this->tableLabelAssociation}`.`%s` = `{$this->table}`.`%s`
                    )  as `%s`
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
                self::COLUMN_ASSOCIATION_LABELS_LABEL_ID,
                self::COLUMN_ASSOCIATION_LABELS_DOCUMENT_ID,
                self::COLUMN_ID,
                self::COLUMN_LABELS_ID,
            )
        );

        if ($statement === false) {
            throw new Exception('ciebit.legislation.documents.storages.database.sql.sintaxe-error', 201);
        }

        /** @var \PDOStatement $statement */

        $this->sqlHelper->bind($statement);

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.documents.storages.database.sql.find_error', 202);
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

    public function setLimit(int $limit): self
    {
        $this->sqlHelper->setLimit($limit);
        return $this;
    }
    
    public function setOffset(int $offset): self
    {
        $this->sqlHelper->setOffset($offset);
        return $this;
    }

    public function setTable(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    public function setTableLabelAssociation(string $name): self
    {
        $this->tableLabelAssociation = $name;
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

        $this->pdo->beginTransaction();

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.documents.storages.database.sql.store_error', 203);
        }

        $id = $this->pdo->lastInsertId();
        if (count($document->getLabelsId()) > 0) {
            $this->storeLabelsId($id, ...$document->getLabelsId());
        }

        $this->pdo->commit();

        return $id;
    }

    private function storeLabelsId(string $documentId, string ...$labelsId): bool
    {
        $fields = implode('`,`', [
            self::COLUMN_ASSOCIATION_LABELS_DOCUMENT_ID,
            self::COLUMN_ASSOCIATION_LABELS_LABEL_ID,
        ]);

        $values = [];

        foreach ($labelsId as $key => $labelId) {
            $values[] = "(:documentId, :labelId{$key})";
        }

        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->tableLabelAssociation} (`{$fields}`) 
            VALUES " . implode(',', $values)
        );

        $statement->bindValue(':documentId', $documentId, PDO::PARAM_INT);

        foreach ($labelsId as $key => $labelId) {
            $statement->bindValue(":labelId{$key}", $labelId, PDO::PARAM_INT);
        }

        if ($statement->execute() === false) {
            error_log($statement->errorInfo()[2]);
            throw new Exception('ciebit.legislation.documents.storages.database.store-labels-error', 204);
        }

        return true;
    }

    private function updateTotalItemsWithoutFilters(): self
    {
        $this->totalItemsOfLastFindWithoutLimitations = (int) $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
        return $this;
    }
}