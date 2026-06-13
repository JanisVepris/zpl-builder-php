<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Antenna;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SetAntennaParameters;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SetAntennaParameters::class)]
class SetAntennaParametersTest extends UnitTestCase
{
    public function testRendersDiversity(): void
    {
        $command = new SetAntennaParameters(Antenna::Diversity, Antenna::Diversity);

        self::assertSame('^WAD,D', (string) $command);
    }

    public function testRendersLeftAndRight(): void
    {
        $command = new SetAntennaParameters(Antenna::Left, Antenna::Right);

        self::assertSame('^WAL,R', (string) $command);
    }
}
