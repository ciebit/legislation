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
    protected array $labelsId;
    protected string $slug;
    protected Status $status;
    protected string $title;

    public function __construct()
    {
        $this->dateTime = new DateTime;
        $this->description = '';
        $this->id = '';
        $this->labelsId = [];
        $this->slug = '';
        $this->status = Status::DRAFT();
        $this->title = '';
    }

    abstract public function create(): DocumentEntity;

    public function setData(array $data): self
    {
        isset($data['dateTime']) && $this->setDateTime($data['dateTime']);
        isset($data['description']) && $this->setDescription($data['description']);
        isset($data['id']) && $this->setId($data['id']);
        isset($data['labelsId']) && $this->setLabelsId(...$data['labelsId']);
        isset($data['slug']) && $this->setSlug($data['slug']);
        isset($data['status']) && $this->setStatus($data['status']);
        isset($data['title']) && $this->setTitle($data['title']);
        
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

    public function setLabelsId(string ...$ids): self
    {
        $this->labelsId = $ids;
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