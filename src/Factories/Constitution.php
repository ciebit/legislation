<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Factories;

use Ciebit\Legislation\Constitution as ConstitutionEntity;
use Ciebit\Legislation\Document as DocumentEntity;
use Ciebit\Legislation\Factories\Document;
use Ciebit\Legislation\Status;
use DateTime;

class Constitution extends Document
{
    public function __construct()
    {
        $this->dateTime = new DateTime;
        $this->description = '';
        $this->id = '';
        $this->slug = '';
        $this->status = Status::DRAFT();
        $this->title = '';
    }

    public function create(): DocumentEntity
    {
        return new ConstitutionEntity(
            $this->title,
            $this->dateTime,
            $this->status,
            $this->slug,
            $this->description,
            $this->id
        );
    }

    public function setDocument(DocumentEntity $document): self
    {
        $this->setData((array) $document);
        return $this;
    }
}