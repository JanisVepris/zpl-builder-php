<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\DetectMultipleRfidTags;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DetectMultipleRfidTags::class)]
class DetectMultipleRfidTagsTest extends UnitTestCase
{
    public function testRendersDisabled(): void
    {
        self::assertSame('^RNN', (string) new DetectMultipleRfidTags(false));
    }

    public function testRendersEnabled(): void
    {
        self::assertSame('^RNY', (string) new DetectMultipleRfidTags(true));
    }
}
