<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Revogations;

use MyCLabs\Enum\Enum;

class Mode extends Enum
{
    public const INTEGRAL = 1;
    public const PARTIAL = 2;
}
