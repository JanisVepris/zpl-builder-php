<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ImageMove;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ImageMove::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ImageMoveTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageMove(StorageDevice::Ram, 'LOGO', '');
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageMove(StorageDevice::Ram, '', 'GRF');
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageMove(StorageDevice::Ram, 'LOGO', 'GRFX');
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageMove(StorageDevice::Ram, str_repeat('A', 17), 'GRF');
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^IMR:LOGO.GRF',
            (string) new ImageMove(StorageDevice::Ram, 'LOGO', 'GRF'),
        );
    }

    public function testRendersWithFlashStorage(): void
    {
        self::assertSame(
            '^IME:SAMPLE.GRF',
            (string) new ImageMove(StorageDevice::Flash, 'SAMPLE', 'GRF'),
        );
    }
}
