<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Document as Document;
use Ciebit\Legislation\Documents\Factories\Document as DocumentFactory;

abstract class DocumentWithNumber extends DocumentFactory
{
    protected int $number;

    public function __construct()
    {
        parent::__construct();
        $this->number = 0;
    }

    public function setData(array $data): self
    {
        parent::setData($data);
        $data['number'] && $this->setNumber($data['number']);
        return $this;
    }

    public function setDocument(Document $document): self
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
