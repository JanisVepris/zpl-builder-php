<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\UnsupportedFontExtensionException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FontName;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FontName::class)]
#[UsesClass(FontExtension::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(UnsupportedFontExtensionException::class)]
#[UsesClass(ValueAssert::class)]
class FontNameTest extends UnitTestCase
{
    public function testHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            32001,
            50,
            StorageDevice::MemoryCardB,
            'CYRI_UB',
            FontExtension::Font,
        );
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            -1,
            50,
            StorageDevice::MemoryCardB,
            'CYRI_UB',
            FontExtension::Font,
        );
    }

    public function testNameContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            50,
            StorageDevice::MemoryCardB,
            'CYRI.UB',
            FontExtension::Font,
        );
    }

    public function testNameEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            50,
            StorageDevice::MemoryCardB,
            '',
            FontExtension::Font,
        );
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            50,
            StorageDevice::MemoryCardB,
            str_repeat('A', FontName::MAX_NAME_BYTES + 1),
            FontExtension::Font,
        );
    }

    public function testRendersSpecExample(): void
    {
        self::assertSame(
            '^A@N,50,50,B:CYRI_UB.FNT',
            (string) new FontName(
                Orientation::Rotate0,
                50,
                50,
                StorageDevice::MemoryCardB,
                'CYRI_UB',
                FontExtension::Font,
            ),
        );
    }

    public function testRendersTrueTypeFromFlashRotated(): void
    {
        self::assertSame(
            '^A@R,70,40,E:ARI000.TTF',
            (string) new FontName(
                Orientation::Rotate90,
                70,
                40,
                StorageDevice::Flash,
                'ARI000',
                FontExtension::TrueType,
            ),
        );
    }

    public function testTrueTypeExtensionThrows(): void
    {
        // ^A@ accepts only .FNT and .TTF; .TTE belongs to ^CW (fontIdentifier()).
        $this->expectException(UnsupportedFontExtensionException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            50,
            StorageDevice::Flash,
            'ARI000',
            FontExtension::TrueTypeExtension,
        );
    }

    public function testWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            32001,
            StorageDevice::MemoryCardB,
            'CYRI_UB',
            FontExtension::Font,
        );
    }

    public function testWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new FontName(
            Orientation::Rotate0,
            50,
            -1,
            StorageDevice::MemoryCardB,
            'CYRI_UB',
            FontExtension::Font,
        );
    }
}
