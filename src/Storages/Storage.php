<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Storages;

use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;

interface Storage
{
    public const FIELD_DATE_TIME = 'date_time';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_ID = 'id';
    public const FIELD_NUMBER = 'number';
    public const FIELD_SLUG = 'slug';
    public const FIELD_STATUS = 'status';
    public const FIELD_TITLE = 'title';
    public const FIELD_TYPE = 'type';

    public function addFilterById(string $operation, string ...$id): self;

    public function addFilterBySlug(string $operator, string ...$slug): self;

    public function addOrderBy(string $field, string $direction): self;

    public function find(): Collection;

    public function getTotalItemsOfLastFindWithoutLimit(): int;

    /** @return string ID */
    public function store(Document $document): string;
}