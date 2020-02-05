<?php

declare(strict_types=1);

namespace Ciebit\Legislation;

use MyCLabs\Enum\Enum;

class Status extends Enum
{
    public const ACTIVE = 3;
    public const ANALYZE = 2;
    public const DRAFT = 1;
    public const TRASH = 4;
}
