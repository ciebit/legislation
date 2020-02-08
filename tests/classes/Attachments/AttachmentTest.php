<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Attachments;

use Ciebit\Legislation\Attachments\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    protected const ID = '1';
    protected const DOCUMENT_ID = '2';
    protected const FILE_ID = '3';

    public function testeCreate(): void
    {
        $attachment = new Attachment(
            self::DOCUMENT_ID,
            self::FILE_ID,
            self::ID
        );

        $this->assertEquals(self::DOCUMENT_ID, $attachment->getDocumentId());
        $this->assertEquals(self::FILE_ID, $attachment->getFileId());
        $this->assertEquals(self::ID, $attachment->getId());
    }
}
