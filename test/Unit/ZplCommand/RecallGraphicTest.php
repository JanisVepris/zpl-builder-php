<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\RecallGraphic;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(RecallGraphic::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class RecallGraphicTest extends UnitTestCase
{
    public function testEmptyNameThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, '', 'GRF', 1, 1);
    }

    public function testMagnificationXAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, 'SAMPLE', 'GRF', 11, 1);
    }

    public function testMagnificationXBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, 'SAMPLE', 'GRF', 0, 1);
    }

    public function testMagnificationYAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, 'SAMPLE', 'GRF', 1, 11);
    }

    public function testMagnificationYBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, 'SAMPLE', 'GRF', 1, 0);
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RecallGraphic(StorageDevice::Ram, str_repeat('A', 17), 'GRF', 1, 1);
    }

    public function testRendersWithDramDefault(): void
    {
        self::assertSame(
            '^XGR:SAMPLE.GRF,1,1',
            (string) new RecallGraphic(StorageDevice::Ram, 'SAMPLE', 'GRF', 1, 1),
        );
    }

    public function testRendersWithMagnification(): void
    {
        self::assertSame(
            '^XGE:LOGO.GRF,2,3',
            (string) new RecallGraphic(StorageDevice::Flash, 'LOGO', 'GRF', 2, 3),
        );
    }
}
