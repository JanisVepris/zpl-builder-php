<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetMediaSensors;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetMediaSensors::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetMediaSensorsTest extends UnitTestCase
{
    public function testLabelLengthAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetMediaSensors(50, 40, 30, 32001, null, null, null, null, null);
    }

    public function testLabelLengthBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetMediaSensors(50, 40, 30, 0, null, null, null, null, null);
    }

    public function testOptionalParameterAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetMediaSensors(50, 40, 30, 1225, null, null, 101, null, null);
    }

    public function testRendersAllParameters(): void
    {
        $command = new SetMediaSensors(50, 40, 30, 1225, 60, 70, 80, 90, 100);

        self::assertSame('^SS050,040,030,1225,060,070,080,090,100', (string) $command);
    }

    public function testRendersInteriorUnsetParametersAsEmpty(): void
    {
        $command = new SetMediaSensors(50, 40, 30, 1225, null, null, 80, null, null);

        self::assertSame('^SS050,040,030,1225,,,080', (string) $command);
    }

    public function testRendersRequiredParameters(): void
    {
        $command = new SetMediaSensors(50, 40, 30, 1225, null, null, null, null, null);

        self::assertSame('^SS050,040,030,1225', (string) $command);
    }

    public function testWebAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetMediaSensors(101, 40, 30, 1225, null, null, null, null, null);
    }

    public function testWebBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetMediaSensors(-1, 40, 30, 1225, null, null, null, null, null);
    }
}
