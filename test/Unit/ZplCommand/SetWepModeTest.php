<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WepEncryptionMode;
use Janisvepris\ZplBuilder\Enum\WepKeyStorage;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetWepMode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetWepMode::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class SetWepModeTest extends UnitTestCase
{
    public function testIndexAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetWepMode(WepEncryptionMode::Bit128, 5, null, null, null, null, null, null);
    }

    public function testKeyWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new SetWepMode(WepEncryptionMode::Bit40, null, null, null, '12,45', null, null, null);
    }

    public function testRendersKey4AfterInteriorGaps(): void
    {
        $command = new SetWepMode(WepEncryptionMode::Bit128, 4, null, WepKeyStorage::Hex, null, null, null, '98765');

        self::assertSame('^WE128,4,,H,,,,98765', (string) $command);
    }

    public function testRendersModeAndKey1(): void
    {
        $command = new SetWepMode(WepEncryptionMode::Bit40, null, null, null, '12345', null, null, null);

        self::assertSame('^WE40,,,,12345', (string) $command);
    }

    public function testRendersModeOnly(): void
    {
        $command = new SetWepMode(WepEncryptionMode::Off, null, null, null, null, null, null, null);

        self::assertSame('^WEOFF', (string) $command);
    }
}
