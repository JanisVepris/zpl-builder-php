<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ImageSave;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ImageSave::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ImageSaveTest extends UnitTestCase
{
    public function testEmptyExtensionThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageSave(StorageDevice::Ram, 'SAMPLE', '', true);
    }

    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageSave(StorageDevice::Ram, '', 'GRF', true);
    }

    public function testExtensionTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageSave(StorageDevice::Ram, 'SAMPLE', 'GRFX', true);
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new ImageSave(StorageDevice::Ram, str_repeat('A', 17), 'GRF', true);
    }

    public function testRendersAndPrintsAfterStoring(): void
    {
        self::assertSame(
            '^ISR:SAMPLE.GRF,Y',
            (string) new ImageSave(StorageDevice::Ram, 'SAMPLE', 'GRF', true),
        );
    }

    public function testRendersWithoutPrintingAfterStoring(): void
    {
        self::assertSame(
            '^ISE:SAMPLE.PNG,N',
            (string) new ImageSave(StorageDevice::Flash, 'SAMPLE', 'PNG', false),
        );
    }
}
