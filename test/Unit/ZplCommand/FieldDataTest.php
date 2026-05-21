<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\FieldData;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldData::class)]
class FieldDataTest extends UnitTestCase
{
    public function testRendersWithData(): void
    {
        self::assertSame('^FDHello World', (string) new FieldData('Hello World'));
    }

    public function testRendersEmptyData(): void
    {
        self::assertSame('^FD', (string) new FieldData(''));
    }

    public function testDataTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FieldData(str_repeat('a', 3073));
    }

    public function testCaretInDataThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldData('breakout^XA');
    }

    public function testTildeInDataThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FieldData('breakout~JS');
    }
}
