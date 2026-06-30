<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidDataOrder;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\ReadRfidTag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ReadRfidTag::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class ReadRfidTagTest extends UnitTestCase
{
    public function testFieldNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ReadRfidTag(10000, 0, 1, RfidByteFormat::Ascii, 0, RfidMotion::Feed, RfidDataOrder::Normal);
    }

    public function testRendersAllParameters(): void
    {
        $command = new ReadRfidTag(1, 7, 3, RfidByteFormat::Hexadecimal, 5, RfidMotion::NoFeed, RfidDataOrder::Reversed);

        self::assertSame('^RT1,7,3,1,5,1,1', (string) $command);
    }

    public function testRendersDefaults(): void
    {
        $command = new ReadRfidTag(0, 0, 1, RfidByteFormat::Ascii, 0, RfidMotion::Feed, RfidDataOrder::Normal);

        self::assertSame('^RT0,0,1,0,0,0,0', (string) $command);
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new ReadRfidTag(0, 0, 1, RfidByteFormat::Ascii, 11, RfidMotion::Feed, RfidDataOrder::Normal);
    }
}
