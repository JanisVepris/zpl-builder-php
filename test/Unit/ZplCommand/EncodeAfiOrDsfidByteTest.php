<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RfidWriteProtect;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\EncodeAfiOrDsfidByte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(EncodeAfiOrDsfidByte::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class EncodeAfiOrDsfidByteTest extends UnitTestCase
{
    public function testRendersAllParameters(): void
    {
        $command = new EncodeAfiOrDsfidByte(
            5,
            RfidMotion::NoFeed,
            RfidWriteProtect::WriteProtected,
            RfidByteFormat::Hexadecimal,
            RfidByteType::Dsfid,
        );

        self::assertSame('^WF5,1,1,1,D', (string) $command);
    }

    public function testRendersDefaults(): void
    {
        $command = new EncodeAfiOrDsfidByte(
            0,
            RfidMotion::Feed,
            RfidWriteProtect::NotProtected,
            RfidByteFormat::Ascii,
            RfidByteType::Afi,
        );

        self::assertSame('^WF0,0,0,0,A', (string) $command);
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new EncodeAfiOrDsfidByte(
            11,
            RfidMotion::Feed,
            RfidWriteProtect::NotProtected,
            RfidByteFormat::Ascii,
            RfidByteType::Afi,
        );
    }

    public function testRetriesBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new EncodeAfiOrDsfidByte(
            -1,
            RfidMotion::Feed,
            RfidWriteProtect::NotProtected,
            RfidByteFormat::Ascii,
            RfidByteType::Afi,
        );
    }
}
