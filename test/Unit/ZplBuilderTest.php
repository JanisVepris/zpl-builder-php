<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\Test\Unit;

use DateTimeImmutable;
use Janisvepris\ZplBuilder\BarcodeDefaultSettings;
use Janisvepris\ZplBuilder\CharacterRemap;
use Janisvepris\ZplBuilder\Enum\CacheType;
use Janisvepris\ZplBuilder\Enum\ClockLanguage;
use Janisvepris\ZplBuilder\Enum\ClockMode;
use Janisvepris\ZplBuilder\Enum\ClockSet;
use Janisvepris\ZplBuilder\Enum\ClockTimeFormat;
use Janisvepris\ZplBuilder\Enum\CodabarCharacter;
use Janisvepris\ZplBuilder\Enum\CodablockMode;
use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Code49InterpretationLine;
use Janisvepris\ZplBuilder\Enum\Code49Mode;
use Janisvepris\ZplBuilder\Enum\DataMatrixQuality;
use Janisvepris\ZplBuilder\Enum\DateTimeFormat;
use Janisvepris\ZplBuilder\Enum\DiagonalOrientation;
use Janisvepris\ZplBuilder\Enum\DownloadExtension;
use Janisvepris\ZplBuilder\Enum\DownloadFormat;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\GraphicFieldCompression;
use Janisvepris\ZplBuilder\Enum\IpResolution;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Enum\MaxiCodeMode;
use Janisvepris\ZplBuilder\Enum\MeasurementUnit;
use Janisvepris\ZplBuilder\Enum\MediaFeedAction;
use Janisvepris\ZplBuilder\Enum\MediaTrackingType;
use Janisvepris\ZplBuilder\Enum\MemoryLetter;
use Janisvepris\ZplBuilder\Enum\MsiCheckDigit;
use Janisvepris\ZplBuilder\Enum\NetworkDevice;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\PostPrintAction;
use Janisvepris\ZplBuilder\Enum\PrintDirection;
use Janisvepris\ZplBuilder\Enum\PrintMethod;
use Janisvepris\ZplBuilder\Enum\PrintSpeed;
use Janisvepris\ZplBuilder\Enum\ProtectedMode;
use Janisvepris\ZplBuilder\Enum\QrErrorCorrection;
use Janisvepris\ZplBuilder\Enum\QrModel;
use Janisvepris\ZplBuilder\Enum\RfidOperation;
use Janisvepris\ZplBuilder\Enum\RssSymbologyType;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Enum\WiredPrintServerCheck;
use Janisvepris\ZplBuilder\Enum\ZplMode;
use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Exception\UnsupportedFontExtensionException;
use Janisvepris\ZplBuilder\FieldOriginLocation;
use Janisvepris\ZplBuilder\FontSettings;
use Janisvepris\ZplBuilder\Test\UnitTestCase;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ValueObject\AztecErrorControl;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplBuilder;
use Janisvepris\ZplBuilder\ZplBuilderInterface;
use Janisvepris\ZplBuilder\ZplCommand\AbortDownloadGraphic;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeAztec;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCodabar;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCodablock;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode11;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode128;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode39;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode49;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeCode93;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeDataMatrix;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeDefaults;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeEan13;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeEan8;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeIndustrial2of5;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeInterleaved2of5;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeLogmars;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMaxiCode;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMicroPdf417;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeMsi;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePdf417;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePlanetCode;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePlessey;
use Janisvepris\ZplBuilder\ZplCommand\BarcodePostnet;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeQrCode;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeRss;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeStandard2of5;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeTlc39;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeUpcA;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeUpcE;
use Janisvepris\ZplBuilder\ZplCommand\BarcodeUpcEanExtensions;
use Janisvepris\ZplBuilder\ZplCommand\ChangeFont;
use Janisvepris\ZplBuilder\ZplCommand\ChangeInternationalEncoding;
use Janisvepris\ZplBuilder\ZplCommand\DownloadGraphics;
use Janisvepris\ZplBuilder\ZplCommand\DownloadObject;
use Janisvepris\ZplBuilder\ZplCommand\EndFormat;
use Janisvepris\ZplBuilder\ZplCommand\EraseDownloadGraphics;
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
use Janisvepris\ZplBuilder\ZplCommand\FontIdentifier;
use Janisvepris\ZplBuilder\ZplCommand\FontName;
use Janisvepris\ZplBuilder\ZplCommand\GraphicBox;
use Janisvepris\ZplBuilder\ZplCommand\GraphicCircle;
use Janisvepris\ZplBuilder\ZplCommand\GraphicDiagonalLine;
use Janisvepris\ZplBuilder\ZplCommand\GraphicEllipse;
use Janisvepris\ZplBuilder\ZplCommand\GraphicField;
use Janisvepris\ZplBuilder\ZplCommand\GraphicSymbol;
use Janisvepris\ZplBuilder\ZplCommand\HostGraphic;
use Janisvepris\ZplBuilder\ZplCommand\ImageLoad;
use Janisvepris\ZplBuilder\ZplCommand\ImageMove;
use Janisvepris\ZplBuilder\ZplCommand\ImageSave;
use Janisvepris\ZplBuilder\ZplCommand\LabelHome;
use Janisvepris\ZplBuilder\ZplCommand\LabelLength;
use Janisvepris\ZplBuilder\ZplCommand\LabelReversePrint;
use Janisvepris\ZplBuilder\ZplCommand\MultipleFieldOrigin;
use Janisvepris\ZplBuilder\ZplCommand\ObjectDelete;
use Janisvepris\ZplBuilder\ZplCommand\PrintOrientation;
use Janisvepris\ZplBuilder\ZplCommand\PrintQuantity;
use Janisvepris\ZplBuilder\ZplCommand\PrintWidth;
use Janisvepris\ZplBuilder\ZplCommand\RawCommand;
use Janisvepris\ZplBuilder\ZplCommand\RecallFormat;
use Janisvepris\ZplBuilder\ZplCommand\RecallGraphic;
use Janisvepris\ZplBuilder\ZplCommand\ScalableBitmappedFont;
use Janisvepris\ZplBuilder\ZplCommand\SelectDateTimeFormat;
use Janisvepris\ZplBuilder\ZplCommand\SelectEncoding;
use Janisvepris\ZplBuilder\ZplCommand\SerializationData;
use Janisvepris\ZplBuilder\ZplCommand\SerializationField;
use Janisvepris\ZplBuilder\ZplCommand\SetClockMode;
use Janisvepris\ZplBuilder\ZplCommand\SetDateTime;
use Janisvepris\ZplBuilder\ZplCommand\SetOffset;
use Janisvepris\ZplBuilder\ZplCommand\StartFormat;
use Janisvepris\ZplBuilder\ZplCommand\TransferObject;
use Janisvepris\ZplBuilder\ZplCommand\UploadGraphics;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use ReflectionClass;
use ReflectionMethod;

#[CoversClass(ZplBuilder::class)]
#[UsesClass(AztecErrorControl::class)]
#[UsesClass(AbortDownloadGraphic::class)]
#[UsesClass(BarcodeAztec::class)]
#[UsesClass(BarcodeCodabar::class)]
#[UsesClass(BarcodeCodablock::class)]
#[UsesClass(BarcodeCode11::class)]
#[UsesClass(BarcodeCode128::class)]
#[UsesClass(BarcodeCode39::class)]
#[UsesClass(BarcodeCode49::class)]
#[UsesClass(BarcodeCode93::class)]
#[UsesClass(BarcodeDataMatrix::class)]
#[UsesClass(BarcodeDefaults::class)]
#[UsesClass(BarcodeDefaultSettings::class)]
#[UsesClass(BarcodeEan13::class)]
#[UsesClass(BarcodeEan8::class)]
#[UsesClass(BarcodeIndustrial2of5::class)]
#[UsesClass(BarcodeInterleaved2of5::class)]
#[UsesClass(BarcodeLogmars::class)]
#[UsesClass(BarcodeMaxiCode::class)]
#[UsesClass(BarcodeMicroPdf417::class)]
#[UsesClass(BarcodeMsi::class)]
#[UsesClass(BarcodePdf417::class)]
#[UsesClass(BarcodePlanetCode::class)]
#[UsesClass(BarcodePlessey::class)]
#[UsesClass(BarcodePostnet::class)]
#[UsesClass(BarcodeQrCode::class)]
#[UsesClass(BarcodeRss::class)]
#[UsesClass(BarcodeStandard2of5::class)]
#[UsesClass(BarcodeTlc39::class)]
#[UsesClass(BarcodeUpcA::class)]
#[UsesClass(BarcodeUpcE::class)]
#[UsesClass(BarcodeUpcEanExtensions::class)]
#[UsesClass(BoolToStr::class)]
#[UsesClass(ChangeFont::class)]
#[UsesClass(ChangeInternationalEncoding::class)]
#[UsesClass(CharacterRemap::class)]
#[UsesClass(ClockSet::class)]
#[UsesClass(ClockTimeFormat::class)]
#[UsesClass(CodabarCharacter::class)]
#[UsesClass(CodablockMode::class)]
#[UsesClass(Code128Mode::class)]
#[UsesClass(DataMatrixQuality::class)]
#[UsesClass(DateTimeFormat::class)]
#[UsesClass(DownloadGraphics::class)]
#[UsesClass(DownloadObject::class)]
#[UsesClass(DuplicateClockIndicatorException::class)]
#[UsesClass(Encoding::class)]
#[UsesClass(EndFormat::class)]
#[UsesClass(EraseDownloadGraphics::class)]
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
#[UsesClass(FontIdentifier::class)]
#[UsesClass(FontName::class)]
#[UsesClass(FontPreset::class)]
#[UsesClass(FontPresetDoesNotExistException::class)]
#[UsesClass(FontSettings::class)]
#[UsesClass(GraphicBox::class)]
#[UsesClass(GraphicCircle::class)]
#[UsesClass(GraphicDiagonalLine::class)]
#[UsesClass(GraphicEllipse::class)]
#[UsesClass(GraphicField::class)]
#[UsesClass(GraphicSymbol::class)]
#[UsesClass(HostGraphic::class)]
#[UsesClass(ImageLoad::class)]
#[UsesClass(ImageMove::class)]
#[UsesClass(ImageSave::class)]
#[UsesClass(IntegerValueOutOfRangeException::class)]
#[UsesClass(Justify::class)]
#[UsesClass(LabelFlip::class)]
#[UsesClass(LabelHome::class)]
#[UsesClass(LabelLength::class)]
#[UsesClass(LabelReversePrint::class)]
#[UsesClass(LineColor::class)]
#[UsesClass(MaxiCodeMode::class)]
#[UsesClass(MsiCheckDigit::class)]
#[UsesClass(MultipleFieldOrigin::class)]
#[UsesClass(ObjectDelete::class)]
#[UsesClass(Orientation::class)]
#[UsesClass(PrintDirection::class)]
#[UsesClass(PrintOrientation::class)]
#[UsesClass(PrintQuantity::class)]
#[UsesClass(PrintWidth::class)]
#[UsesClass(QrErrorCorrection::class)]
#[UsesClass(QrModel::class)]
#[UsesClass(RawCommand::class)]
#[UsesClass(RecallFormat::class)]
#[UsesClass(RecallGraphic::class)]
#[UsesClass(RssSymbologyType::class)]
#[UsesClass(SelectDateTimeFormat::class)]
#[UsesClass(SelectEncoding::class)]
#[UsesClass(SerializationData::class)]
#[UsesClass(SerializationField::class)]
#[UsesClass(SetClockMode::class)]
#[UsesClass(SetDateTime::class)]
#[UsesClass(SetOffset::class)]
#[UsesClass(StartFormat::class)]
#[UsesClass(StorageDevice::class)]
#[UsesClass(StringLengthOutOfRangeException::class)]
#[UsesClass(StringValueContainsBannedValuesException::class)]
#[UsesClass(TertiaryClockIndicatorWithoutSecondaryException::class)]
#[UsesClass(TransferObject::class)]
#[UsesClass(UnsupportedFontExtensionException::class)]
#[UsesClass(UploadGraphics::class)]
#[UsesClass(ValueAssert::class)]
#[UsesClass(ScalableBitmappedFont::class)]
class ZplBuilderTest extends UnitTestCase
{
    public function testAbortDownloadGraphicEmitsDn(): void
    {
        $output = (string) ZplBuilder::start()->abortDownloadGraphic();

        self::assertSame('^XA~DN', $output);
    }

    public function testAddFontPresetInheritsDimensionsFromFontWhenOmitted(): void
    {
        $builder = ZplBuilder::start()
            ->changeFont(Font::A, 30, 15)
            ->addFontPreset('big', Font::A);

        $preset = $builder->getFontPresets()['big'];

        self::assertSame(30, $preset->height);
        self::assertSame(15, $preset->width);
    }

    public function testApplicatorReprintEmitsPr(): void
    {
        $output = (string) ZplBuilder::start()->applicatorReprint();

        self::assertSame('^XA~PR', $output);
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

    public function testBarcodeAztecEmitsB0ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeAztec('DATA');

        self::assertSame('^XA^B0N,1,N,0,N,1^FDDATA^FS', $output);
    }

    public function testBarcodeAztecEmitsStructuredAppendId(): void
    {
        $output = (string) ZplBuilder::start()->barcodeAztec(
            'DATA',
            orientation: Orientation::Rotate90,
            magnification: 7,
            errorControl: AztecErrorControl::fullRangeSymbol(32),
            symbolCount: 3,
            structuredAppendId: 'JOB42',
        );

        self::assertSame('^XA^B0R,7,N,232,N,3,JOB42^FDDATA^FS', $output);
    }

    public function testBarcodeCodabarEmitsBkThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCodabar('12345', height: 100);

        self::assertSame('^XA^BKN,N,100,Y,N,A,A^FD12345^FS', $output);
    }

    public function testBarcodeCodabarEmitsCustomStartStop(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCodabar(
            '12345',
            height: 100,
            startCharacter: CodabarCharacter::B,
            stopCharacter: CodabarCharacter::C,
        );

        self::assertSame('^XA^BKN,N,100,Y,N,B,C^FD12345^FS', $output);
    }

    public function testBarcodeCodabarInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCodabar('12345');

        self::assertStringContainsString('^BKN,N,50,', $output);
    }

    public function testBarcodeCodablockEmitsBbThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCodablock('DATA');

        self::assertSame('^XA^BBN,8,Y,,,F^FDDATA^FS', $output);
    }

    public function testBarcodeCodablockEmitsRowsAndColumns(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCodablock(
            'DATA',
            orientation: Orientation::Rotate90,
            rowHeight: 10,
            security: false,
            charactersPerRow: 30,
            rows: 12,
            mode: CodablockMode::ModeA,
        );

        self::assertSame('^XA^BBR,10,N,30,12,A^FDDATA^FS', $output);
    }

    public function testBarcodeCode11EmitsB1ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode11('123456', height: 150);

        self::assertSame('^XA^B1N,N,150,Y,N^FD123456^FS', $output);
    }

    public function testBarcodeCode11InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode11('123456');

        self::assertStringContainsString('^B1N,N,50,', $output);
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

    public function testBarcodeCode39EmitsB3ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode39('123ABC', height: 100);

        self::assertSame('^XA^B3N,N,100,Y,N^FD123ABC^FS', $output);
    }

    public function testBarcodeCode39InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode39('123ABC');

        self::assertStringContainsString('^B3N,N,50,', $output);
    }

    public function testBarcodeCode49EmitsB4ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode49('12345ABCDE', height: 20);

        self::assertSame('^XA^B4N,20,N,A^FD12345ABCDE^FS', $output);
    }

    public function testBarcodeCode49EmitsInterpretationLineAndMode(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode49(
            '12345ABCDE',
            height: 20,
            interpretationLine: Code49InterpretationLine::Below,
            mode: Code49Mode::RegularNumeric,
        );

        self::assertSame('^XA^B4N,20,B,2^FD12345ABCDE^FS', $output);
    }

    public function testBarcodeCode49InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode49('12345ABCDE');

        self::assertStringContainsString('^B4N,50,', $output);
    }

    public function testBarcodeCode93EmitsBaThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeCode93('CODE93', height: 100);

        self::assertSame('^XA^BAN,100,Y,N,N^FDCODE93^FS', $output);
    }

    public function testBarcodeCode93InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeCode93('CODE93');

        self::assertStringContainsString('^BAN,50,', $output);
    }

    public function testBarcodeDataMatrixEmitsBxThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeDataMatrix('DATA');

        self::assertSame('^XA^BXN,0,200^FDDATA^FS', $output);
    }

    public function testBarcodeDataMatrixEmitsForcedSize(): void
    {
        $output = (string) ZplBuilder::start()->barcodeDataMatrix(
            'DATA',
            orientation: Orientation::Rotate90,
            moduleHeight: 10,
            quality: DataMatrixQuality::Ecc100,
            columns: 16,
            rows: 16,
        );

        self::assertSame('^XA^BXR,10,100,16,16^FDDATA^FS', $output);
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

    public function testBarcodeEan13EmitsBeThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeEan13('123456789012', height: 100);

        self::assertSame('^XA^BEN,100,Y,N^FD123456789012^FS', $output);
    }

    public function testBarcodeEan13InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeEan13('123456789012');

        self::assertStringContainsString('^BEN,50,', $output);
    }

    public function testBarcodeEan8EmitsB8ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeEan8('1234567', height: 100);

        self::assertSame('^XA^B8N,100,Y,N^FD1234567^FS', $output);
    }

    public function testBarcodeEan8InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeEan8('1234567');

        self::assertStringContainsString('^B8N,50,', $output);
    }

    public function testBarcodeIndustrial2of5EmitsBiThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeIndustrial2of5('123456', height: 150);

        self::assertSame('^XA^BIN,150,Y,N^FD123456^FS', $output);
    }

    public function testBarcodeIndustrial2of5InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeIndustrial2of5('123456');

        self::assertStringContainsString('^BIN,50,', $output);
    }

    public function testBarcodeInterleaved2of5EmitsB2ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeInterleaved2of5('123456', height: 150);

        self::assertSame('^XA^B2N,150,Y,N,N^FD123456^FS', $output);
    }

    public function testBarcodeInterleaved2of5InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeInterleaved2of5('123456');

        self::assertStringContainsString('^B2N,50,', $output);
    }

    public function testBarcodeLogmarsEmitsBlThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeLogmars('LOGMARS', height: 100);

        self::assertSame('^XA^BLN,100,N^FDLOGMARS^FS', $output);
    }

    public function testBarcodeLogmarsInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeLogmars('LOGMARS');

        self::assertStringContainsString('^BLN,50,', $output);
    }

    public function testBarcodeMaxiCodeEmitsBdThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeMaxiCode('123456123451234567');

        self::assertSame('^XA^BD2,1,1^FD123456123451234567^FS', $output);
    }

    public function testBarcodeMaxiCodeEmitsStructuredAppend(): void
    {
        $output = (string) ZplBuilder::start()->barcodeMaxiCode(
            'DATA',
            mode: MaxiCodeMode::StandardSymbol,
            symbolNumber: 2,
            totalSymbols: 4,
        );

        self::assertSame('^XA^BD4,2,4^FDDATA^FS', $output);
    }

    public function testBarcodeMicroPdf417EmitsBfThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeMicroPdf417('Zebra', height: 20, mode: 5);

        self::assertSame('^XA^BFN,20,5^FDZebra^FS', $output);
    }

    public function testBarcodeMicroPdf417InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeMicroPdf417('Zebra');

        self::assertStringContainsString('^BFN,50,0', $output);
    }

    public function testBarcodeMsiEmitsBmThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeMsi('1234', height: 100);

        self::assertSame('^XA^BMN,B,100,Y,N,N^FD1234^FS', $output);
    }

    public function testBarcodeMsiEmitsCheckDigitSelection(): void
    {
        $output = (string) ZplBuilder::start()->barcodeMsi(
            '1234',
            checkDigit: MsiCheckDigit::TwoMod10,
            height: 100,
            insertCheckDigitInInterpretation: true,
        );

        self::assertSame('^XA^BMN,C,100,Y,N,Y^FD1234^FS', $output);
    }

    public function testBarcodeMsiInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeMsi('1234');

        self::assertStringContainsString('^BMN,B,50,', $output);
    }

    public function testBarcodePdf417EmitsB7ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodePdf417('Zebra', height: 5, securityLevel: 5, rows: 83);

        self::assertSame('^XA^B7N,5,5,,83,N^FDZebra^FS', $output);
    }

    public function testBarcodePdf417InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodePdf417('Zebra');

        self::assertStringContainsString('^B7N,50,0,,,N', $output);
    }

    public function testBarcodePlanetCodeEmitsB5ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodePlanetCode('12345678901', height: 100);

        self::assertSame('^XA^B5N,100,N,N^FD12345678901^FS', $output);
    }

    public function testBarcodePlanetCodeInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodePlanetCode('12345678901');

        self::assertStringContainsString('^B5N,50,', $output);
    }

    public function testBarcodePlesseyEmitsBpThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodePlessey('12345', height: 100);

        self::assertSame('^XA^BPN,N,100,Y,N^FD12345^FS', $output);
    }

    public function testBarcodePlesseyInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodePlessey('12345');

        self::assertStringContainsString('^BPN,N,50,', $output);
    }

    public function testBarcodePostnetEmitsBzThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodePostnet('12345', height: 100);

        self::assertSame('^XA^BZN,100,N,N^FD12345^FS', $output);
    }

    public function testBarcodePostnetInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodePostnet('12345');

        self::assertStringContainsString('^BZN,50,', $output);
    }

    public function testBarcodeQrCodeEmitsBqThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeQrCode('QA,HELLO', magnification: 10);

        self::assertSame('^XA^BQN,2,10^FDQA,HELLO^FS', $output);
    }

    public function testBarcodeQrCodeEmitsErrorCorrectionAndMask(): void
    {
        $output = (string) ZplBuilder::start()->barcodeQrCode(
            'QA,HELLO',
            model: QrModel::Model1,
            magnification: 5,
            errorCorrection: QrErrorCorrection::UltraHighReliability,
            maskValue: 3,
        );

        self::assertSame('^XA^BQN,1,5,H,3^FDQA,HELLO^FS', $output);
    }

    public function testBarcodeRssEmitsBrThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeRss('12345678901');

        self::assertSame('^XA^BRR,1,1,1,25,22^FD12345678901^FS', $output);
    }

    public function testBarcodeRssEmitsCustomParameters(): void
    {
        $output = (string) ZplBuilder::start()->barcodeRss(
            '12345678901',
            orientation: Orientation::Rotate0,
            symbologyType: RssSymbologyType::UpcA,
            magnification: 5,
            separatorHeight: 2,
            barcodeHeight: 100,
            segmentWidth: 20,
        );

        self::assertSame('^XA^BRN,7,5,2,100,20^FD12345678901^FS', $output);
    }

    public function testBarcodeStandard2of5EmitsBjThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeStandard2of5('123456', height: 150);

        self::assertSame('^XA^BJN,150,Y,N^FD123456^FS', $output);
    }

    public function testBarcodeStandard2of5InheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeStandard2of5('123456');

        self::assertStringContainsString('^BJN,50,', $output);
    }

    public function testBarcodeTlc39EmitsBtThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeTlc39('123456,ABCd12345678901234');

        self::assertSame('^XA^BTN,2,2.0,40,2,4^FD123456,ABCd12345678901234^FS', $output);
    }

    public function testBarcodeTlc39EmitsCustomParameters(): void
    {
        $output = (string) ZplBuilder::start()->barcodeTlc39(
            '123456',
            orientation: Orientation::Rotate90,
            code39Width: 4,
            wideToNarrowRatio: 3.0,
            code39Height: 120,
            microPdfWidth: 4,
            microPdfRowHeight: 8,
        );

        self::assertSame('^XA^BTR,4,3.0,120,4,8^FD123456^FS', $output);
    }

    public function testBarcodeUpcAEmitsBuThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeUpcA('12345678901', height: 100);

        self::assertSame('^XA^BUN,100,Y,N,Y^FD12345678901^FS', $output);
    }

    public function testBarcodeUpcAInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeUpcA('12345678901');

        self::assertStringContainsString('^BUN,50,', $output);
    }

    public function testBarcodeUpcEanExtensionsEmitsBsThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeUpcEanExtensions('12345', height: 100);

        self::assertSame('^XA^BSN,100,Y,Y^FD12345^FS', $output);
    }

    public function testBarcodeUpcEanExtensionsInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeUpcEanExtensions('12');

        self::assertStringContainsString('^BSN,50,', $output);
    }

    public function testBarcodeUpcEEmitsB9ThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->barcodeUpcE('1230000045', height: 100);

        self::assertSame('^XA^B9N,100,Y,N,Y^FD1230000045^FS', $output);
    }

    public function testBarcodeUpcEInheritsHeightFromBarcodeDefaults(): void
    {
        $output = (string) ZplBuilder::start()
            ->barcodeDefaults(2, 3.0, 50)
            ->barcodeUpcE('1230000045');

        self::assertStringContainsString('^B9N,50,', $output);
    }

    public function testCacheOnEmitsCoWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->cacheOn();

        self::assertSame('^XA^COY,40,0', $output);
    }

    public function testCacheOnEmitsExplicitMemoryAndType(): void
    {
        $output = (string) ZplBuilder::start()->cacheOn(false, 128, CacheType::Internal);

        self::assertSame('^XA^CON,128,1', $output);
    }

    public function testCacheOnValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->cacheOn(additionalMemory: -1);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testCalibrateRfidTransponderEmitsHr(): void
    {
        $output = (string) ZplBuilder::start()->calibrateRfidTransponder();

        self::assertSame('^XA^HRstart,end', $output);
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

    public function testChangeMemoryLettersEmitsCm(): void
    {
        $output = (string) ZplBuilder::start()->changeMemoryLetters(
            aliasForB: MemoryLetter::Flash,
            aliasForE: MemoryLetter::MemoryCardB,
        );

        self::assertSame('^XA^CME,B,R,A', $output);
    }

    public function testChangeMemoryLettersUsesIdentityDefaults(): void
    {
        $output = (string) ZplBuilder::start()->changeMemoryLetters();

        self::assertSame('^XA^CMB,E,R,A', $output);
    }

    public function testCodeValidationDisables(): void
    {
        $output = (string) ZplBuilder::start()->codeValidation(false);

        self::assertSame('^XA^CVN', $output);
    }

    public function testCodeValidationEmitsCv(): void
    {
        $output = (string) ZplBuilder::start()->codeValidation();

        self::assertSame('^XA^CVY', $output);
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

    public function testDefineEpcDataStructureEmitsRbWithPartitions(): void
    {
        $output = (string) ZplBuilder::start()->defineEpcDataStructure(96, 10, 26, 60);

        self::assertSame('^XA^RB96,10,26,60', $output);
    }

    public function testDefineEpcDataStructureEmitsTotalBitSizeOnly(): void
    {
        $output = (string) ZplBuilder::start()->defineEpcDataStructure();

        self::assertSame('^XA^RB96', $output);
    }

    public function testDownloadFormatEmitsDf(): void
    {
        $output = (string) ZplBuilder::start()->downloadFormat('STOREFMT', StorageDevice::Flash);

        self::assertSame('^XA^DFE:STOREFMT.ZPL', $output);
    }

    public function testDownloadFormatUsesRamAndZplDefaults(): void
    {
        $output = (string) ZplBuilder::start()->downloadFormat('STOREFMT');

        self::assertSame('^XA^DFR:STOREFMT.ZPL', $output);
    }

    public function testDownloadGraphicsEmitsDg(): void
    {
        $output = (string) ZplBuilder::start()->downloadGraphics('SAMPLE', 8000, 80, 'FF00FF00', StorageDevice::Flash);

        self::assertSame('^XA~DGE:SAMPLE.GRF,8000,80,FF00FF00', $output);
    }

    public function testDownloadGraphicsUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->downloadGraphics('LOGO', 16, 2, 'ABCD');

        self::assertSame('^XA~DGR:LOGO.GRF,16,2,ABCD', $output);
    }

    public function testDownloadGraphicsValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->downloadGraphics('', 16, 2, 'ABCD');
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testDownloadObjectEmitsDy(): void
    {
        $output = (string) ZplBuilder::start()->downloadObject(
            'FONTFILE.TTF',
            DownloadFormat::UncompressedBinary,
            52010,
            DownloadExtension::TrueType,
            0,
            '',
            StorageDevice::Flash,
        );

        self::assertSame('^XA~DYE:FONTFILE.TTF,B,T,52010,0,', $output);
    }

    public function testDownloadObjectUsesGrfRamAndEmptyDataDefaults(): void
    {
        $output = (string) ZplBuilder::start()->downloadObject('LOGO', DownloadFormat::UncompressedAscii, 8000);

        self::assertSame('^XA~DYR:LOGO,A,G,8000,0,', $output);
    }

    public function testDownloadObjectValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->downloadObject('', DownloadFormat::UncompressedAscii, 8000);
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testEnableEasBitDisables(): void
    {
        $output = (string) ZplBuilder::start()->enableEasBit(false);

        self::assertSame('^XA^REN,0', $output);
    }

    public function testEnableEasBitEmitsRe(): void
    {
        $output = (string) ZplBuilder::start()->enableEasBit();

        self::assertSame('^XA^REY,0', $output);
    }

    public function testEnableRfidMotionDisables(): void
    {
        $output = (string) ZplBuilder::start()->enableRfidMotion(false);

        self::assertSame('^XA^RMN', $output);
    }

    public function testEnableRfidMotionEmitsRm(): void
    {
        $output = (string) ZplBuilder::start()->enableRfidMotion();

        self::assertSame('^XA^RMY', $output);
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

    public function testEraseDownloadGraphicsEmitsEg(): void
    {
        $output = (string) ZplBuilder::start()->eraseDownloadGraphics();

        self::assertSame('^XA~EG', $output);
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

    public function testFontByNameRejectsTrueTypeExtension(): void
    {
        // ^A@ accepts only .FNT and .TTF; .TTE must go through fontIdentifier() (^CW).
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->fontByName('ARI000', 50, 50, FontExtension::TrueTypeExtension);
            self::fail('Expected UnsupportedFontExtensionException');
        } catch (UnsupportedFontExtensionException) {
        }

        self::assertSame($before, (string) $builder);
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

    public function testFontIdentifierEmitsCwWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->fontIdentifier(Font::T, 'ARIAL');

        // Defaults: extension .FNT, device R: (RAM). Standalone — no trailing ^FS.
        self::assertSame('^XA^CWT,R:ARIAL.FNT', $output);
    }

    public function testFontIdentifierEmitsCwWithExplicitArguments(): void
    {
        $output = (string) ZplBuilder::start()->fontIdentifier(
            font: Font::One,
            name: 'ANMDS',
            extension: FontExtension::TrueTypeExtension,
            device: StorageDevice::Flash,
        );

        self::assertSame('^XA^CW1,E:ANMDS.TTE', $output);
    }

    public function testFontIdentifierValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->fontIdentifier(Font::T, '');
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
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

    public function testGetRfidTagIdEmitsRi(): void
    {
        $output = (string) ZplBuilder::start()->getRfidTagId();

        self::assertSame('^XA^RI0,0,0,0', $output);
    }

    public function testGetRfidTagIdValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->getRfidTagId(retries: 11);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testGraphicBoxEmitsGbAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->graphicBox(100, 50, 2);

        self::assertSame('^XA^GB100,50,2,B,0^FS', $output);
    }

    public function testGraphicCircleEmitsGcAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->graphicCircle(100, 2, LineColor::White);

        self::assertSame('^XA^GC100,2,W^FS', $output);
    }

    public function testGraphicCircleEmitsGcWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->graphicCircle(100);

        self::assertSame('^XA^GC100,1,B^FS', $output);
    }

    public function testGraphicDiagonalLineEmitsGdAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()
            ->graphicDiagonalLine(300, 200, 3, LineColor::White, DiagonalOrientation::LeftLeaning);

        self::assertSame('^XA^GD300,200,3,W,L^FS', $output);
    }

    public function testGraphicDiagonalLineEmitsGdWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->graphicDiagonalLine(100, 100);

        self::assertSame('^XA^GD100,100,1,B,R^FS', $output);
    }

    public function testGraphicEllipseEmitsGeAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->graphicEllipse(300, 200, 4, LineColor::White);

        self::assertSame('^XA^GE300,200,4,W^FS', $output);
    }

    public function testGraphicEllipseEmitsGeWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->graphicEllipse(300, 200);

        self::assertSame('^XA^GE300,200,1,B^FS', $output);
    }

    public function testGraphicFieldDefaultsToAsciiCompression(): void
    {
        $output = (string) ZplBuilder::start()->graphicField(4, 4, 2, 'FF00');

        self::assertSame('^XA^GFA,4,4,2,FF00^FS', $output);
    }

    public function testGraphicFieldEmitsGfAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()
            ->graphicField(8000, 8000, 80, 'FF00FF00', GraphicFieldCompression::Binary);

        self::assertSame('^XA^GFB,8000,8000,80,FF00FF00^FS', $output);
    }

    public function testGraphicFieldValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->graphicField(0, 4, 2, 'FF00');
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testGraphicSymbolDefaultsToNormalOrientation(): void
    {
        $output = (string) ZplBuilder::start()->graphicSymbol('B', 27, 27);

        self::assertSame('^XA^GSN,27,27^FDB^FS', $output);
    }

    public function testGraphicSymbolEmitsGsThenFieldData(): void
    {
        $output = (string) ZplBuilder::start()->graphicSymbol('A', 50, 40, Orientation::Rotate90);

        self::assertSame('^XA^GSR,50,40^FDA^FS', $output);
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

    public function testHeadColdWarningDisables(): void
    {
        $output = (string) ZplBuilder::start()->headColdWarning(false);

        self::assertSame('^XA^MWN', $output);
    }

    public function testHeadColdWarningEmitsMw(): void
    {
        $output = (string) ZplBuilder::start()->headColdWarning();

        self::assertSame('^XA^MWY', $output);
    }

    public function testHostFormatEmitsHf(): void
    {
        $output = (string) ZplBuilder::start()->hostFormat('FILE1', StorageDevice::MemoryCardB);

        self::assertSame('^XA^HFB:FILE1.ZPL', $output);
    }

    public function testHostFormatUsesRamAndZplDefaults(): void
    {
        $output = (string) ZplBuilder::start()->hostFormat('FILE1');

        self::assertSame('^XA^HFR:FILE1.ZPL', $output);
    }

    public function testHostGraphicEmitsHg(): void
    {
        $output = (string) ZplBuilder::start()->hostGraphic('SAMPLE', StorageDevice::Flash);

        self::assertSame('^XA^HGE:SAMPLE.GRF', $output);
    }

    public function testHostGraphicUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->hostGraphic('LOGO');

        self::assertSame('^XA^HGR:LOGO.GRF', $output);
    }

    public function testImageLoadEmitsIlAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->imageLoad('SAMPLE', StorageDevice::Flash);

        self::assertSame('^XA^ILE:SAMPLE.GRF^FS', $output);
    }

    public function testImageLoadUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->imageLoad('LOGO');

        self::assertSame('^XA^ILR:LOGO.GRF^FS', $output);
    }

    public function testImageMoveEmitsImAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->imageMove('SAMPLE', StorageDevice::Flash);

        self::assertSame('^XA^IME:SAMPLE.GRF^FS', $output);
    }

    public function testImageMoveUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->imageMove('LOGO');

        self::assertSame('^XA^IMR:LOGO.GRF^FS', $output);
    }

    public function testImageSaveEmitsIsAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->imageSave('SAMPLE', StorageDevice::Flash, 'PNG', false);

        self::assertSame('^XA^ISE:SAMPLE.PNG,N^FS', $output);
    }

    public function testImageSaveUsesRamGrfAndPrintDefaults(): void
    {
        $output = (string) ZplBuilder::start()->imageSave('LOGO');

        self::assertSame('^XA^ISR:LOGO.GRF,Y^FS', $output);
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

    public function testLabelShiftEmitsLs(): void
    {
        $output = (string) ZplBuilder::start()->labelShift(-50);

        self::assertSame('^XA^LS-50', $output);
    }

    public function testLabelShiftUsesZeroDefault(): void
    {
        $output = (string) ZplBuilder::start()->labelShift();

        self::assertSame('^XA^LS0', $output);
    }

    public function testLabelTopEmitsLt(): void
    {
        $output = (string) ZplBuilder::start()->labelTop(-30);

        self::assertSame('^XA^LT-30', $output);
    }

    public function testMapClearEmitsMc(): void
    {
        $output = (string) ZplBuilder::start()->mapClear();

        self::assertSame('^XA^MCY', $output);
    }

    public function testMapClearRetainsBitmap(): void
    {
        $output = (string) ZplBuilder::start()->mapClear(false);

        self::assertSame('^XA^MCN', $output);
    }

    public function testMaximumLabelLengthEmitsMl(): void
    {
        $output = (string) ZplBuilder::start()->maximumLabelLength(1225);

        self::assertSame('^XA^ML1225', $output);
    }

    public function testMediaDarknessEmitsMd(): void
    {
        $output = (string) ZplBuilder::start()->mediaDarkness(15);

        self::assertSame('^XA^MD15', $output);
    }

    public function testMediaDarknessRendersNegativeAdjustment(): void
    {
        $output = (string) ZplBuilder::start()->mediaDarkness(-6);

        self::assertSame('^XA^MD-6', $output);
    }

    public function testMediaFeedEmitsMf(): void
    {
        $output = (string) ZplBuilder::start()->mediaFeed(
            MediaFeedAction::Feed,
            MediaFeedAction::None,
        );

        self::assertSame('^XA^MFF,N', $output);
    }

    public function testMediaTrackingEmitsMn(): void
    {
        $output = (string) ZplBuilder::start()->mediaTracking(MediaTrackingType::NonContinuousWeb);

        self::assertSame('^XA^MNY', $output);
    }

    public function testMediaTypeEmitsMt(): void
    {
        $output = (string) ZplBuilder::start()->mediaType(PrintMethod::ThermalTransfer);

        self::assertSame('^XA^MTT', $output);
    }

    public function testModeProtectionEmitsMp(): void
    {
        $output = (string) ZplBuilder::start()->modeProtection(ProtectedMode::DisableDarkness);

        self::assertSame('^XA^MPD', $output);
    }

    public function testNetworkIdEmitsNi(): void
    {
        $output = (string) ZplBuilder::start()->networkId(42);

        self::assertSame('^XA^NI042', $output);
    }

    public function testNetworkIdValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->networkId(0);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testNoPrintQuantityEmittedByDefault(): void
    {
        $output = (string) ZplBuilder::start()->fieldData('Hello')->end();

        self::assertStringNotContainsString('^PQ', $output);
        self::assertSame('^XA^FDHello^FS^XZ', $output);
    }

    public function testObjectDeleteEmitsIdAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->objectDelete('SAMPLE', StorageDevice::Flash, 'ZPL');

        self::assertSame('^XA^IDE:SAMPLE.ZPL^FS', $output);
    }

    public function testObjectDeleteUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->objectDelete('*');

        self::assertSame('^XA^IDR:*.GRF^FS', $output);
    }

    public function testPrimaryDeviceEmitsNpWithDefault(): void
    {
        $output = (string) ZplBuilder::start()->primaryDevice();

        self::assertSame('^XA^NPP', $output);
    }

    public function testPrimaryDeviceSelectsPrintServer(): void
    {
        $output = (string) ZplBuilder::start()->primaryDevice(NetworkDevice::PrintServer);

        self::assertSame('^XA^NPM', $output);
    }

    public function testPrinterSleepEmitsExplicitValues(): void
    {
        $output = (string) ZplBuilder::start()->printerSleep(300, true);

        self::assertSame('^XA^ZZ300,Y', $output);
    }

    public function testPrinterSleepEmitsZzWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->printerSleep();

        self::assertSame('^XA^ZZ0,N', $output);
    }

    public function testPrinterSleepValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->printerSleep(idleSeconds: 1000000);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testPrintMirrorEmitsPm(): void
    {
        $output = (string) ZplBuilder::start()->printMirror(false);

        self::assertSame('^XA^PMN', $output);
    }

    public function testPrintMirrorUsesYesDefault(): void
    {
        $output = (string) ZplBuilder::start()->printMirror();

        self::assertSame('^XA^PMY', $output);
    }

    public function testPrintModeEmitsCutterWithoutPrepeel(): void
    {
        $output = (string) ZplBuilder::start()->printMode(PostPrintAction::Cutter, false);

        self::assertSame('^XA^MMC,N', $output);
    }

    public function testPrintModeEmitsMmWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->printMode();

        self::assertSame('^XA^MMT,Y', $output);
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

    public function testPrintRateEmitsExplicitSpeeds(): void
    {
        $output = (string) ZplBuilder::start()->printRate(
            PrintSpeed::Ips6,
            PrintSpeed::Ips8,
            PrintSpeed::Ips4,
        );

        self::assertSame('^XA^PR6,8,4', $output);
    }

    public function testPrintRateEmitsPrWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->printRate();

        self::assertSame('^XA^PR2,6,2', $output);
    }

    public function testPrintStartEmitsPs(): void
    {
        $output = (string) ZplBuilder::start()->printStart();

        self::assertSame('^XA~PS', $output);
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

    public function testReadAfiOrDsfidByteEmitsRa(): void
    {
        $output = (string) ZplBuilder::start()->readAfiOrDsfidByte();

        self::assertSame('^XA^RA0,0,0,0,A', $output);
    }

    public function testReadAfiOrDsfidByteValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->readAfiOrDsfidByte(retries: 11);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testReadWriteRfidFormatEmitsRf(): void
    {
        $output = (string) ZplBuilder::start()->readWriteRfidFormat();

        self::assertSame('^XA^RFW,H', $output);
    }

    public function testReadWriteRfidFormatReads(): void
    {
        $output = (string) ZplBuilder::start()->readWriteRfidFormat(RfidOperation::Read);

        self::assertSame('^XA^RFR,H', $output);
    }

    public function testRecallFormatEmitsXf(): void
    {
        $output = (string) ZplBuilder::start()->recallFormat('LABEL', StorageDevice::Flash);

        self::assertSame('^XA^XFE:LABEL.ZPL', $output);
    }

    public function testRecallGraphicEmitsXgAndSeparator(): void
    {
        $output = (string) ZplBuilder::start()->recallGraphic('SAMPLE', StorageDevice::Flash, 'GRF', 2, 3);

        self::assertSame('^XA^XGE:SAMPLE.GRF,2,3^FS', $output);
    }

    public function testRecallGraphicUsesRamGrfAndUnitMagnificationDefaults(): void
    {
        $output = (string) ZplBuilder::start()->recallGraphic('LOGO');

        self::assertSame('^XA^XGR:LOGO.GRF,1,1^FS', $output);
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

    public function testSearchWiredPrintServerChecks(): void
    {
        $output = (string) ZplBuilder::start()->searchWiredPrintServer(WiredPrintServerCheck::Check);

        self::assertSame('^XA^NBC', $output);
    }

    public function testSearchWiredPrintServerEmitsNbWithDefault(): void
    {
        $output = (string) ZplBuilder::start()->searchWiredPrintServer();

        self::assertSame('^XA^NBS', $output);
    }

    public function testSelectDateTimeFormatEmitsKd(): void
    {
        $output = (string) ZplBuilder::start()->selectDateTimeFormat(DateTimeFormat::DayMonthYear24Hour);

        self::assertSame('^XA^KD3', $output);
    }

    public function testSelectEncodingEmitsSe(): void
    {
        $output = (string) ZplBuilder::start()->selectEncoding('CP1252', StorageDevice::Flash);

        self::assertSame('^XA^SEE:CP1252.DAT', $output);
    }

    public function testSelectEncodingUsesRamDeviceByDefault(): void
    {
        $output = (string) ZplBuilder::start()->selectEncoding('UTF8');

        self::assertSame('^XA^SER:UTF8.DAT', $output);
    }

    public function testSelectEncodingValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->selectEncoding('');
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
            // expected
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testSerializationDataAutoEscapesStartValue(): void
    {
        $output = (string) ZplBuilder::start()->serializationData('A^B', '1');

        self::assertSame('^XA^FH_^SNA_5EB,1,N^FS', $output);
    }

    public function testSerializationDataDefaultsIncrementAndLeadingZeros(): void
    {
        $output = (string) ZplBuilder::start()->serializationData('0001');

        self::assertSame('^XA^SN0001,1,N^FS', $output);
    }

    public function testSerializationDataEmitsSnThenFieldSeparator(): void
    {
        $output = (string) ZplBuilder::start()->serializationData('BL0000', '1', false);

        self::assertSame('^XA^SNBL0000,1,N^FS', $output);
    }

    public function testSerializationDataUsesExplicitDecrementAndLeadingZeros(): void
    {
        $output = (string) ZplBuilder::start()->serializationData('0100', '-5', true);

        self::assertSame('^XA^SN0100,-5,Y^FS', $output);
    }

    public function testSerializationDataValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->serializationData('00,01', '1');
            self::fail('Expected StringValueContainsBannedValuesException');
        } catch (StringValueContainsBannedValuesException) {
            // expected — a comma in the start value would corrupt the parameter list.
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSerializationFieldAutoEscapesStartValue(): void
    {
        $output = (string) ZplBuilder::start()->serializationField('A^B', 'DDD', '1');

        self::assertSame('^XA^FH_^FDA_5EB^SFDDD,1^FS', $output);
    }

    public function testSerializationFieldDefaultsIncrementToOne(): void
    {
        $output = (string) ZplBuilder::start()->serializationField('12345678', 'DDDDDDDD');

        self::assertSame('^XA^FD12345678^SFDDDDDDDD,1^FS', $output);
    }

    public function testSerializationFieldEmitsFdThenSfThenFieldSeparator(): void
    {
        $output = (string) ZplBuilder::start()->serializationField('00-0', '%%%%%%%%%%%n', '1');

        self::assertSame('^XA^FD00-0^SF%%%%%%%%%%%n,1^FS', $output);
    }

    public function testSerializationFieldUsesExplicitMultiCharIncrement(): void
    {
        $output = (string) ZplBuilder::start()->serializationField('BL00', 'AADD', '11');

        self::assertSame('^XA^FDBL00^SFAADD,11^FS', $output);
    }

    public function testSerializationFieldValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->serializationField('12345', 'DD,DD', '1');
            self::fail('Expected StringValueContainsBannedValuesException');
        } catch (StringValueContainsBannedValuesException) {
            // expected — a comma in the mask would corrupt the two-parameter list.
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetClockModeEmitsSlWithDefaultStartMode(): void
    {
        $output = (string) ZplBuilder::start()->setClockMode();

        self::assertSame('^XA^SLS', $output);
    }

    public function testSetClockModeEmitsSlWithLanguage(): void
    {
        $output = (string) ZplBuilder::start()->setClockMode(
            language: ClockLanguage::German,
        );

        self::assertSame('^XA^SLS,4', $output);
    }

    public function testSetClockModeEmitsSlWithTimeNowMode(): void
    {
        $output = (string) ZplBuilder::start()->setClockMode(ClockMode::TimeNow);

        self::assertSame('^XA^SLT', $output);
    }

    public function testSetClockModeEmitsSlWithTolerance(): void
    {
        $output = (string) ZplBuilder::start()->setClockMode(
            toleranceSeconds: 30,
            language: ClockLanguage::English,
        );

        self::assertSame('^XA^SL30,1', $output);
    }

    public function testSetClockModeToleranceTakesPrecedenceOverDefaultMode(): void
    {
        $output = (string) ZplBuilder::start()->setClockMode(toleranceSeconds: 60);

        self::assertSame('^XA^SL60', $output);
    }

    public function testSetClockModeValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->setClockMode(toleranceSeconds: 1000);
            self::fail('Expected IntegerValueOutOfRangeException was not thrown.');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testSetDarknessEmitsSd(): void
    {
        $output = (string) ZplBuilder::start()->setDarkness(15);

        self::assertSame('^XA~SD15', $output);
    }

    public function testSetDarknessPadsSingleDigit(): void
    {
        $output = (string) ZplBuilder::start()->setDarkness(8);

        self::assertSame('^XA~SD08', $output);
    }

    public function testSetDarknessValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->setDarkness(31);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetDateTimeDefaultsToCurrentTimeAndMilitaryFormat(): void
    {
        $now = new DateTimeImmutable();
        $expected = sprintf(
            '^XA^ST%02d,%02d,%04d,%02d,%02d,%02d,M',
            (int) $now->format('n'),
            (int) $now->format('j'),
            (int) $now->format('Y'),
            (int) $now->format('G'),
            (int) $now->format('i'),
            (int) $now->format('s'),
        );

        $output = (string) ZplBuilder::start()->setDateTime();

        self::assertSame($expected, $output);
    }

    public function testSetDateTimeEmitsSt(): void
    {
        $output = (string) ZplBuilder::start()
            ->setDateTime(3, 7, 2026, 9, 5, 1, ClockTimeFormat::Am);

        self::assertSame('^XA^ST03,07,2026,09,05,01,A', $output);
    }

    public function testSetDateTimeValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->setDateTime(13, 1, 2026, 0, 0, 0, ClockTimeFormat::Military24Hour);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetMediaSensorsEmitsOptionalParameters(): void
    {
        $output = (string) ZplBuilder::start()->setMediaSensors(50, 40, 30, 1225, 60, 70);

        self::assertSame('^XA^SS050,040,030,1225,060,070', $output);
    }

    public function testSetMediaSensorsEmitsSs(): void
    {
        $output = (string) ZplBuilder::start()->setMediaSensors(50, 40, 30, 1225);

        self::assertSame('^XA^SS050,040,030,1225', $output);
    }

    public function testSetMediaSensorsValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->setMediaSensors(101, 0, 0, 1);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetOffsetEmitsSo(): void
    {
        $output = (string) ZplBuilder::start()
            ->setOffset(ClockSet::Tertiary, 1, 2, 3, 4, 5, 6);

        self::assertSame('^XA^SO3,1,2,3,4,5,6', $output);
    }

    public function testSetOffsetUsesZeroDefaultsForOmittedOffsets(): void
    {
        $output = (string) ZplBuilder::start()
            ->setOffset(ClockSet::Secondary);

        self::assertSame('^XA^SO2,0,0,0,0,0,0', $output);
    }

    public function testSetOffsetValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();

        try {
            $builder->setOffset(ClockSet::Secondary, 32001);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
            // expected
        }

        self::assertSame('^XA', (string) $builder);
    }

    public function testSetSmtpEmitsNt(): void
    {
        $output = (string) ZplBuilder::start()->setSmtp('10.0.0.1', 'example.com');

        self::assertSame('^XA^NT10.0.0.1,example.com', $output);
    }

    public function testSetSnmpEmitsNn(): void
    {
        $output = (string) ZplBuilder::start()->setSnmp(systemName: 'printer1');

        self::assertSame('^XA^NNprinter1,,,public,public,public', $output);
    }

    public function testSetSnmpValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->setSnmp(systemName: str_repeat('a', 18));
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetUnitsEmitsConversion(): void
    {
        $output = (string) ZplBuilder::start()->setUnits(MeasurementUnit::Dots, 150, 300);

        self::assertSame('^XA^MUD,150,300', $output);
    }

    public function testSetUnitsEmitsMuWithDefault(): void
    {
        $output = (string) ZplBuilder::start()->setUnits();

        self::assertSame('^XA^MUD', $output);
    }

    public function testSetUnitsValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->setUnits(baseDpi: 149);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testSetZplEmitsSzWithDefault(): void
    {
        $output = (string) ZplBuilder::start()->setZpl();

        self::assertSame('^XA^SZ2', $output);
    }

    public function testSetZplSelectsLegacyZpl(): void
    {
        $output = (string) ZplBuilder::start()->setZpl(ZplMode::Zpl);

        self::assertSame('^XA^SZ1', $output);
    }

    public function testSlewEmitsPf(): void
    {
        $output = (string) ZplBuilder::start()->slew(50);

        self::assertSame('^XA^PF50', $output);
    }

    public function testStartEmitsStartFormat(): void
    {
        self::assertSame('^XA', (string) ZplBuilder::start());
    }

    public function testStartPrintEmitsSp(): void
    {
        $output = (string) ZplBuilder::start()->startPrint(500);

        self::assertSame('^XA^SP500', $output);
    }

    public function testSuppressBackfeedEmitsXb(): void
    {
        $output = (string) ZplBuilder::start()->suppressBackfeed();

        self::assertSame('^XA^XB', $output);
    }

    public function testTearOffAdjustEmitsTa(): void
    {
        $output = (string) ZplBuilder::start()->tearOffAdjust(45);

        self::assertSame('^XA~TA045', $output);
    }

    public function testTearOffAdjustRendersNegativeAdjustment(): void
    {
        $output = (string) ZplBuilder::start()->tearOffAdjust(-30);

        self::assertSame('^XA~TA-30', $output);
    }

    public function testTransferObjectEmitsToWithDefaults(): void
    {
        $output = (string) ZplBuilder::start()->transferObject(
            StorageDevice::Ram,
            StorageDevice::MemoryCardB,
        );

        // Names and extensions default to the `*` wildcard — copy every object, keep extensions.
        // Standalone command — no trailing ^FS.
        self::assertSame('^XA^TOR:*.*,B:*.*', $output);
    }

    public function testTransferObjectEmitsToWithExplicitArguments(): void
    {
        $output = (string) ZplBuilder::start()->transferObject(
            sourceDevice: StorageDevice::Ram,
            destinationDevice: StorageDevice::MemoryCardB,
            sourceName: 'ZLOGO',
            sourceExtension: 'GRF',
            destinationName: 'ZLOGO1',
            destinationExtension: 'GRF',
        );

        self::assertSame('^XA^TOR:ZLOGO.GRF,B:ZLOGO1.GRF', $output);
    }

    public function testTransferObjectEmitsToWithWildcardNames(): void
    {
        $output = (string) ZplBuilder::start()->transferObject(
            sourceDevice: StorageDevice::Ram,
            destinationDevice: StorageDevice::MemoryCardB,
            sourceName: 'LOGO*',
            sourceExtension: 'GRF',
            destinationName: 'NEW*',
            destinationExtension: 'GRF',
        );

        self::assertSame('^XA^TOR:LOGO*.GRF,B:NEW*.GRF', $output);
    }

    public function testTransferObjectValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->transferObject(
                sourceDevice: StorageDevice::Ram,
                destinationDevice: StorageDevice::MemoryCardB,
                sourceName: '',
            );
            self::fail('Expected StringLengthOutOfRangeException');
        } catch (StringLengthOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testUploadGraphicsEmitsHy(): void
    {
        $output = (string) ZplBuilder::start()->uploadGraphics('SAMPLE', StorageDevice::Flash, 'PNG');

        self::assertSame('^XA^HYE:SAMPLE.PNG', $output);
    }

    public function testUploadGraphicsUsesRamAndGrfDefaults(): void
    {
        $output = (string) ZplBuilder::start()->uploadGraphics('LOGO');

        self::assertSame('^XA^HYR:LOGO.GRF', $output);
    }

    public function testWebAuthTimeoutEmitsNwWithDefault(): void
    {
        $output = (string) ZplBuilder::start()->webAuthTimeout();

        self::assertSame('^XA^NW5', $output);
    }

    public function testWebAuthTimeoutValidationFailureLeavesNoCommandAppended(): void
    {
        $builder = ZplBuilder::start();
        $before = (string) $builder;

        try {
            $builder->webAuthTimeout(256);
            self::fail('Expected IntegerValueOutOfRangeException');
        } catch (IntegerValueOutOfRangeException) {
        }

        self::assertSame($before, (string) $builder);
    }

    public function testWhenAppliesCallbackWhenPredicateIsTrue(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(true, fn (ZplBuilder $builder) => $builder->raw('YES'));

        self::assertSame('^XAYES', $output);
    }

    public function testWhenAppliesElseCallbackWhenPredicateIsFalse(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(
                false,
                fn (ZplBuilder $builder) => $builder->raw('YES'),
                fn (ZplBuilder $builder) => $builder->raw('NO'),
            );

        self::assertSame('^XANO', $output);
    }

    public function testWhenInvokesCallablePredicateAndAppliesCallbackWhenItReturnsTrue(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(fn (): bool => true, fn (ZplBuilder $builder) => $builder->raw('YES'));

        self::assertSame('^XAYES', $output);
    }

    public function testWhenInvokesCallablePredicateAndSkipsCallbackWhenItReturnsFalse(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(fn (): bool => false, fn (ZplBuilder $builder) => $builder->raw('YES'));

        self::assertSame('^XA', $output);
    }

    public function testWhenSkipsCallbackWhenPredicateIsFalse(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(false, fn (ZplBuilder $builder) => $builder->raw('YES'));

        self::assertSame('^XA', $output);
    }

    public function testWhenSkipsElseCallbackWhenPredicateIsTrue(): void
    {
        $output = (string) ZplBuilder::start()
            ->when(
                true,
                fn (ZplBuilder $builder) => $builder->raw('YES'),
                fn (ZplBuilder $builder) => $builder->raw('NO'),
            );

        self::assertSame('^XAYES', $output);
    }

    public function testWiredNetworkSettingsEmitsNs(): void
    {
        $output = (string) ZplBuilder::start()->wiredNetworkSettings(
            IpResolution::Permanent,
            '192.168.0.1',
            '255.255.255.0',
            '192.168.0.2',
        );

        self::assertSame('^XA^NSP,192.168.0.1,255.255.255.0,192.168.0.2', $output);
    }

    public function testZplBuilderImplementsZplBuilderInterface(): void
    {
        self::assertInstanceOf(ZplBuilderInterface::class, ZplBuilder::start());
    }

    public function testZplBuilderInterfaceDeclaresEveryPublicInstanceMethod(): void
    {
        $builder = new ReflectionClass(ZplBuilder::class);
        $interface = new ReflectionClass(ZplBuilderInterface::class);

        $missing = [];
        foreach ($builder->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic()) {
                continue;
            }

            if (!$interface->hasMethod($method->getName())) {
                $missing[] = $method->getName();
            }
        }

        self::assertSame([], $missing, sprintf(
            'ZplBuilderInterface must declare every public instance method of ZplBuilder; missing: %s',
            implode(', ', $missing),
        ));
    }
}
