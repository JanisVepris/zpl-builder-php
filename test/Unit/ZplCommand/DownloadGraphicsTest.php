<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\DownloadGraphics;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DownloadGraphics::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class DownloadGraphicsTest extends UnitTestCase
{
    public function testBytesPerRowBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DownloadGraphics(StorageDevice::Ram, 'SAMPLE', 'GRF', 8000, 0, 'FF00');
    }

    public function testDataContainingTildeThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new DownloadGraphics(StorageDevice::Ram, 'SAMPLE', 'GRF', 8000, 80, 'FF~00');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadGraphics(StorageDevice::Ram, '', 'GRF', 8000, 80, 'FF00');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadGraphics(StorageDevice::Ram, 'SAMPLE', 'GRFX', 8000, 80, 'FF00');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadGraphics(StorageDevice::Ram, str_repeat('A', 17), 'GRF', 8000, 80, 'FF00');
    }

    public function testRendersGraphicDownload(): void
    {
        self::assertSame(
            '~DGR:SAMPLE.GRF,8000,80,FF00FF00',
            (string) new DownloadGraphics(StorageDevice::Ram, 'SAMPLE', 'GRF', 8000, 80, 'FF00FF00'),
        );
    }

    public function testRendersToFlashStorage(): void
    {
        self::assertSame(
            '~DGE:LOGO.GRF,16,2,ABCD',
            (string) new DownloadGraphics(StorageDevice::Flash, 'LOGO', 'GRF', 16, 2, 'ABCD'),
        );
    }

    public function testTotalBytesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DownloadGraphics(StorageDevice::Ram, 'SAMPLE', 'GRF', 0, 80, 'FF00');
    }
}
