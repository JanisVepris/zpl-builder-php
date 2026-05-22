<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\ZplBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ZplBuilder::class)]
class ZplBuilderTest extends UnitTestCase
{
    public function testAddFontPresetInheritsDimensionsFromFontWhenOmitted(): void
    {
        $builder = ZplBuilder::start()
            ->changeFont(Font::A, 30, 15)
            ->addFontPreset('big', Font::A);

        $preset = $builder->getFontPresets()['big'];

        self::assertSame(30, $preset->height);
        self::assertSame(15, $preset->width);
    }

    public function testApplyFontPresetEmitsChangeFontWithStoredDimensions(): void
    {
        $output = (string) ZplBuilder::start()
            ->addFontPreset('big', Font::Zero, 80, 40)
            ->applyFontPreset('big');

        self::assertSame('^XA^CF0,80,40', $output);
    }

    public function testApplyFontPresetThrowsOnUnknownName(): void
    {
        $this->expectException(FontPresetDoesNotExistException::class);

        ZplBuilder::start()->applyFontPreset('does-not-exist');
    }

    public function testBarcodeCode128EmitsBcThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode128('ABC', height: 75);

        self::assertSame('^XA^BCN,75,Y,N,N,N^FDABC^FS', $output);
    }

    public function testBarcodeCode128InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode128('ABC');

        self::assertStringContainsString('^BCN,50,', $output);
    }

    public function testBarcodeCode128OverridesBarcodeDefaultsHeight(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode128('ABC', height: 120);

        self::assertStringContainsString('^BCN,120,', $output);
    }

    public function testBarcodeDefaultsEmitsBy(): void
    {
        $output = (string) ZplBuilder::start()->barcodeDefaults(3, 2.5, 75);

        self::assertSame('^XA^BY3,2.5,75', $output);
    }

    public function testBarcodeDefaultsNoArgsEmitsAlignedDefaults(): void
    {
        $output = (string) ZplBuilder::start()->barcodeDefaults();

        // The fluent method's defaults must match BarcodeDefaultSettings'
        // constructor defaults so the cached state and the emitted ^BY agree.
        self::assertSame('^XA^BY2,3.0,10', $output);
    }

    public function testBarcodeDefaultsValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->barcodeDefaults(5, 2.5, 0);
            self::fail('Expected IntegerValueOutOfRangeException on invalid height.');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testBarcodeDefaultsValidationFailureLeavesNoCommandAppendedForRatio(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->barcodeDefaults(5, 3.5);
            self::fail('Expected FloatValueOutOfRangeException on invalid ratio.');
        } catch (FloatValueOutOfRangeException) {
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testChangeFontEmitsCfWithLetterFont(): void
    {
        $output = (string) ZplBuilder::start()->changeFont(Font::A, 30, 15);

        self::assertSame('^XA^CFA,30,15', $output);
    }

    public function testChangeFontEmitsCfWithNumericFontUsingDefaultWidth(): void
    {
        $output = (string) ZplBuilder::start()->changeFont(Font::Zero, 50);

        // No width passed — inherits the FontSettings default of 5.
        self::assertSame('^XA^CF0,50,5', $output);
    }

    public function testChangeFontRemembersHeightWhenOnlyWidthChanges(): void
    {
        $output = (string) ZplBuilder::start()
            ->changeFont(Font::A, 30, 15)
            ->changeFont(Font::A, width: 20);

        self::assertSame('^XA^CFA,30,15^CFA,30,20', $output);
    }

    public function testChangeFontRemembersWidthWhenOnlyHeightChanges(): void
    {
        $output = (string) ZplBuilder::start()
            ->changeFont(Font::A, 30, 15)
            ->changeFont(Font::A, 50);

        self::assertSame('^XA^CFA,30,15^CFA,50,15', $output);
    }

    public function testChangeFontWidthFailureDoesNotLeakHeightIntoNextCall(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->changeFont(Font::A, 30, -1);
            self::fail('Expected IntegerValueOutOfRangeException on invalid width.');
        } catch (IntegerValueOutOfRangeException) {
        }

        // The failed call must not leak height=30 into the cached FontSettings.
        // A subsequent no-arg changeFont() should still use the defaults (9, 5).
        $builder->changeFont(Font::A);

        self::assertSame('^XA^CFA,9,5', (string) $builder);
    }

    public function testChangeInternationalEncodingEmitsCi(): void
    {
        $output = (string) ZplBuilder::start()->changeInternationalEncoding(Encoding::Utf8);

        self::assertSame('^XA^CI28', $output);
    }

    public function testChangeInternationalEncodingEmitsRemaps(): void
    {
        $output = (string) ZplBuilder::start()->changeInternationalEncoding(
            Encoding::Utf8,
            new CharacterRemap(65, 66),
        );

        self::assertSame('^XA^CI28,65,66', $output);
    }

    public function testCommentEmitsFx(): void
    {
        $output = (string) ZplBuilder::start()->comment(' section header');

        self::assertSame('^XA^FX section header', $output);
    }

    public function testEndAppendsEndFormatEveryTimeItsCalled(): void
    {
        $output = (string) ZplBuilder::start()->end()->end();

        self::assertSame('^XA^XZ^XZ', $output);
    }

    public function testEndAppendsEndFormatLikeAnyOtherCommand(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->end();

        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }

    public function testFieldBlockEmitsFb(): void
    {
        $output = (string) ZplBuilder::start()->fieldBlock(400, 3, 5, Justify::Center, 10);

        self::assertSame('^XA^FB400,3,5,C,10', $output);
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

    public function testFieldDataClearsPendingIndicatorAfterUse(): void
    {
        $output = (string) ZplBuilder::start()
            ->fieldHexIndicator('%')
            ->fieldData('clean')
            ->fieldData('foo^bar');

        // After the first fieldData ends the field with ^FS, the pending indicator
        // is cleared. The next dirty fieldData falls back to the default ^FH_.
        self::assertSame('^XA^FH%^FDclean^FS^FH_^FDfoo_5Ebar^FS', $output);
    }

    public function testFieldDataDoesNotEmitHexIndicatorWhenInputHasOnlyUnderscore(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('id_42');

        self::assertStringContainsString('^FDid_42^FS', $output);
        self::assertStringNotContainsString('^FH', $output);
    }

    public function testFieldDataEscapesUnderscoreWhenHexIndicatorIsEmitted(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('id_42^X');

        self::assertStringContainsString('^FH_^FDid_5F42_5EX^FS', $output);
    }

    public function testFieldDataPassesCleanInputThrough(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello World');

        self::assertStringContainsString('^FDHello World^FS', $output);
        self::assertStringNotContainsString('^FH', $output);
    }

    public function testFieldDataReusesPendingHexIndicatorAcrossIntermediateCommands(): void
    {
        $output = (string) ZplBuilder::start()
            ->fieldHexIndicator('%')
            ->fieldOrigin(50, 50)
            ->fieldData('foo^bar');

        // ^FH applies until the next ^FS per the ZPL spec, so commands between
        // fieldHexIndicator and fieldData don't reset the pending indicator.
        self::assertSame('^XA^FH%^FO50,50^FDfoo%5Ebar^FS', $output);
    }

    public function testFieldDataReusesPendingHexIndicatorInsteadOfEmittingDuplicate(): void
    {
        $output = (string) ZplBuilder::start()
            ->fieldHexIndicator('%')
            ->fieldData('foo^bar');

        // The user's explicit ^FH% must be preserved and used for escape encoding
        // (no duplicate ^FH_ appended, and the data is escaped with % not _).
        self::assertSame('^XA^FH%^FDfoo%5Ebar^FS', $output);
    }

    public function testFieldHexIndicatorEmitsFhWithCustomIndicator(): void
    {
        $output = (string) ZplBuilder::start()->fieldHexIndicator('%');

        self::assertSame('^XA^FH%', $output);
    }

    public function testFieldHexIndicatorEmitsFhWithDefaultIndicator(): void
    {
        $output = (string) ZplBuilder::start()->fieldHexIndicator();

        self::assertSame('^XA^FH_', $output);
    }

    public function testFieldNumberEmitsFn(): void
    {
        $output = (string) ZplBuilder::start()->fieldNumber(7);

        self::assertSame('^XA^FN7', $output);
    }

    public function testFieldOrientationEmitsFw(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrientation(Orientation::Rotate90);

        self::assertSame('^XA^FWR', $output);
    }

    public function testFieldOriginDefaultsToZeroZero(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrigin();

        self::assertSame('^XA^FO0,0', $output);
    }

    public function testFieldOriginEmitsFo(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrigin(50, 100);

        self::assertSame('^XA^FO50,100', $output);
    }

    public function testGetCommandsReturnsAppendedCommands(): void
    {
        $commands = ZplBuilder::start()
            ->fieldData('Hello')
            ->end()
            ->getCommands();

        // ^XA, ^FD, ^FS, ^XZ
        self::assertCount(4, $commands);
    }

    public function testGetFontPresetsExposesRegistry(): void
    {
        $builder = ZplBuilder::start()
            ->addFontPreset('big', Font::Zero, 80, 40)
            ->addFontPreset('small', Font::A, 20, 10);

        $presets = $builder->getFontPresets();

        self::assertCount(2, $presets);
        self::assertArrayHasKey('big', $presets);
        self::assertArrayHasKey('small', $presets);
    }

    public function testGraphicBoxEmitsGbAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->graphicBox(100, 50, 2);

        self::assertSame('^XA^GB100,50,2,B,0^FS', $output);
    }

    public function testHasFontPresetReturnsFalseForUnknown(): void
    {
        $builder = ZplBuilder::start();

        self::assertFalse($builder->hasFontPreset('big'));
    }

    public function testHasFontPresetReturnsTrueAfterRegistration(): void
    {
        $builder = ZplBuilder::start()->addFontPreset('big', Font::Zero, 80, 40);

        self::assertTrue($builder->hasFontPreset('big'));
    }

    public function testLabelHomeEmitsLh(): void
    {
        $output = (string) ZplBuilder::start()->labelHome(20, 30);

        self::assertSame('^XA^LH20,30', $output);
    }

    public function testLabelLengthEmitsLl(): void
    {
        $output = (string) ZplBuilder::start()->labelLength(1200);

        self::assertSame('^XA^LL1200', $output);
    }

    public function testLabelReversePrintEmitsLrNo(): void
    {
        $output = (string) ZplBuilder::start()->labelReversePrint(false);

        self::assertSame('^XA^LRN', $output);
    }

    public function testLabelReversePrintEmitsLrYes(): void
    {
        $output = (string) ZplBuilder::start()->labelReversePrint();

        self::assertSame('^XA^LRY', $output);
    }

    public function testNoPrintQuantityEmittedByDefault(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->end();

        self::assertStringNotContainsString('^PQ', $output);
        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }

    public function testPrintNewlinesSeparatesCommandsWithEol(): void
    {
        $output = (string) ZplBuilder::start()->printNewlines()->fieldData('Hi')->end();

        self::assertSame('^XA'.PHP_EOL.'^FDHi'.PHP_EOL.'^FS'.PHP_EOL.'^XZ'.PHP_EOL, $output);
    }

    public function testPrintOrientationEmitsPo(): void
    {
        $output = (string) ZplBuilder::start()->printOrientation(LabelFlip::Inverted);

        self::assertSame('^XA^POI', $output);
    }

    public function testPrintQuantityEmitsAtCallSite(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->printQuantity(5)->end();

        self::assertSame('^XA^FDHello^FS^PQ5^XZ', $output);
    }

    public function testPrintWidthEmitsPw(): void
    {
        $output = (string) ZplBuilder::start()->printWidth(800);

        self::assertSame('^XA^PW800', $output);
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

    public function testRecallFormatEmitsXf(): void
    {
        $output = (string) ZplBuilder::start()->recallFormat('LABEL', StorageDevice::Flash);

        self::assertSame('^XA^XFE:LABEL.ZPL', $output);
    }

    public function testRemoveFontPresetDropsRegistration(): void
    {
        $builder = ZplBuilder::start()
            ->addFontPreset('big', Font::Zero, 80, 40)
            ->removeFontPreset('big');

        self::assertFalse($builder->hasFontPreset('big'));
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

    public function testResetReEmitsStartFormat(): void
    {
        $commands = ZplBuilder::start()
            ->fieldData('A')
            ->reset()
            ->getCommands();

        self::assertCount(1, $commands);
        self::assertSame('^XA', (string) $commands[0]);
    }

    public function testStartEmitsStartFormat(): void
    {
        self::assertSame('^XA', (string) ZplBuilder::start());
    }
}
