<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Factories\DocumentWithNumber;
use Ciebit\Legislation\Documents\Ordinance as OrdinanceEntity;

class Ordinance extends DocumentWithNumber
{
    public function create(): OrdinanceEntity
    {
        return new OrdinanceEntity(
            $this->title,
            $this->dateTime,
            $this->status,
            $this->number,
            $this->slug,
            $this->description,
            $this->labelsId,
            $this->id,
        );
    }
}