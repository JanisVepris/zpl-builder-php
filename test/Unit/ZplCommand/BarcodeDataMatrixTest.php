<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DataMatrixQuality;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeDataMatrix;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeDataMatrix::class)]
#[UsesClass(DataMatrixQuality::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeDataMatrixTest extends UnitTestCase
{
    public function testColumnsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc200, 50, null, null, null);
    }

    public function testColumnsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc200, 8, null, null, null);
    }

    public function testEscapeCharTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc200, null, null, null, '~~');
    }

    public function testFormatIdAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc0, null, null, 7, null);
    }

    public function testFormatIdBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc0, null, null, 0, null);
    }

    public function testModuleHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, -1, DataMatrixQuality::Ecc0, null, null, null, null);
    }

    public function testRendersFullySpecified(): void
    {
        $command = new BarcodeDataMatrix(Orientation::Rotate90, 10, DataMatrixQuality::Ecc200, 16, 16, null, '~');

        self::assertSame('^BXR,10,200,16,16,,~', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc0, null, null, null, null);

        self::assertSame('^BXN,0,0', (string) $command);
    }

    public function testRendersWithFormatIdTrimsTrailingEmptyParameters(): void
    {
        $command = new BarcodeDataMatrix(Orientation::Rotate0, 5, DataMatrixQuality::Ecc0, null, null, 6, null);

        self::assertSame('^BXN,5,0,,,6', (string) $command);
    }

    public function testRowsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc200, null, 50, null, null);
    }

    public function testRowsBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeDataMatrix(Orientation::Rotate0, 0, DataMatrixQuality::Ecc200, null, 8, null, null);
    }
}
