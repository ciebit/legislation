<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Revogations;

use Ciebit\Legislation\Revogations\Mode;
use Ciebit\Legislation\Revogations\Revogation;
use PHPUnit\Framework\TestCase;

class RevogationTest extends TestCase
{
    protected const DESCRIPTION = 'Description Example';
    protected const ID = '1';
    protected const MODE = 1;
    protected const REVOKED_DOCUMENT_ID = '2';
    protected const SUBSTITUTE_DOCUMENT_ID = '3';

    public function testeCreate(): void
    {
        $revogation = new Revogation(
            self::REVOKED_DOCUMENT_ID,
            self::SUBSTITUTE_DOCUMENT_ID,
            new Mode(self::MODE),
            self::DESCRIPTION,
            self::ID
        );

        $this->assertEquals(self::DESCRIPTION, $revogation->getDescription());
        $this->assertEquals(self::REVOKED_DOCUMENT_ID, $revogation->getRevokedDocumentId());
        $this->assertEquals(self::SUBSTITUTE_DOCUMENT_ID, $revogation->getSubstituteDocumentId());
        $this->assertEquals(self::MODE, $revogation->getMode()->getValue());
        $this->assertEquals(self::ID, $revogation->getId());
    }
}
