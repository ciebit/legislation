<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents;

use Ciebit\Legislation\Documents\Status;
use Ciebit\Legislation\Documents\Document;
use DateTime;

class Constitution extends Document
{
    public function __construct(
        string $title,
        DateTime $dateTime,
        Status $status,
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
    }

    public static function getType(): string
    {
        return 'constitution';
    }
}