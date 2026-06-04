<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FieldVariable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FieldVariable::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class FieldVariableTest extends UnitTestCase
{
    public function testCaretInDataThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldVariable('breakout^XA');
    }

    public function testDataTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldVariable(str_repeat('a', 3073));
    }

    public function testRendersEmptyData(): void
    {
        self::assertSame('^FV', (string) new FieldVariable(''));
    }

    public function testRendersWithData(): void
    {
        self::assertSame('^FVHello World', (string) new FieldVariable('Hello World'));
    }

    public function testTildeInDataThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldVariable('breakout~JS');
    }
}
