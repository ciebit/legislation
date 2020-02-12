<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Attachments;

class Attachment
{
    private string $description;
    private string $documentId;
    private string $fileId;
    private string $id;
    private string $title;

    public function __construct(
        string $documentId,
        string $fileId,
        string $title = '',
        string $description = '',
        string $id = ''
    ) {
        $this->description = $description;
        $this->documentId = $documentId;
        $this->fileId = $fileId;
        $this->id = $id;
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
