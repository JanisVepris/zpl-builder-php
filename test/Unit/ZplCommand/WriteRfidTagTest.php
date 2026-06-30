<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RfidWriteProtect;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\WriteRfidTag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(WriteRfidTag::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class WriteRfidTagTest extends UnitTestCase
{
    public function testBlockBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new WriteRfidTag(-1, 0, RfidMotion::Feed, RfidWriteProtect::NotProtected, RfidByteFormat::Ascii, false);
    }

    public function testRendersAllParameters(): void
    {
        $command = new WriteRfidTag(
            1,
            5,
            RfidMotion::NoFeed,
            RfidWriteProtect::WriteProtected,
            RfidByteFormat::Hexadecimal,
            true,
        );

        self::assertSame('^WT1,5,1,1,1,Y', (string) $command);
    }

    public function testRendersDefaults(): void
    {
        $command = new WriteRfidTag(
            0,
            0,
            RfidMotion::Feed,
            RfidWriteProtect::NotProtected,
            RfidByteFormat::Ascii,
            false,
        );

        self::assertSame('^WT0,0,0,0,0,N', (string) $command);
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new WriteRfidTag(0, 11, RfidMotion::Feed, RfidWriteProtect::NotProtected, RfidByteFormat::Ascii, false);
    }
}
