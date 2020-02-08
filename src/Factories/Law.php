<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Factories;

use Ciebit\Legislation\Factories\DocumentWithNumber;
use Ciebit\Legislation\Law as LawEntity;
use Ciebit\Legislation\Status;
use DateTime;

class Law extends DocumentWithNumber
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

    public function create(): LawEntity
    {
        return new LawEntity(
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