<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\HostFormat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(HostFormat::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class HostFormatTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new HostFormat(StorageDevice::Ram, 'FILE1', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new HostFormat(StorageDevice::Ram, '', 'ZPL');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new HostFormat(StorageDevice::Ram, 'FILE1', 'ZPLX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new HostFormat(StorageDevice::Ram, str_repeat('A', 17), 'ZPL');
    }

    public function testRendersWithMemoryCardStorage(): void
    {
        self::assertSame(
            '^HFB:FILE1.ZPL',
            (string) new HostFormat(StorageDevice::MemoryCardB, 'FILE1', 'ZPL'),
        );
    }

    public function testRendersWithRamDefault(): void
    {
        self::assertSame(
            '^HFR:FILE1.ZPL',
            (string) new HostFormat(StorageDevice::Ram, 'FILE1', 'ZPL'),
        );
    }
}
