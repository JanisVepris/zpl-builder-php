<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DownloadExtension;
use Janisvepris\ZplBuilder\Enum\DownloadFormat;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\DownloadObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DownloadObject::class)]
#[UsesClass(DownloadExtension::class)]
#[UsesClass(DownloadFormat::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class DownloadObjectTest extends UnitTestCase
{
    public function testBytesPerRowBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DownloadObject(StorageDevice::Ram, 'LOGO', DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 8000, -1, 'FF00');
    }

    public function testDataContainingCaretThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new DownloadObject(StorageDevice::Ram, 'LOGO', DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 8000, 80, 'FF^00');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadObject(StorageDevice::Ram, '', DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 8000, 80, 'FF00');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadObject(StorageDevice::Ram, str_repeat('A', 17), DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 8000, 80, 'FF00');
    }

    public function testRendersGraphicDownload(): void
    {
        self::assertSame(
            '~DYR:LOGO,A,G,8000,80,FF00',
            (string) new DownloadObject(StorageDevice::Ram, 'LOGO', DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 8000, 80, 'FF00'),
        );
    }

    public function testRendersTrueTypeFontWithSeparateData(): void
    {
        self::assertSame(
            '~DYE:FONTFILE.TTF,B,T,52010,0,',
            (string) new DownloadObject(StorageDevice::Flash, 'FONTFILE.TTF', DownloadFormat::UncompressedBinary, DownloadExtension::TrueType, 52010, 0, ''),
        );
    }

    public function testTotalBytesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new DownloadObject(StorageDevice::Ram, 'LOGO', DownloadFormat::UncompressedAscii, DownloadExtension::Grf, 0, 80, 'FF00');
    }
}
