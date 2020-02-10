<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Decree as DecreeEntity;
use Ciebit\Legislation\Documents\Factories\DocumentWithNumber;
use Ciebit\Legislation\Documents\Status;
use DateTime;

class Decree extends DocumentWithNumber
{
    public function __construct()
    {
        $this->dateTime = new DateTime;
        $this->description = '';
        $this->id = '';
        $this->number = 0;
        $this->slug = '';
        $this->status = Status::DRAFT();
        $this->title = '';
    }

    public function create(): DecreeEntity
    {
        return new DecreeEntity(
            $this->title,
            $this->dateTime,
            $this->status,
            $this->number,
            $this->slug,
            $this->description,
            $this->id
        );
    }
}