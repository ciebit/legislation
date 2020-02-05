<?php

declare(strict_types=1);

namespace Ciebit\Legislation;

use Ciebit\Legislation\Status;
use DateTime;

abstract class Document
{
    private DateTime $dateTime;
    private string $description;
    private array $filesId;
    private string $id;
    private string $slug;
    private Status $status;
    private string $title;

    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
        string $slug = '',
        string $description = '',
        string $id = '',
        array $filesId = []
    ) {
        $this->dateTime = $dateTime;
        $this->description = $description;
        $this->setFiles(...$filesId);
        $this->id = $id;
        $this->slug = $slug;
        $this->status = $status;
        $this->title = $title;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFilesId(): array
    {
        return $this->filesId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    private function setFiles(string ...$ids): self
    {
        $this->filesId = $ids;
        return $this;
    }
}
