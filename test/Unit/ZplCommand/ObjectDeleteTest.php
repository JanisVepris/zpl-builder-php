<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ObjectDelete;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ObjectDelete::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ObjectDeleteTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ObjectDelete(StorageDevice::Ram, 'SAMPLE', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ObjectDelete(StorageDevice::Ram, '', 'GRF');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ObjectDelete(StorageDevice::Ram, 'SAMPLE', 'GRFX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ObjectDelete(StorageDevice::Ram, str_repeat('A', 17), 'GRF');
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^IDR:SAMPLE.GRF',
            (string) new ObjectDelete(StorageDevice::Ram, 'SAMPLE', 'GRF'),
        );
    }

    public function testRendersWithWildcardExtension(): void
    {
        self::assertSame(
            '^IDR:SAMPLE.*',
            (string) new ObjectDelete(StorageDevice::Ram, 'SAMPLE', '*'),
        );
    }

    public function testRendersWithWildcardName(): void
    {
        self::assertSame(
            '^IDR:*.ZPL',
            (string) new ObjectDelete(StorageDevice::Ram, '*', 'ZPL'),
        );
    }
}
