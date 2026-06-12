<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\WiredPrintServerCheck;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\SearchWiredPrintServer;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SearchWiredPrintServer::class)]
class SearchWiredPrintServerTest extends UnitTestCase
{
    public function testRendersCheck(): void
    {
        self::assertSame('^NBC', (string) new SearchWiredPrintServer(WiredPrintServerCheck::Check));
    }

    public function testRendersSkip(): void
    {
        self::assertSame('^NBS', (string) new SearchWiredPrintServer(WiredPrintServerCheck::Skip));
    }
}
