<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents;

use Ciebit\Legislation\Documents\Status;
use DateTime;

abstract class Document
{
    private DateTime $dateTime;
    private string $description;
    private string $id;
    private array $labelsId;
    private string $slug;
    private Status $status;
    private string $title;

    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
        string $slug = '',
        string $description = '',
        array $labelsId = [],
        string $id = ''
    ) {
        $this->dateTime = $dateTime;
        $this->description = $description;
        $this->id = $id;
        $this->slug = $slug;
        $this->status = $status;
        $this->title = $title;

        $this->setLabelsId(...$labelsId);
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabelsId(): array
    {
        return $this->labelsId;
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

    abstract public static function getType(): string;

    private function setLabelsId(string ...$ids): self
    {
        $this->labelsId = $ids;
        return $this;
    }
}
