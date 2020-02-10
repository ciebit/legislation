<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Factories\DocumentWithNumber;
use Ciebit\Legislation\Documents\Bill as BillEntity;

class Bill extends DocumentWithNumber
{
    public function create(): BillEntity
    {
        return new BillEntity(
            $this->title,
            $this->dateTime,
            $this->status,
            $this->number,
            $this->slug,
            $this->description,
            $this->labelsId,
            $this->id
        );
    }
}