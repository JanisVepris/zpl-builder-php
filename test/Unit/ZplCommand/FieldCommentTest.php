<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\FieldComment;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldComment::class)]
class FieldCommentTest extends UnitTestCase
{
    public function testCaretInTextThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldComment('contains ^XA');
    }

    public function testRendersComment(): void
    {
        self::assertSame('^FX section header', (string) new FieldComment(' section header'));
    }

    public function testRendersEmptyComment(): void
    {
        self::assertSame('^FX', (string) new FieldComment(''));
    }

    public function testTildeInTextThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldComment('contains ~JS');
    }
}
