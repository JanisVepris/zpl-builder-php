<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldHexIndicator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldHexIndicator::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class FieldHexIndicatorTest extends UnitTestCase
{
    public function testCaretIndicatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldHexIndicator('^');
    }

    public function testEmptyIndicatorThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldHexIndicator('');
    }

    public function testMultiCharIndicatorThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldHexIndicator('__');
    }

    public function testRendersCustomIndicator(): void
    {
        self::assertSame('^FH%', (string) new FieldHexIndicator('%'));
    }

    public function testRendersWithIndicator(): void
    {
        self::assertSame('^FH_', (string) new FieldHexIndicator('_'));
    }

    public function testTildeIndicatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldHexIndicator('~');
    }
}
