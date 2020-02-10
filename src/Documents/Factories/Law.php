<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Factories\DocumentWithNumber;
use Ciebit\Legislation\Documents\Law as LawEntity;

class Law extends DocumentWithNumber
{
    public function create(): LawEntity
    {
        return new LawEntity(
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