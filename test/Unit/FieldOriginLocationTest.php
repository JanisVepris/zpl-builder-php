<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\FieldOriginLocation;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldOriginLocation::class)]
class FieldOriginLocationTest extends UnitTestCase
{
    public function testAtRendersCoordinates(): void
    {
        self::assertSame('100,200', (string) FieldOriginLocation::at(100, 200));
    }

    public function testAtRendersUpperBoundCoordinates(): void
    {
        self::assertSame('32000,32000', (string) FieldOriginLocation::at(32000, 32000));
    }

    public function testAtRendersZeroCoordinates(): void
    {
        self::assertSame('0,0', (string) FieldOriginLocation::at(0, 0));
    }

    public function testAtWithNegativeXThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        FieldOriginLocation::at(-1, 0);
    }

    public function testAtWithNegativeYThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        FieldOriginLocation::at(0, -1);
    }

    public function testAtWithXAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        FieldOriginLocation::at(32001, 0);
    }

    public function testAtWithYAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        FieldOriginLocation::at(0, 32001);
    }

    public function testExcludedRendersDoubleEMarker(): void
    {
        self::assertSame('e,e', (string) FieldOriginLocation::excluded());
    }
}
