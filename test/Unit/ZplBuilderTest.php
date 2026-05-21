<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ZplBuilder::class)]
class ZplBuilderTest extends UnitTestCase
{
    public function testFieldDataPassesCleanInputThrough(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello World');

        self::assertStringContainsString('^FDHello World^FS', $output);
        self::assertStringNotContainsString('^FH', $output);
    }

    public function testFieldDataAutoEscapesCaret(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('A^B');

        self::assertStringContainsString('^FH_^FDA_5EB^FS', $output);
    }

    public function testFieldDataAutoEscapesTilde(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('A~B');

        self::assertStringContainsString('^FH_^FDA_7EB^FS', $output);
    }

    public function testFieldDataEscapesUnderscoreWhenHexIndicatorIsEmitted(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('id_42^X');

        self::assertStringContainsString('^FH_^FDid_5F42_5EX^FS', $output);
    }

    public function testFieldDataDoesNotEmitHexIndicatorWhenInputHasOnlyUnderscore(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('id_42');

        self::assertStringContainsString('^FDid_42^FS', $output);
        self::assertStringNotContainsString('^FH', $output);
    }
}
