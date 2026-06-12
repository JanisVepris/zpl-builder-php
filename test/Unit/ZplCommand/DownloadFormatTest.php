<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\DownloadFormat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(DownloadFormat::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class DownloadFormatTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadFormat(StorageDevice::Ram, 'STOREFMT', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadFormat(StorageDevice::Ram, '', 'ZPL');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadFormat(StorageDevice::Ram, 'STOREFMT', 'ZPLX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new DownloadFormat(StorageDevice::Ram, str_repeat('A', 17), 'ZPL');
    }

    public function testRendersWithFlashStorage(): void
    {
        self::assertSame(
            '^DFE:STOREFMT.ZPL',
            (string) new DownloadFormat(StorageDevice::Flash, 'STOREFMT', 'ZPL'),
        );
    }

    public function testRendersWithRamDefault(): void
    {
        self::assertSame(
            '^DFR:STOREFMT.ZPL',
            (string) new DownloadFormat(StorageDevice::Ram, 'STOREFMT', 'ZPL'),
        );
    }
}
