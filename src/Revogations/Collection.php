<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Revogations;

use ArrayIterator;
use ArrayObject;
use Ciebit\Legislation\Revogations\Revogation;
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

    public function add(Revogation ...$revogationList): self
    {
        foreach($revogationList as $revogation) {
            $this->items->append($revogation);
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
    public function getById(string $id): Revogation
    {
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getId() == $id) {
                return $revogation;
            }
        }

        throw new Exception('ciebit.legislation.revogations.collection.not-found-revogation');
    }

    public function getIterator(): ArrayIterator
    {
        return $this->items->getIterator();
    }

    public function hasRevogationWithId(string $id): bool
    {
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getId() == $id) {
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