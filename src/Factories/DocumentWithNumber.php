<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Factories;

use Ciebit\Legislation\Factories\Document;
use Ciebit\Legislation\DocumentWithNumber as DocumentWithNumberEntity;

abstract class DocumentWithNumber extends Document
{
    protected int $number;

    public function setData(array $data): self
    {
        parent::setData($data);
        $data['number'] && $this->setNumber($data['number']);
        return $this;
    }

    public function setDocument(DocumentWithNumberEntity $document): self
    {
        $this->setData((array) $document);
        return $this;
    }

    public function setNumber(int $value): self
    {
        $this->number = $value;
        return $this;
    }
}
