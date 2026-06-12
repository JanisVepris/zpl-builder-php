<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MediaTrackingType;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\MediaTracking;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MediaTracking::class)]
class MediaTrackingTest extends UnitTestCase
{
    public function testRendersContinuous(): void
    {
        self::assertSame('^MNN', (string) new MediaTracking(MediaTrackingType::Continuous));
    }

    public function testRendersMarkSensing(): void
    {
        self::assertSame('^MNM', (string) new MediaTracking(MediaTrackingType::NonContinuousMark));
    }

    public function testRendersWebSensingAlternate(): void
    {
        self::assertSame('^MNW', (string) new MediaTracking(MediaTrackingType::NonContinuousWebAlternate));
    }
}
