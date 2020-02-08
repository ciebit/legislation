<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Attachments;

use ArrayIterator;
use ArrayObject;
use Ciebit\Legislation\Attachments\Attachment;
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

    public function add(Attachment ...$attachmentList): self
    {
        foreach($attachmentList as $attachment) {
            $this->items->append($attachment);
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
    public function getById(string $id): Attachment
    {
        foreach ($this->getIterator() as $attachment) {
            if ($attachment->getId() == $id) {
                return $attachment;
            }
        }

        throw new Exception('ciebit.legislation.attachment.collection.not-found-Attachment');
    }

    public function getIterator(): ArrayIterator
    {
        return $this->items->getIterator();
    }

    public function hasWithId(string $id): bool
    {
        foreach ($this->getIterator() as $attachment) {
            if ($attachment->getId() == $id) {
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
