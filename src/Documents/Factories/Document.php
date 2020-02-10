<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Document as DocumentEntity;
use Ciebit\Legislation\Documents\Status;
use DateTime;

abstract class Document
{
    protected DateTime $dateTime;
    protected string $description;
    protected string $id;
    protected string $slug;
    protected Status $status;
    protected string $title;

    abstract public function create(): DocumentEntity;

    public function setData(array $data): self
    {
        $data['dateTime'] && $this->setDateTime($data['dateTime']);
        $data['description'] && $this->setDescription($data['description']);
        $data['id'] && $this->setId($data['id']);
        $data['slug'] && $this->setSlug($data['slug']);
        $data['status'] && $this->setStatus($data['status']);
        $data['title'] && $this->setTitle($data['title']);
        return $this;
    }

    public function setDateTime(DateTime $value): self
    {
        $this->dateTime = $value;
        return $this;
    }

    abstract public function setDocument(DocumentEntity $document): self;

    public function setDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }

    public function setId(string $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function setNumber(int $value): self
    {
        $this->number = $value;
        return $this;
    }

    public function setSlug(string $value): self
    {
        $this->slug = $value;
        return $this;
    }

    public function setStatus(Status $value): self
    {
        $this->status = $value;
        return $this;
    }

    public function setTitle(string $value): self
    {
        $this->title = $value;
        return $this;
    }
}