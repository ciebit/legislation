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
        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getId() == $id) {
                return $revogation;
            }
        }

        throw new Exception('ciebit.legislation.revogations.collection.not-found-revogation', 303);
    }

    public function getAllByRevokedDocumentId(string $id): Collection
    {
        $collection = new Collection();

        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getRevokedDocumentId() == $id) {
                $collection->add($revogation);
            }
        }

        return $collection;
    }

    /**
     * @throws Exception
     */
    public function getAllBySubstituteDocumentId(string $id): Collection
    {
        $collection = new Collection;

        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getSubstituteDocumentId() == $id) {
                $collection->add($revogation);
            }
        }

        return $collection;
    }

    /**
     * @throws Exception
     */
    public function getByRevokedDocumentId(string $id): Revogation
    {
        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getRevokedDocumentId() == $id) {
                return $revogation;
            }
        }

        throw new Exception('ciebit.legislation.revogations.collection.not-found-revogation', 303);
    }

    /**
     * @throws Exception
     */
    public function getBySubstituteDocumentId(string $id): Revogation
    {
        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getSubstituteDocumentId() == $id) {
                return $revogation;
            }
        }

        throw new Exception('ciebit.legislation.revogations.collection.not-found-revogation', 303);
    }

    public function getIterator(): ArrayIterator
    {
        return $this->items->getIterator();
    }

    public function hasRevokedWithDocumentId(string $id): bool
    {
        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getRevokedDocumentId() == $id) {
                return true;
            }
        }

        return false;
    }

    public function hasSubstituteWithDocumentId(string $id): bool
    {
        /** @var Revogation $revogation */
        foreach ($this->getIterator() as $revogation) {
            if ($revogation->getSubstituteDocumentId() == $id) {
                return true;
            }
        }

        return false;
    }

    public function hasWithId(string $id): bool
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
