<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ImageLoad;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ImageLoad::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ImageLoadTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageLoad(StorageDevice::Ram, 'LOGO', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageLoad(StorageDevice::Ram, '', 'GRF');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageLoad(StorageDevice::Ram, 'LOGO', 'GRFX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageLoad(StorageDevice::Ram, str_repeat('A', 17), 'GRF');
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^ILR:LOGO.GRF',
            (string) new ImageLoad(StorageDevice::Ram, 'LOGO', 'GRF'),
        );
    }

    public function testRendersWithFlashStorage(): void
    {
        self::assertSame(
            '^ILE:SAMPLE.GRF',
            (string) new ImageLoad(StorageDevice::Flash, 'SAMPLE', 'GRF'),
        );
    }
}
