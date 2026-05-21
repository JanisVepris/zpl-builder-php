<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\LabelHome;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LabelHome::class)]
class LabelHomeTest extends UnitTestCase
{
    public function testNegativeXThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new LabelHome(-1, 0);
    }

    public function testRendersNonZeroCoordinates(): void
    {
        self::assertSame('^LH25,40', (string) new LabelHome(25, 40));
    }

    public function testRendersWithCoordinates(): void
    {
        self::assertSame('^LH0,0', (string) new LabelHome(0, 0));
    }
}
