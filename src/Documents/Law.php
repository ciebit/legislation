<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents;

use Ciebit\Legislation\Documents\DocumentWithNumber;

class Law extends DocumentWithNumber
{
    public static function getType(): string
    {
        return 'law';
    }
}