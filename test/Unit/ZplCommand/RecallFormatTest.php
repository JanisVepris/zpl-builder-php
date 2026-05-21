<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\RecallFormat;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RecallFormat::class)]
class RecallFormatTest extends UnitTestCase
{
    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RecallFormat(StorageDevice::Ram, '', 'ZPL');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RecallFormat(StorageDevice::Ram, 'SAMPLE', 'ZPLX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RecallFormat(StorageDevice::Ram, str_repeat('A', 17), 'ZPL');
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^XFR:SAMPLE.ZPL',
            (string) new RecallFormat(StorageDevice::Ram, 'SAMPLE', 'ZPL'),
        );
    }

    public function testRendersWithFlashStorage(): void
    {
        self::assertSame(
            '^XFE:STOREFMT.ZPL',
            (string) new RecallFormat(StorageDevice::Flash, 'STOREFMT', 'ZPL'),
        );
    }

    public function testRendersWithMemoryCardAndCustomExtension(): void
    {
        self::assertSame(
            '^XFB:LOGO.GRF',
            (string) new RecallFormat(StorageDevice::MemoryCardB, 'LOGO', 'GRF'),
        );
    }
}
