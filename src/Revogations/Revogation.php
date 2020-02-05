<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Revogations;

use Ciebit\Legislation\Revogations\Mode;

class Revogation
{
    private string $description;
    private string $id;
    private Mode $mode;
    private string $revokedDocumentId;
    private string $substituteDocumentId;

    public function __construct(
        string $revokedDocumentId,
        string $substituteDocumentId,
        Mode $mode,
        string $description,
        string $id = ''
    ) {
        $this->description = $description;
        $this->id = $id;
        $this->mode = $mode;
        $this->revokedDocumentId = $revokedDocumentId;
        $this->substituteDocumentId = $substituteDocumentId;
    }

     public function getDescription(): string
     {
        return $this->description;
     }

     public function getId(): string
     {
        return $this->id;
     }

     public function getMode(): Mode
     {
        return $this->mode;
     }

     public function getRevokedDocumentId(): string
     {
        return $this->revokedDocumentId;
     }

     public function getSubstituteDocumentId(): string
     {
        return $this->substituteDocumentId;
     }
}
