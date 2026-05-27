<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldComment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldComment::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
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
