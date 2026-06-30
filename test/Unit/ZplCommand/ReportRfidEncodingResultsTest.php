<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\RfidReportMode;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\ReportRfidEncodingResults;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ReportRfidEncodingResults::class)]
#[UsesClass(RfidReportMode::class)]
class ReportRfidEncodingResultsTest extends UnitTestCase
{
    public function testRendersDisable(): void
    {
        self::assertSame('~RVD', (string) new ReportRfidEncodingResults(RfidReportMode::Disable));
    }

    public function testRendersEnable(): void
    {
        self::assertSame('~RVE', (string) new ReportRfidEncodingResults(RfidReportMode::Enable));
    }
}
