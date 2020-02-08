<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Attachments\Storages;

use Ciebit\Legislation\Attachments\Attachment;
use Ciebit\Legislation\Attachments\Collection;

interface Storage
{
    public const FIELD_FILE_ID = 'file_id';
    public const FIELD_DOCUMENT_ID = 'document_id';
    public const FIELD_ID = 'id';

    public function addFilterById(string $operator, string ...$id): self;

    public function addFilterByDocumentId(string $operator, string ...$id): self;

    public function addFilterByFileId(string $operator, string ...$id): self;

    public function addOrderBy(string $field, string $direction): self;

    public function find(): Collection;

    public function getTotalItemsOfLastFindWithoutLimit(): int;

    /** @return string ID */
    public function store(Attachment $attachment): string;
}