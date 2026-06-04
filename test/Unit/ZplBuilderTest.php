<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use Janisvepris\ZplBuilder\BarcodeDefaultSettings;
use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\PrintDirection;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\FieldOriginLocation;
use Janisvepris\ZplBuilder\FontSettings;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplBuilder;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode128;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeDefaults;
use Janisvepris\ZplBuilder\ZplCommand\ChangeFont;
use Janisvepris\ZplBuilder\ZplCommand\ChangeInternationalEncoding;
use Janisvepris\ZplBuilder\ZplCommand\EndFormat;
use Janisvepris\ZplBuilder\ZplCommand\FieldBlock;
use Janisvepris\ZplBuilder\ZplCommand\FieldClock;
use Janisvepris\ZplBuilder\ZplCommand\FieldComment;
use Janisvepris\ZplBuilder\ZplCommand\FieldData;
use Janisvepris\ZplBuilder\ZplCommand\FieldHexIndicator;
use Janisvepris\ZplBuilder\ZplCommand\FieldNumber;
use Janisvepris\ZplBuilder\ZplCommand\FieldOrientation;
use Janisvepris\ZplBuilder\ZplCommand\FieldOrigin;
use Janisvepris\ZplBuilder\ZplCommand\FieldParameter;
use Janisvepris\ZplBuilder\ZplCommand\FieldReversePrint;
use Janisvepris\ZplBuilder\ZplCommand\FieldSeparator;
use Janisvepris\ZplBuilder\ZplCommand\FieldTypeset;
use Janisvepris\ZplBuilder\ZplCommand\FieldVariable;
use Janisvepris\ZplBuilder\ZplCommand\FontName;
use Janisvepris\ZplBuilder\ZplCommand\GraphicBox;
use Janisvepris\ZplBuilder\ZplCommand\LabelHome;
use Janisvepris\ZplBuilder\ZplCommand\LabelLength;
use Janisvepris\ZplBuilder\ZplCommand\LabelReversePrint;
use Janisvepris\ZplBuilder\ZplCommand\MultipleFieldOrigin;
use Janisvepris\ZplBuilder\ZplCommand\PrintOrientation;
use Janisvepris\ZplBuilder\ZplCommand\PrintQuantity;
use Janisvepris\ZplBuilder\ZplCommand\PrintWidth;
use Janisvepris\ZplBuilder\ZplCommand\RawCommand;
use Janisvepris\ZplBuilder\ZplCommand\RecallFormat;
use Janisvepris\ZplBuilder\ZplCommand\StartFormat;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ZplBuilder::class)]
#[UsesClass(BarcodeCode128::class)]
#[UsesClass(BarcodeDefaults::class)]
#[UsesClass(BarcodeDefaultSettings::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(ChangeFont::class)]
#[UsesClass(ChangeInternationalEncoding::class)]
#[UsesClass(CharacterRemap::class)]
#[UsesClass(Code128Mode::class)]
#[UsesClass(DuplicateClockIndicatorException::class)]
#[UsesClass(Encoding::class)]
#[UsesClass(EndFormat::class)]
#[UsesClass(FieldBlock::class)]
#[UsesClass(FieldClock::class)]
#[UsesClass(FieldComment::class)]
#[UsesClass(FieldData::class)]
#[UsesClass(FieldDataEncoder::class)]
#[UsesClass(FieldHexIndicator::class)]
#[UsesClass(FieldNumber::class)]
#[UsesClass(FieldOrientation::class)]
#[UsesClass(FieldOrigin::class)]
#[UsesClass(FieldOriginLocation::class)]
#[UsesClass(FieldParameter::class)]
#[UsesClass(FieldReversePrint::class)]
#[UsesClass(FieldSeparator::class)]
#[UsesClass(FieldTypeset::class)]
#[UsesClass(FieldVariable::class)]
#[UsesClass(FloatValueOutOfRangeException::class)]
#[UsesClass(Font::class)]
#[UsesClass(FontExtension::class)]
#[UsesClass(FontName::class)]
#[UsesClass(FontPreset::class)]
#[UsesClass(FontPresetDoesNotExistException::class)]
#[UsesClass(FontSettings::class)]
#[UsesClass(GraphicBox::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Justify::class)]
#[UsesClass(LabelFlip::class)]
#[UsesClass(LabelHome::class)]
#[UsesClass(LabelLength::class)]
#[UsesClass(LabelReversePrint::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(MultipleFieldOrigin::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(PrintDirection::class)]
#[UsesClass(PrintOrientation::class)]
#[UsesClass(PrintQuantity::class)]
#[UsesClass(PrintWidth::class)]
#[UsesClass(RawCommand::class)]
#[UsesClass(RecallFormat::class)]
#[UsesClass(StartFormat::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(TertiaryClockIndicatorWithoutSecondaryException::class)]
#[UsesClass(ValueAssert::class)]
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

    public function testComposesRealisticLabel(): void
    {
        $output = (string) ZplBuilder::start()
            ->labelHome(30, 30)
            ->changeFont(Font::Zero, 40, 20)
            ->fieldOrigin(50, 50)
            ->fieldData('Hello, ZPL!')
            ->fieldOrigin(50, 120)
            ->barcodeDefaults(3, 3.0, 100)
            ->barcodeCode128('ABC123')
            ->printQuantity(1)
            ->end();

        self::assertSame(
            '^XA^LH30,30^CF0,40,20^FO50,50^FDHello, ZPL!^FS^FO50,120^BY3,3.0,100^BCN,100,Y,N,N,N^FDABC123^FS^PQ1^XZ',
            $output,
        );
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

    public function testFieldClockEmitsFcWithAllIndicators(): void
    {
        $output = (string) ZplBuilder::start()->fieldClock('%', '{', '#');

        self::assertSame('^XA^FC%,{,#', $output);
    }

    public function testFieldClockEmitsFcWithDefaultPrimaryIndicator(): void
    {
        $output = (string) ZplBuilder::start()->fieldClock();

        self::assertSame('^XA^FC%', $output);
    }

    public function testFieldClockEmitsFcWithSecondaryIndicator(): void
    {
        $output = (string) ZplBuilder::start()->fieldClock('%', '{');

        self::assertSame('^XA^FC%,{', $output);
    }

    public function testFieldClockValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->fieldClock('%', '%');
            self::fail('Expected DuplicateClockIndicatorException on duplicate secondary indicator.');
        } catch (DuplicateClockIndicatorException) {
        }

        self::assertSame('^XA', (string) $builder);
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

    public function testFieldOriginsEmitsFm(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrigins(
            FieldOriginLocation::at(100, 200),
            FieldOriginLocation::at(100, 600),
        );

        self::assertSame('^XA^FM100,200,100,600', $output);
    }

    public function testFieldOriginsNoLocationsIsNoop(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrigins();

        self::assertSame('^XA', $output);
    }

    public function testFieldOriginsTooManyValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $locations = array_fill(0, 61, FieldOriginLocation::at(0, 0));

        try {
            $builder->fieldOrigins(...$locations);
            self::fail('Expected IntegerValueOutOfRangeException on too many ^FM locations.');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testFieldOriginsWithExcludedLocation(): void
    {
        $output = (string) ZplBuilder::start()->fieldOrigins(
            FieldOriginLocation::at(100, 200),
            FieldOriginLocation::excluded(),
            FieldOriginLocation::at(100, 600),
        );

        self::assertSame('^XA^FM100,200,e,e,100,600', $output);
    }

    public function testFieldParameterDefaultsToHorizontalWithNoGap(): void
    {
        $output = (string) ZplBuilder::start()->fieldParameter();

        self::assertSame('^XA^FPH,0', $output);
    }

    public function testFieldParameterEmitsFp(): void
    {
        $output = (string) ZplBuilder::start()->fieldParameter(PrintDirection::Vertical, 5);

        self::assertSame('^XA^FPV,5', $output);
    }

    public function testFieldReversePrintEmitsFr(): void
    {
        $output = (string) ZplBuilder::start()->fieldReversePrint();

        self::assertSame('^XA^FR', $output);
    }

    public function testFieldTypesetDefaultsToZeroZero(): void
    {
        $output = (string) ZplBuilder::start()->fieldTypeset();

        self::assertSame('^XA^FT0,0', $output);
    }

    public function testFieldTypesetEmitsFt(): void
    {
        $output = (string) ZplBuilder::start()->fieldTypeset(50, 100);

        self::assertSame('^XA^FT50,100', $output);
    }

    public function testFieldVariableAutoEscapesCaret(): void
    {
        $output = (string) ZplBuilder::start()->fieldVariable('A^B');

        self::assertSame('^XA^FH_^FVA_5EB^FS', $output);
    }

    public function testFieldVariableEmitsFvThenFieldSeparator(): void
    {
        $output = (string) ZplBuilder::start()->fieldVariable('Hello');

        self::assertSame('^XA^FVHello^FS', $output);
    }

    public function testFieldVariablePassesCleanInputThrough(): void
    {
        $output = (string) ZplBuilder::start()->fieldVariable('Hello World');

        self::assertStringContainsString('^FVHello World^FS', $output);
        self::assertStringNotContainsString('^FH', $output);
    }

    public function testFieldVariableReusesPendingHexIndicator(): void
    {
        $output = (string) ZplBuilder::start()
            ->fieldHexIndicator('%')
            ->fieldVariable('foo^bar');

        // The explicit ^FH% is preserved and reused for escape encoding — no duplicate ^FH_,
        // and the data is escaped with % rather than the default _.
        self::assertSame('^XA^FH%^FVfoo%5Ebar^FS', $output);
    }

    public function testFontChainsWithFieldDataWithoutEmittingExtraSeparator(): void
    {
        // ^A is a field modifier: it selects the font but does not itself open or close a field.
        // The ^FS comes only from the following fieldData() call.
        $output = (string) ZplBuilder::start()
            ->font(Font::Zero, height: 50, width: 50)
            ->fieldData('Hello');

        self::assertSame('^XA^A0N,50,50^FDHello^FS', $output);
    }

    public function testFontEmitsAWithAllArguments(): void
    {
        $output = (string) ZplBuilder::start()->font(Font::A, Orientation::Rotate90, 40, 20);

        self::assertSame('^XA^AAR,40,20', $output);
    }

    public function testFontUsesDefaultOrientationAndDimensions(): void
    {
        // No orientation/height/width passed — defaults to normal orientation and the 10-dot minimum.
        $output = (string) ZplBuilder::start()->font(Font::Zero);

        self::assertSame('^XA^A0N,10,10', $output);
    }

    public function testFontValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->font(Font::Zero, height: 9);
            self::fail('Expected IntegerValueOutOfRangeException on invalid height.');
        } catch (IntegerValueOutOfRangeException) {
        }

        // The failed call must not append a partial ^A command.
        self::assertSame('^XA', (string) $builder);
    }

    public function testFontByNameEmitsAAtWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->fontByName('CYRI_UB', 50, 50);

        // Defaults: extension .FNT, device R: (RAM), orientation N. No trailing ^FS — it is a selector.
        self::assertSame('^XA^A@N,50,50,R:CYRI_UB.FNT', $output);
    }

    public function testFontByNameEmitsAAtWithExplicitArguments(): void
    {
        $output = (string) ZplBuilder::start()->fontByName(
            name: 'ARI000',
            height: 70,
            width: 40,
            extension: FontExtension::TrueType,
            device: StorageDevice::Flash,
            orientation: Orientation::Rotate90,
        );

        self::assertSame('^XA^A@R,70,40,E:ARI000.TTF', $output);
    }

    public function testFontByNameValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->fontByName('', 50, 50);
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
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

        self::assertSame('^XA' . PHP_EOL . '^FDHi' . PHP_EOL . '^FS' . PHP_EOL . '^XZ' . PHP_EOL, $output);
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

    public function testRawIsNoOpForEmptyInput(): void
    {
        $builder = ZplBuilder::start()->raw('');

        self::assertSame('^XA', (string) $builder);
        // Empty input must not bump the command list either.
        self::assertCount(1, $builder->getCommands());
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

    public function testRemoveFontPresetThrowsOnUnknownName(): void
    {
        $this->expectException(FontPresetDoesNotExistException::class);

        ZplBuilder::start()->removeFontPreset('does-not-exist');
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

    public function testRenderReturnsEmptyStringWhenNoCommands(): void
    {
        // The protected constructor with no commands is reachable only via a subclass —
        // `start()`/`reset()` always append `^XA`, so this guard is otherwise dead code.
        $builder = new class extends ZplBuilder {
            public function __construct()
            {
                parent::__construct();
            }
        };

        self::assertSame('', $builder->render());
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
