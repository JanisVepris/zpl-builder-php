<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\FieldOrientation;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FieldOrientation::class)]
class FieldOrientationTest extends UnitTestCase
{
    public function testRendersNormalOrientation(): void
    {
        self::assertSame('^FWN', (string) new FieldOrientation(Orientation::ROTATE_0));
    }

    public function testRendersRotated90(): void
    {
        self::assertSame('^FWR', (string) new FieldOrientation(Orientation::ROTATE_90));
    }

    public function testRendersRotated180(): void
    {
        self::assertSame('^FWI', (string) new FieldOrientation(Orientation::ROTATE_180));
    }

    public function testRendersRotated270(): void
    {
        self::assertSame('^FWB', (string) new FieldOrientation(Orientation::ROTATE_270));
    }
}
