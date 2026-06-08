<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SelectEncoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SelectEncoding::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SelectEncodingTest extends UnitTestCase
{
    public function testNameAtMaxLengthRenders(): void
    {
        self::assertSame(
            '^SER:ABCDEFGH.DAT',
            (string) new SelectEncoding(StorageDevice::Ram, 'ABCDEFGH'),
        );
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SelectEncoding(StorageDevice::Ram, 'ABCDEFGHI');
    }

    public function testNameTooShortThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new SelectEncoding(StorageDevice::Ram, '');
    }

    public function testRendersWithFlashStorage(): void
    {
        self::assertSame(
            '^SEE:CP1252.DAT',
            (string) new SelectEncoding(StorageDevice::Flash, 'CP1252'),
        );
    }

    public function testRendersWithRamDefaultDevice(): void
    {
        self::assertSame(
            '^SER:UTF8.DAT',
            (string) new SelectEncoding(StorageDevice::Ram, 'UTF8'),
        );
    }
}
