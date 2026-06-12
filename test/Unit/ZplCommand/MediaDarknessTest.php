<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\MediaDarkness;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(MediaDarkness::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class MediaDarknessTest extends UnitTestCase
{
    public function testLevelAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new MediaDarkness(31);
    }

    public function testLevelBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new MediaDarkness(-31);
    }

    public function testRendersNegativeAdjustment(): void
    {
        self::assertSame('^MD-9', (string) new MediaDarkness(-9));
    }

    public function testRendersPositiveAdjustment(): void
    {
        self::assertSame('^MD30', (string) new MediaDarkness(30));
    }
}
