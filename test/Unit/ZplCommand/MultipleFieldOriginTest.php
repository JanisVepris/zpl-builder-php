<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\FieldOriginLocation;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\MultipleFieldOrigin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(MultipleFieldOrigin::class)]
#[UsesClass(FieldOriginLocation::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class MultipleFieldOriginTest extends UnitTestCase
{
    public function testNoLocationsThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new MultipleFieldOrigin();
    }

    public function testRendersExcludedSlotBetweenCoordinates(): void
    {
        $command = new MultipleFieldOrigin(
            FieldOriginLocation::at(100, 200),
            FieldOriginLocation::excluded(),
            FieldOriginLocation::at(100, 600),
        );

        self::assertSame('^FM100,200,e,e,100,600', (string) $command);
    }

    public function testRendersMultiplePairs(): void
    {
        $command = new MultipleFieldOrigin(
            FieldOriginLocation::at(50, 60),
            FieldOriginLocation::at(150, 160),
            FieldOriginLocation::at(250, 260),
        );

        self::assertSame('^FM50,60,150,160,250,260', (string) $command);
    }

    public function testRendersSinglePair(): void
    {
        self::assertSame('^FM100,200', (string) new MultipleFieldOrigin(FieldOriginLocation::at(100, 200)));
    }

    public function testTooManyLocationsThrows(): void
    {
        $locations = array_fill(0, 61, FieldOriginLocation::at(0, 0));

        $this->expectException(IntegerValueOutOfRangeException::class);

        new MultipleFieldOrigin(...$locations);
    }

    public function testUpperBoundLocationCountAccepted(): void
    {
        $locations = array_fill(0, 60, FieldOriginLocation::at(0, 0));

        $command = new MultipleFieldOrigin(...$locations);

        // 60 pairs × "0,0" joined by "," + the ^FM prefix
        self::assertSame('^FM' . implode(',', array_fill(0, 60, '0,0')), (string) $command);
    }
}
