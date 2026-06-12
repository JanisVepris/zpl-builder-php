<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\MediaFeedAction;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\MediaFeed;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MediaFeed::class)]
class MediaFeedTest extends UnitTestCase
{
    public function testRendersCalibrateAndLengthDetect(): void
    {
        $command = new MediaFeed(MediaFeedAction::Calibrate, MediaFeedAction::LengthDetect);

        self::assertSame('^MFC,L', (string) $command);
    }

    public function testRendersFeedAndNoFeed(): void
    {
        $command = new MediaFeed(MediaFeedAction::Feed, MediaFeedAction::None);

        self::assertSame('^MFF,N', (string) $command);
    }
}
