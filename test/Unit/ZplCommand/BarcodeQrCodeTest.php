<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\QrErrorCorrection;
use Janisvepris\ZplBuilder\Enum\QrModel;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeQrCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeQrCode::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(QrErrorCorrection::class)]
#[UsesClass(QrModel::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeQrCodeTest extends UnitTestCase
{
    public function testMagnificationAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeQrCode(QrModel::Model2, 11, null, null);
    }

    public function testMagnificationBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeQrCode(QrModel::Model2, 0, null, null);
    }

    public function testMaskAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeQrCode(QrModel::Model2, 2, null, 8);
    }

    public function testMaskBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeQrCode(QrModel::Model2, 2, null, 0);
    }

    public function testRendersErrorCorrectionOnly(): void
    {
        $command = new BarcodeQrCode(QrModel::Model2, 10, QrErrorCorrection::HighReliability, null);

        self::assertSame('^BQN,2,10,Q', (string) $command);
    }

    public function testRendersFullySpecified(): void
    {
        $command = new BarcodeQrCode(QrModel::Model1, 7, QrErrorCorrection::UltraHighReliability, 3);

        self::assertSame('^BQN,1,7,H,3', (string) $command);
    }

    public function testRendersMaskWithoutErrorCorrection(): void
    {
        $command = new BarcodeQrCode(QrModel::Model2, 10, null, 5);

        self::assertSame('^BQN,2,10,,5', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeQrCode(QrModel::Model2, 10, null, null);

        self::assertSame('^BQN,2,10', (string) $command);
    }
}
