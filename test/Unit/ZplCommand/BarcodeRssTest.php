<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\RssSymbologyType;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeRss;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BarcodeRss::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(RssSymbologyType::class)]
#[UsesClass(ValueAssert::class)]
class BarcodeRssTest extends UnitTestCase
{
    public function testBarcodeHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 1, 0, 22);
    }

    public function testMagnificationAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 11, 1, 25, 22);
    }

    public function testMagnificationBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 0, 1, 25, 22);
    }

    public function testRendersFullySpecified(): void
    {
        $command = new BarcodeRss(Orientation::Rotate0, RssSymbologyType::UpcA, 5, 2, 100, 20);

        self::assertSame('^BRN,7,5,2,100,20', (string) $command);
    }

    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 1, 25, 22);

        self::assertSame('^BRR,1,1,1,25,22', (string) $command);
    }

    public function testSegmentWidthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 1, 25, 23);
    }

    public function testSegmentWidthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 1, 25, 1);
    }

    public function testSeparatorHeightAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 3, 25, 22);
    }

    public function testSeparatorHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeRss(Orientation::Rotate90, RssSymbologyType::Rss14, 1, 0, 25, 22);
    }
}
