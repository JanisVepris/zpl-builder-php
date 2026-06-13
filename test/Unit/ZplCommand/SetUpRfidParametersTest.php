<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\PrintSpeed;
use Janisvepris\ZplBuilder\Enum\RfidErrorHandling;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\SetUpRfidParameters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SetUpRfidParameters::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(ValueAssert::class)]
class SetUpRfidParametersTest extends UnitTestCase
{
    public function testNumberOfLabelsAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUpRfidParameters(null, null, null, 11, null, null, null);
    }

    public function testRendersPositionLabelsAndErrorHandling(): void
    {
        $command = new SetUpRfidParameters(null, 800, null, 2, RfidErrorHandling::PauseMode, null, null);

        self::assertSame('^RS,800,,2,P', (string) $command);
    }

    public function testRendersTagTypeOnly(): void
    {
        $command = new SetUpRfidParameters(4, null, null, null, null, null, null);

        self::assertSame('^RS4', (string) $command);
    }

    public function testRendersVoidPrintSpeedAfterInteriorGap(): void
    {
        $command = new SetUpRfidParameters(8, null, null, null, null, null, PrintSpeed::Ips4);

        self::assertSame('^RS8,,,,,,,4', (string) $command);
    }

    public function testTagTypeAboveMaxThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new SetUpRfidParameters(10, null, null, null, null, null, null);
    }
}
