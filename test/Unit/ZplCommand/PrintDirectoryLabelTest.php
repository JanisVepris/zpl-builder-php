<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Enum\DirectoryDevice;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand\PrintDirectoryLabel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PrintDirectoryLabel::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(ValueAssert::class)]
class PrintDirectoryLabelTest extends UnitTestCase
{
    public function testNameWithBannedValueThrows(): void
    {
        $this->expectException(StringValueContainsBannedValuesException::class);

        new PrintDirectoryLabel(DirectoryDevice::Ram, 'LOGO^', '*');
    }

    public function testRendersAllObjectsOnRam(): void
    {
        $command = new PrintDirectoryLabel(DirectoryDevice::Ram, '*', '*');

        self::assertSame('^WDR:*.*', (string) $command);
    }

    public function testRendersResidentBarCodes(): void
    {
        $command = new PrintDirectoryLabel(DirectoryDevice::Resident, '*', 'BAR');

        self::assertSame('^WDZ:*.BAR', (string) $command);
    }
}
