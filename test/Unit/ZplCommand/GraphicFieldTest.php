<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\GraphicFieldCompression;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GraphicField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GraphicField::class)]
#[UsesClass(GraphicFieldCompression::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class GraphicFieldTest extends UnitTestCase
{
    public function testByteCountAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 100000, 8000, 80, 'FF');
    }

    public function testByteCountBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 0, 8000, 80, 'FF');
    }

    public function testBytesPerRowAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 8000, 8000, 100000, 'FF');
    }

    public function testBytesPerRowBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 8000, 8000, 0, 'FF');
    }

    public function testDataContainingCaretThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 4, 4, 2, 'FF^00');
    }

    public function testFieldCountAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 8000, 100000, 80, 'FF');
    }

    public function testFieldCountBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GraphicField(GraphicFieldCompression::AsciiHex, 8000, 0, 80, 'FF');
    }

    public function testRendersAsciiHexField(): void
    {
        $command = new GraphicField(GraphicFieldCompression::AsciiHex, 8000, 8000, 80, 'FF00FF00');

        self::assertSame('^GFA,8000,8000,80,FF00FF00', (string) $command);
    }

    public function testRendersBinaryField(): void
    {
        $command = new GraphicField(GraphicFieldCompression::Binary, 8000, 8000, 80, 'BINARYDATA');

        self::assertSame('^GFB,8000,8000,80,BINARYDATA', (string) $command);
    }
}
