<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents;

use ArrayIterator;
use ArrayObject;
use Ciebit\Legislation\Documents\Document;
use Countable;
use Exception;
use IteratorAggregate;
use JsonSerializable;

class Collection implements Countable, IteratorAggregate, JsonSerializable
{
    private ArrayObject $items;

    public function __construct()
    {
        $this->items = new ArrayObject;
    }

    public function add(Document ...$documentList): self
    {
        foreach($documentList as $document) {
            $this->items->append($document);
        }

        return $this;
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->items;
    }

    /**
     * @throws Exception
     */
    public function getById(string $id): Document
    {
        foreach ($this->getIterator() as $document) {
            if ($document->getId() == $id) {
                return $document;
            }
        }

        throw new Exception('ciebit.legislation.collection.not-found-document');
    }

    public function getIterator(): ArrayIterator
    {
        return $this->items->getIterator();
    }

    public function hasDocumentWithId(string $id): bool
    {
        foreach ($this->getIterator() as $document) {
            if ($document->getId() == $id) {
                return true;
            }
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayObject()->getArrayCopy();
    }
}
