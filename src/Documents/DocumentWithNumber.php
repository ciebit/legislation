<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents;

use Ciebit\Legislation\Documents\Document;
use Ciebit\Legislation\Documents\Status;
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
        array $labelsId = [],
        string $id = ''
    ) {
        parent::__construct(
            $title,
            $dateTime,
            $status,
            $slug,
            $description,
            $labelsId,
            $id,
        );

        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
