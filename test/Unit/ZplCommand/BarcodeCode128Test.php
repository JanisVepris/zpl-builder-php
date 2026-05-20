<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode128;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BarcodeCode128::class)]
class BarcodeCode128Test extends UnitTestCase
{
    public function testRendersWithDefaults(): void
    {
        $command = new BarcodeCode128(
            orientation: Orientation::ROTATE_0,
            height: 75,
            printInterpretation: false,
            interpretationAboveCode: false,
            useUccCheckDigit: false,
            mode: Code128Mode::No_mode,
        );

        self::assertSame('^BCN,75,N,N,N,N', (string) $command);
    }

    public function testRendersWithAllFlagsEnabled(): void
    {
        $command = new BarcodeCode128(
            orientation: Orientation::ROTATE_90,
            height: 100,
            printInterpretation: true,
            interpretationAboveCode: true,
            useUccCheckDigit: true,
            mode: Code128Mode::AUTO,
        );

        self::assertSame('^BCR,100,Y,Y,Y,A', (string) $command);
    }

    public function testHeightBelowMinThrows(): void
    {
        $this->expectException(IntegerValueOutOfRangeException::class);

        new BarcodeCode128(
            orientation: Orientation::ROTATE_0,
            height: 0,
            printInterpretation: false,
            interpretationAboveCode: false,
            useUccCheckDigit: false,
            mode: Code128Mode::No_mode,
        );
    }
}
