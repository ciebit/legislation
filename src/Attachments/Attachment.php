<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Attachments;

class Attachment
{
    private string $documentId;
    private string $fileId;
    private string $id;

    public function __construct(
        string $documentId,
        string $fileId,
        string $id = ''
    ) {
        $this->documentId = $documentId;
        $this->fileId = $fileId;
        $this->id = $id;
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
}
