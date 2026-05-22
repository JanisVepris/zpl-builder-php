<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit\ZplCommand;

use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplCommand\RawCommand;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RawCommand::class)]
class RawCommandTest extends UnitTestCase
{
    public function testRejectsEmptyString(): void
    {
        $this->expectException(StringLengthOutOfRangeException::class);

        new RawCommand('');
    }

    public function testRendersMultipleCommandsVerbatim(): void
    {
        self::assertSame(
            '^FO10,10^FDTest^FS',
            (string) new RawCommand('^FO10,10^FDTest^FS'),
        );
    }

    public function testRendersSingleCommand(): void
    {
        self::assertSame('^MMT', (string) new RawCommand('^MMT'));
    }
}
