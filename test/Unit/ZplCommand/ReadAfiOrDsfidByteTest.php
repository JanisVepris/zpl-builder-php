<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ReadAfiOrDsfidByte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ReadAfiOrDsfidByte::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ReadAfiOrDsfidByteTest extends UnitTestCase
{
    public function testFieldNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ReadAfiOrDsfidByte(10000, RfidByteFormat::Ascii, 0, RfidMotion::Feed, RfidByteType::Afi);
    }

    public function testRendersAfiAscii(): void
    {
        $command = new ReadAfiOrDsfidByte(0, RfidByteFormat::Ascii, 0, RfidMotion::Feed, RfidByteType::Afi);

        self::assertSame('^RA0,0,0,0,A', (string) $command);
    }

    public function testRendersDsfidHexadecimal(): void
    {
        $command = new ReadAfiOrDsfidByte(1, RfidByteFormat::Hexadecimal, 5, RfidMotion::NoFeed, RfidByteType::Dsfid);

        self::assertSame('^RA1,1,5,1,D', (string) $command);
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ReadAfiOrDsfidByte(0, RfidByteFormat::Ascii, 11, RfidMotion::Feed, RfidByteType::Afi);
    }
}
