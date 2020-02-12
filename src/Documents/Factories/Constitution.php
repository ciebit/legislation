<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Constitution as ConstitutionEntity;
use Ciebit\Legislation\Documents\Document as DocumentEntity;
use Ciebit\Legislation\Documents\Factories\Document;

class Constitution extends Document
{
    public function create(): DocumentEntity
    {
        return new ConstitutionEntity(
            $this->title,
            $this->dateTime,
            $this->status,
            $this->slug,
            $this->description,
            $this->labelsId,
            $this->id
        );
    }

    public function setDocument(DocumentEntity $document): self
    {
        $this->setData((array) $document);
        return $this;
    }
}