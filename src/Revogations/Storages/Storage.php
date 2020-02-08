<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Revogations\Storages;

use Ciebit\Legislation\Revogations\Collection;
use Ciebit\Legislation\Revogations\Mode;
use Ciebit\Legislation\Revogations\Revogation;

interface Storage
{
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_ID = 'id';
    public const FIELD_MODE = 'mode';
    public const FIELD_REVOKED_DOCUMENT_ID = 'revoked_document_id';
    public const FIELD_SUBSTITUTE_DOCUMENT_ID = 'substitute_document_id';

    public function addFilterById(string $operator, string ...$id): self;

    public function addFilterByMode(string $operator, Mode ...$mode): self;

    public function addFilterByRevokedDocumentId(string $operator, string ...$id): self;

    public function addFilterBySubstituteDocumentId(string $operator, string ...$id): self;

    public function addOrderBy(string $field, string $direction): self;

    public function find(): Collection;

    public function getTotalItemsOfLastFindWithoutLimit(): int;

    /** @return string ID */
    public function store(Revogation $revogation): string;
}