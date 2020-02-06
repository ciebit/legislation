<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Storages;

use Ciebit\Legislation\Collection;
use Ciebit\Legislation\Document;

interface Storage
{
    public function find(): Collection;

    public function getTotalItemsOfLastFindWithoutLimit(): int;

    /** @return string ID */
    public function store(Document $document): string;
}