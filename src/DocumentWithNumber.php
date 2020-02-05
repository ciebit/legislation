<?php

declare(strict_types=1);

namespace Ciebit\Legislation;

use Ciebit\Legislation\Document;
use Ciebit\Legislation\Status;
use DateTime;

abstract class DocumentWithNumber extends Document
{
    private int $number;

    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
        int $number,
        string $slug = '',
        string $description = '',
        string $id = ''
    ) {
        parent::__construct(
            $title,
            $dateTime,
            $status,
            $slug,
            $description,
            $id,
        );

        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
