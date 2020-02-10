<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Documents;

use PHPUnit\Framework\TestCase;

abstract class Document extends TestCase
{
    protected const DATE_TIME = '2020-02-05 15:31:22';
    protected const DESCRIPTION = 'Description Example';
    protected const ID = '4';
    protected const LABELS_IS = ['22','33','44'];
    protected const SLUG = 'law-567';
    protected const STATUS = 3;
    protected const TITLE = 'Law 567/2020';
}
