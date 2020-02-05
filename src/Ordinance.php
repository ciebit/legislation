<?php

declare(strict_types=1);

namespace Ciebit\Legislation;

use Ciebit\Legislation\Status;
use Ciebit\Legislation\Document;
use DateTime;

class Ordinance extends Document
{
    private int $number;

    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
        int $number,
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

        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}