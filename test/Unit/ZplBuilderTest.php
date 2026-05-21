<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\Exception\CommandAfterEndException;
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

    public function testRawAppendsLiteralZpl(): void
    {
        $output = (string) ZplBuilder::start()->raw('^MMT');

        self::assertStringContainsString('^MMT', $output);
    }

    public function testRawPreservesArbitraryFragment(): void
    {
        $output = (string) ZplBuilder::start()->raw('^FO5,5^GB100,100,2^FS');

        self::assertStringContainsString('^FO5,5^GB100,100,2^FS', $output);
    }

    public function testRawAfterEndThrows(): void
    {
        $builder = ZplBuilder::start()->end();

        $this->expectException(CommandAfterEndException::class);

        $builder->raw('^MMT');
    }

    public function testPrintQuantityEmitsAtCallSite(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->printQuantity(5);

        self::assertSame('^XA^FDHello^FS^PQ5^XZ', $output);
    }

    public function testNoPrintQuantityEmittedByDefault(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello');

        self::assertStringNotContainsString('^PQ', $output);
        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }
}
