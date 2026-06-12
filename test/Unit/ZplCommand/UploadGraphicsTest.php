<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\UploadGraphics;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(UploadGraphics::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class UploadGraphicsTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new UploadGraphics(StorageDevice::Ram, 'LOGO', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new UploadGraphics(StorageDevice::Ram, '', 'GRF');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new UploadGraphics(StorageDevice::Ram, 'LOGO', 'GRFX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new UploadGraphics(StorageDevice::Ram, str_repeat('A', 17), 'GRF');
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^HYR:LOGO.GRF',
            (string) new UploadGraphics(StorageDevice::Ram, 'LOGO', 'GRF'),
        );
    }

    public function testRendersWithPngExtension(): void
    {
        self::assertSame(
            '^HYE:SAMPLE.PNG',
            (string) new UploadGraphics(StorageDevice::Flash, 'SAMPLE', 'PNG'),
        );
    }
}
