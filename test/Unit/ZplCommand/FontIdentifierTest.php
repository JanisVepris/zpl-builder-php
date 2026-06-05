<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\FontIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FontIdentifier::class)]
#[UsesClass(Font::class)]
#[UsesClass(FontExtension::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class FontIdentifierTest extends UnitTestCase
{
    public function testNameContainingSeparatorThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new FontIdentifier(
            font: Font::T,
            device: StorageDevice::Flash,
            name: 'ARI.AL',
            extension: FontExtension::TrueType,
        );
    }

    public function testNameEmptyThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FontIdentifier(
            font: Font::T,
            device: StorageDevice::Flash,
            name: '',
            extension: FontExtension::TrueType,
        );
    }

    public function testNameTooLongThrows(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new FontIdentifier(
            font: Font::T,
            device: StorageDevice::Flash,
            name: str_repeat('A', FontIdentifier::MAX_NAME_BYTES + 1),
            extension: FontExtension::TrueType,
        );
    }

    public function testRendersSpecExample(): void
    {
        self::assertSame(
            '^CWT,E:ARIAL.TTF',
            (string) new FontIdentifier(
                font: Font::T,
                device: StorageDevice::Flash,
                name: 'ARIAL',
                extension: FontExtension::TrueType,
            ),
        );
    }

    public function testRendersTrueTypeExtensionFromRamWithNumericIdentifier(): void
    {
        self::assertSame(
            '^CW1,R:ANMDS.TTE',
            (string) new FontIdentifier(
                font: Font::One,
                device: StorageDevice::Ram,
                name: 'ANMDS',
                extension: FontExtension::TrueTypeExtension,
            ),
        );
    }
}
