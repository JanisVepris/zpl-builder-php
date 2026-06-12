<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ApplicatorReprint;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ApplicatorReprint::class)]
class ApplicatorReprintTest extends UnitTestCase
{
    public function testRendersApplicatorReprint(): void
    {
        self::assertSame('~PR', (string) new ApplicatorReprint());
    }
}
