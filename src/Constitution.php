<?php

declare(strict_types=1);

namespace Ciebit\Legislation;

use Ciebit\Legislation\Status;
use Ciebit\Legislation\Document;
use DateTime;

class Constitution extends Document
{
    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
        string $slug = '',
        string $description = '',
        string $id = '',
        array $filesId = []
    ) {
        parent::__construct(
            $title,
            $dateTime,
            $status,
            $slug,
            $description,
            $id,
            $filesId
        );
    }
}