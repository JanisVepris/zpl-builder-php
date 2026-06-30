<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidDataOrder;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\GetRfidTagId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GetRfidTagId::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class GetRfidTagIdTest extends UnitTestCase
{
    public function testFieldNumberAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GetRfidTagId(10000, RfidDataOrder::Normal, 0, RfidMotion::Feed);
    }

    public function testRendersDefaults(): void
    {
        $command = new GetRfidTagId(0, RfidDataOrder::Normal, 0, RfidMotion::Feed);

        self::assertSame('^RI0,0,0,0', (string) $command);
    }

    public function testRendersReversedNoFeed(): void
    {
        $command = new GetRfidTagId(1, RfidDataOrder::Reversed, 5, RfidMotion::NoFeed);

        self::assertSame('^RI1,1,5,1', (string) $command);
    }

    public function testRetriesAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new GetRfidTagId(0, RfidDataOrder::Normal, 11, RfidMotion::Feed);
    }
}
