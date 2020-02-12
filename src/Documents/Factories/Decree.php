<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Decree as DecreeEntity;
use Ciebit\Legislation\Documents\Factories\DocumentWithNumber;

class Decree extends DocumentWithNumber
{
    public function create(): DecreeEntity
    {
        return new DecreeEntity(
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