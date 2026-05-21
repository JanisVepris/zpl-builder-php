<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
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

    public function testPrintQuantityEmitsAtCallSite(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->printQuantity(5)->end();

        self::assertSame('^XA^FDHello^FS^PQ5^XZ', $output);
    }

    public function testNoPrintQuantityEmittedByDefault(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->end();

        self::assertStringNotContainsString('^PQ', $output);
        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }

    public function testResetClearsFontPresets(): void
    {
        $builder = ZplBuilder::start()
            ->addFontPreset('big', Font::Zero, 80, 40)
            ->reset();

        $this->expectException(FontPresetDoesNotExistException::class);

        $builder->applyFontPreset('big');
    }

    public function testResetClearsPrintNewlinesPreference(): void
    {
        $output = (string) ZplBuilder::start()
            ->printNewlines()
            ->reset()
            ->fieldData('Hello');

        self::assertStringNotContainsString(PHP_EOL, $output);
    }

    public function testRenderDoesNotMutateState(): void
    {
        $builder = ZplBuilder::start()->fieldData('Hello');

        $first = (string) $builder;
        $builder->fieldData('World');
        $second = (string) $builder;

        self::assertSame('^XA^FDHello^FS', $first);
        self::assertSame('^XA^FDHello^FS^FDWorld^FS', $second);
    }

    public function testRenderIsIdempotent(): void
    {
        $builder = ZplBuilder::start()->fieldData('Hello');

        self::assertSame((string) $builder, (string) $builder);
    }

    public function testRenderWithoutEndOmitsEndFormat(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello');

        self::assertStringNotContainsString('^XZ', $output);
    }

    public function testEndAppendsEndFormatLikeAnyOtherCommand(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->end();

        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }
}
