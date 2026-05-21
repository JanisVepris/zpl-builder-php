<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\PrintOrientation;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PrintOrientation::class)]
class PrintOrientationTest extends UnitTestCase
{
    public function testRendersInverted(): void
    {
        self::assertSame('^POI', (string) new PrintOrientation(LabelFlip::Inverted));
    }

    public function testRendersNormal(): void
    {
        self::assertSame('^PON', (string) new PrintOrientation(LabelFlip::Normal));
    }
}
