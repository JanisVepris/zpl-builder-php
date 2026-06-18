<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Enum\Antenna;
use Janisvepris\ZplBuilder\Enum\ApplicatorSignal;
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
use Janisvepris\ZplBuilder\Enum\DirectoryDevice;
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
use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidDataOrder;
use Janisvepris\ZplBuilder\Enum\RfidErrorHandling;
use Janisvepris\ZplBuilder\Enum\RfidLockStyle;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RfidOperation;
use Janisvepris\ZplBuilder\Enum\RfidPasswordMemoryBank;
use Janisvepris\ZplBuilder\Enum\RfidPowerLevel;
use Janisvepris\ZplBuilder\Enum\RfidReadWriteFormat;
use Janisvepris\ZplBuilder\Enum\RfidWriteProtect;
use Janisvepris\ZplBuilder\Enum\RssSymbologyType;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Enum\WepAuthenticationType;
use Janisvepris\ZplBuilder\Enum\WepEncryptionMode;
use Janisvepris\ZplBuilder\Enum\WepKeyStorage;
use Janisvepris\ZplBuilder\Enum\WiredPrintServerCheck;
use Janisvepris\ZplBuilder\Enum\ZplMode;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use Janisvepris\ZplBuilder\ValueObject\AztecErrorControl;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplCommand as Commands;

class ZplBuilder implements ZplBuilderInterface
{
    private BarcodeDefaultSettings $barcodeDefaultSettings;

    /** @var Commands[] */
    private array $commands = [];

    /** @var array<string, FontPreset> */
    private array $fontPresets = [];

    /** @var FontSettings[] */
    private array $fontSettings = [];

    private ?string $pendingHexIndicator = null;

    private bool $printNewlines = false;

    /**
     * Protected — use the `start()` static factory to create a builder for normal use,
     * or call `parent::__construct()` from a subclass.
     */
    protected function __construct()
    {
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
    }

    /** Render the accumulated commands as a ZPL string (alias for `render()`). */
    public function __toString(): string
    {
        return $this->render();
    }

    public function abortDownloadGraphic(): self
    {
        return $this->addCommand(new Commands\AbortDownloadGraphic());
    }

    public function addFontPreset(
        string $name,
        Font $font,
        ?int $height = null,
        ?int $width = null,
    ): self {
        $settings = $this->fontSettingsFor($font);

        $this->fontPresets[$name] = new FontPreset(
            font: $font,
            height: $height ?? $settings->height(),
            width: $width ?? $settings->width(),
        );

        return $this;
    }

    public function applicatorReprint(): self
    {
        return $this->addCommand(new Commands\ApplicatorReprint());
    }

    public function applyFontPreset(string $name): self
    {
        if (!isset($this->fontPresets[$name])) {
            throw new FontPresetDoesNotExistException($name);
        }

        $preset = $this->fontPresets[$name];

        $this->changeFont(
            font: $preset->font,
            height: $preset->height,
            width: $preset->width,
        );

        return $this;
    }

    public function barcodeAztec(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $magnification = 1,
        bool $extendedChannelInterpretation = false,
        ?AztecErrorControl $errorControl = null,
        bool $menuSymbol = false,
        int $symbolCount = 1,
        string $structuredAppendId = '',
    ): self {
        $this->addCommand(
            new Commands\BarcodeAztec(
                orientation: $orientation,
                magnification: $magnification,
                extendedChannelInterpretation: $extendedChannelInterpretation,
                errorControl: $errorControl ?? AztecErrorControl::defaultLevel(),
                menuSymbol: $menuSymbol,
                symbolCount: $symbolCount,
                structuredAppendId: $structuredAppendId,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCodabar(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        CodabarCharacter $startCharacter = CodabarCharacter::A,
        CodabarCharacter $stopCharacter = CodabarCharacter::A,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCodabar(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                startCharacter: $startCharacter,
                stopCharacter: $stopCharacter,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCodablock(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $rowHeight = 8,
        bool $security = true,
        ?int $charactersPerRow = null,
        ?int $rows = null,
        CodablockMode $mode = CodablockMode::ModeF,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCodablock(
                orientation: $orientation,
                rowHeight: $rowHeight,
                security: $security,
                charactersPerRow: $charactersPerRow,
                rows: $rows,
                mode: $mode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCode11(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $checkDigit = false,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode11(
                orientation: $orientation,
                checkDigit: $checkDigit,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCode128(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $useUccCheckDigit = false,
        Code128Mode $mode = Code128Mode::None,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode128(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                useUccCheckDigit: $useUccCheckDigit,
                mode: $mode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCode39(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $checkDigit = false,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode39(
                orientation: $orientation,
                checkDigit: $checkDigit,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCode49(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        Code49InterpretationLine $interpretationLine = Code49InterpretationLine::None,
        Code49Mode $mode = Code49Mode::Automatic,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode49(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                interpretationLine: $interpretationLine,
                mode: $mode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeCode93(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeCode93(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                printCheckDigit: $printCheckDigit,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeDataMatrix(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $moduleHeight = 0,
        DataMatrixQuality $quality = DataMatrixQuality::Ecc200,
        ?int $columns = null,
        ?int $rows = null,
        ?int $formatId = null,
        ?string $escapeChar = null,
    ): self {
        $this->addCommand(
            new Commands\BarcodeDataMatrix(
                orientation: $orientation,
                moduleHeight: $moduleHeight,
                quality: $quality,
                columns: $columns,
                rows: $rows,
                formatId: $formatId,
                escapeChar: $escapeChar,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeDefaults(
        int $moduleWidth = 2,
        float $wideToNarrowRatio = 3.0,
        int $height = 10,
    ): self {
        $settings = new BarcodeDefaultSettings($moduleWidth, $wideToNarrowRatio, $height);
        $this->barcodeDefaultSettings = $settings;

        return $this->addCommand(
            new Commands\BarcodeDefaults(
                moduleWidth: $settings->moduleWidth(),
                wideToNarrowRatio: $settings->wideToNarrowRatio(),
                height: $settings->height(),
            ),
        );
    }

    public function barcodeEan13(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeEan13(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeEan8(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeEan8(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeIndustrial2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeIndustrial2of5(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeInterleaved2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $checkDigit = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeInterleaved2of5(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                checkDigit: $checkDigit,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeLogmars(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeLogmars(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeMaxiCode(
        string $data,
        MaxiCodeMode $mode = MaxiCodeMode::StructuredCarrierNumeric,
        int $symbolNumber = 1,
        int $totalSymbols = 1,
    ): self {
        $this->addCommand(
            new Commands\BarcodeMaxiCode(
                mode: $mode,
                symbolNumber: $symbolNumber,
                totalSymbols: $totalSymbols,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeMicroPdf417(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        int $mode = 0,
    ): self {
        $this->addCommand(
            new Commands\BarcodeMicroPdf417(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                mode: $mode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeMsi(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        MsiCheckDigit $checkDigit = MsiCheckDigit::OneMod10,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $insertCheckDigitInInterpretation = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeMsi(
                orientation: $orientation,
                checkDigit: $checkDigit,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                insertCheckDigitInInterpretation: $insertCheckDigitInInterpretation,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodePdf417(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        int $securityLevel = 0,
        ?int $columns = null,
        ?int $rows = null,
        bool $truncate = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodePdf417(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                securityLevel: $securityLevel,
                columns: $columns,
                rows: $rows,
                truncate: $truncate,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodePlanetCode(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = false,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodePlanetCode(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodePlessey(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        bool $printCheckDigit = false,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodePlessey(
                orientation: $orientation,
                printCheckDigit: $printCheckDigit,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodePostnet(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = false,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodePostnet(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeQrCode(
        string $data,
        QrModel $model = QrModel::Model2,
        int $magnification = 1,
        ?QrErrorCorrection $errorCorrection = null,
        ?int $maskValue = null,
    ): self {
        $this->addCommand(
            new Commands\BarcodeQrCode(
                model: $model,
                magnification: $magnification,
                errorCorrection: $errorCorrection,
                maskValue: $maskValue,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeRss(
        string $data,
        Orientation $orientation = Orientation::Rotate90,
        RssSymbologyType $symbologyType = RssSymbologyType::Rss14,
        int $magnification = 1,
        int $separatorHeight = 1,
        int $barcodeHeight = 25,
        int $segmentWidth = 22,
    ): self {
        $this->addCommand(
            new Commands\BarcodeRss(
                orientation: $orientation,
                symbologyType: $symbologyType,
                magnification: $magnification,
                separatorHeight: $separatorHeight,
                barcodeHeight: $barcodeHeight,
                segmentWidth: $segmentWidth,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeStandard2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self {
        $this->addCommand(
            new Commands\BarcodeStandard2of5(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeTlc39(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $code39Width = 2,
        float $wideToNarrowRatio = 2.0,
        int $code39Height = 40,
        int $microPdfWidth = 2,
        int $microPdfRowHeight = 4,
    ): self {
        $this->addCommand(
            new Commands\BarcodeTlc39(
                orientation: $orientation,
                code39Width: $code39Width,
                wideToNarrowRatio: $wideToNarrowRatio,
                code39Height: $code39Height,
                microPdfWidth: $microPdfWidth,
                microPdfRowHeight: $microPdfRowHeight,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeUpcA(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = true,
    ): self {
        $this->addCommand(
            new Commands\BarcodeUpcA(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                printCheckDigit: $printCheckDigit,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeUpcE(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = true,
    ): self {
        $this->addCommand(
            new Commands\BarcodeUpcE(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
                printCheckDigit: $printCheckDigit,
            ),
        );

        return $this->fieldData($data);
    }

    public function barcodeUpcEanExtensions(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = true,
    ): self {
        $this->addCommand(
            new Commands\BarcodeUpcEanExtensions(
                orientation: $orientation,
                height: $height ?? $this->barcodeDefaultSettings->height(),
                printInterpretation: $printInterpretation,
                interpretationAboveCode: $printInterpretationAboveCode,
            ),
        );

        return $this->fieldData($data);
    }

    public function cacheOn(
        bool $enabled = true,
        int $additionalMemory = 40,
        CacheType $type = CacheType::Normal,
    ): self {
        return $this->addCommand(
            new Commands\CacheOn(
                enabled: $enabled,
                additionalMemory: $additionalMemory,
                type: $type,
            ),
        );
    }

    public function calibrateRfidTransponder(string $startString = 'start', string $endString = 'end'): self
    {
        return $this->addCommand(
            new Commands\CalibrateRfidTransponder(
                startString: $startString,
                endString: $endString,
            ),
        );
    }

    public function changeFont(Font $font, ?int $height = null, ?int $width = null): self
    {
        $current = $this->fontSettingsFor($font);
        $settings = new FontSettings(
            $height ?? $current->height(),
            $width ?? $current->width(),
        );
        $this->fontSettings[$font->value] = $settings;

        return $this->addCommand(
            new Commands\ChangeFont($font, $settings->height(), $settings->width()),
        );
    }

    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self
    {
        return $this->addCommand(
            new Commands\ChangeInternationalEncoding($encoding, ...$characterRemaps),
        );
    }

    public function changeMemoryLetters(
        MemoryLetter $aliasForB = MemoryLetter::MemoryCardB,
        MemoryLetter $aliasForE = MemoryLetter::Flash,
        MemoryLetter $aliasForR = MemoryLetter::Ram,
        MemoryLetter $aliasForA = MemoryLetter::MemoryCardA,
    ): self {
        return $this->addCommand(
            new Commands\ChangeMemoryLetters(
                aliasForB: $aliasForB,
                aliasForE: $aliasForE,
                aliasForR: $aliasForR,
                aliasForA: $aliasForA,
            ),
        );
    }

    public function codeValidation(bool $enabled = true): self
    {
        return $this->addCommand(new Commands\CodeValidation($enabled));
    }

    public function comment(string $text): self
    {
        return $this->addCommand(new Commands\FieldComment($text));
    }

    public function defineEpcDataStructure(int $totalBitSize = 96, int ...$partitionSizes): self
    {
        return $this->addCommand(
            new Commands\DefineEpcDataStructure($totalBitSize, ...$partitionSizes),
        );
    }

    public function detectMultipleRfidTags(bool $enabled = true): self
    {
        return $this->addCommand(new Commands\DetectMultipleRfidTags($enabled));
    }

    public function downloadFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self {
        return $this->addCommand(
            new Commands\DownloadFormat(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function downloadGraphics(
        string $name,
        int $totalBytes,
        int $bytesPerRow,
        string $data,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        return $this->addCommand(
            new Commands\DownloadGraphics(
                device: $device,
                name: $name,
                extension: $extension,
                totalBytes: $totalBytes,
                bytesPerRow: $bytesPerRow,
                data: $data,
            ),
        );
    }

    public function downloadObject(
        string $name,
        DownloadFormat $format,
        int $totalBytes,
        DownloadExtension $extension = DownloadExtension::Grf,
        int $bytesPerRow = 0,
        string $data = '',
        StorageDevice $device = StorageDevice::Ram,
    ): self {
        return $this->addCommand(
            new Commands\DownloadObject(
                device: $device,
                name: $name,
                format: $format,
                extension: $extension,
                totalBytes: $totalBytes,
                bytesPerRow: $bytesPerRow,
                data: $data,
            ),
        );
    }

    public function enableEasBit(bool $enabled = true, int $retries = 0): self
    {
        return $this->addCommand(
            new Commands\EnableEasBit(
                enabled: $enabled,
                retries: $retries,
            ),
        );
    }

    public function enableRfidMotion(bool $enabled = true): self
    {
        return $this->addCommand(new Commands\EnableRfidMotion($enabled));
    }

    public function encodeAfiOrDsfidByte(
        int $retries = 0,
        RfidMotion $motion = RfidMotion::Feed,
        RfidWriteProtect $writeProtect = RfidWriteProtect::NotProtected,
        RfidByteFormat $format = RfidByteFormat::Ascii,
        RfidByteType $byteType = RfidByteType::Afi,
    ): self {
        return $this->addCommand(
            new Commands\EncodeAfiOrDsfidByte(
                retries: $retries,
                motion: $motion,
                writeProtect: $writeProtect,
                format: $format,
                byteType: $byteType,
            ),
        );
    }

    public function end(): self
    {
        return $this->addCommand(new Commands\EndFormat());
    }

    public function eraseDownloadGraphics(): self
    {
        return $this->addCommand(new Commands\EraseDownloadGraphics());
    }

    public function fieldBlock(
        int $width = 0,
        int $maxLines = 1,
        int $lineSpacing = 0,
        Justify $justify = Justify::Left,
        int $hangingIndent = 0,
    ): self {
        return $this->addCommand(
            new Commands\FieldBlock(
                width: $width,
                maxLines: $maxLines,
                lineSpacing: $lineSpacing,
                justify: $justify,
                hangingIndent: $hangingIndent,
            ),
        );
    }

    public function fieldClock(
        string $primary = '%',
        ?string $secondary = null,
        ?string $tertiary = null,
    ): self {
        return $this->addCommand(
            new Commands\FieldClock(
                primary: $primary,
                secondary: $secondary,
                tertiary: $tertiary,
            ),
        );
    }

    public function fieldData(string $data): self
    {
        return $this->appendField(
            $data,
            static fn (string $escaped): Commands => new Commands\FieldData($escaped),
        );
    }

    public function fieldHexIndicator(string $indicator = '_'): self
    {
        $this->pendingHexIndicator = $indicator;

        return $this->addCommand(new Commands\FieldHexIndicator($indicator));
    }

    public function fieldNumber(int $number): self
    {
        return $this->addCommand(new Commands\FieldNumber($number));
    }

    public function fieldOrientation(Orientation $orientation): self
    {
        return $this->addCommand(new Commands\FieldOrientation($orientation));
    }

    public function fieldOrigin(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldOrigin($x, $y));
    }

    public function fieldOrigins(FieldOriginLocation ...$locations): self
    {
        if ($locations === []) {
            return $this;
        }

        return $this->addCommand(new Commands\MultipleFieldOrigin(...$locations));
    }

    public function fieldParameter(
        PrintDirection $direction = PrintDirection::Horizontal,
        int $gap = 0,
    ): self {
        return $this->addCommand(
            new Commands\FieldParameter(
                direction: $direction,
                gap: $gap,
            ),
        );
    }

    public function fieldReversePrint(): self
    {
        return $this->addCommand(new Commands\FieldReversePrint());
    }

    public function fieldTypeset(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldTypeset($x, $y));
    }

    public function fieldVariable(string $data): self
    {
        return $this->appendField(
            $data,
            static fn (string $escaped): Commands => new Commands\FieldVariable($escaped),
        );
    }

    public function font(
        Font $font,
        Orientation $orientation = Orientation::Rotate0,
        int $height = Commands\ScalableBitmappedFont::MIN_DIMENSION,
        int $width = Commands\ScalableBitmappedFont::MIN_DIMENSION,
    ): self {
        return $this->addCommand(
            new Commands\ScalableBitmappedFont(
                font: $font,
                orientation: $orientation,
                height: $height,
                width: $width,
            ),
        );
    }

    public function fontByName(
        string $name,
        int $height,
        int $width,
        FontExtension $extension = FontExtension::Font,
        StorageDevice $device = StorageDevice::Ram,
        Orientation $orientation = Orientation::Rotate0,
    ): self {
        return $this->addCommand(
            new Commands\FontName(
                orientation: $orientation,
                height: $height,
                width: $width,
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function fontIdentifier(
        Font $font,
        string $name,
        FontExtension $extension = FontExtension::Font,
        StorageDevice $device = StorageDevice::Ram,
    ): self {
        return $this->addCommand(
            new Commands\FontIdentifier(
                font: $font,
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getFontPresets(): array
    {
        return $this->fontPresets;
    }

    public function getRfidTagId(
        int $fieldNumber = 0,
        RfidDataOrder $dataOrder = RfidDataOrder::Normal,
        int $retries = 0,
        RfidMotion $motion = RfidMotion::Feed,
    ): self {
        return $this->addCommand(
            new Commands\GetRfidTagId(
                fieldNumber: $fieldNumber,
                dataOrder: $dataOrder,
                retries: $retries,
                motion: $motion,
            ),
        );
    }

    public function graphicBox(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
        int $rounding = 0,
    ): self {
        $this->addCommand(
            new Commands\GraphicBox(
                width: $width,
                height: $height,
                thickness: $thickness,
                color: $color,
                rounding: $rounding,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function graphicCircle(
        int $diameter,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
    ): self {
        $this->addCommand(
            new Commands\GraphicCircle(
                diameter: $diameter,
                thickness: $thickness,
                color: $color,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function graphicDiagonalLine(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
        DiagonalOrientation $orientation = DiagonalOrientation::RightLeaning,
    ): self {
        $this->addCommand(
            new Commands\GraphicDiagonalLine(
                width: $width,
                height: $height,
                thickness: $thickness,
                color: $color,
                orientation: $orientation,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function graphicEllipse(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
    ): self {
        $this->addCommand(
            new Commands\GraphicEllipse(
                width: $width,
                height: $height,
                thickness: $thickness,
                color: $color,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function graphicField(
        int $byteCount,
        int $fieldCount,
        int $bytesPerRow,
        string $data,
        GraphicFieldCompression $compression = GraphicFieldCompression::AsciiHex,
    ): self {
        $this->addCommand(
            new Commands\GraphicField(
                compression: $compression,
                byteCount: $byteCount,
                fieldCount: $fieldCount,
                bytesPerRow: $bytesPerRow,
                data: $data,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function graphicSymbol(
        string $symbol,
        int $height,
        int $width,
        Orientation $orientation = Orientation::Rotate0,
    ): self {
        $this->addCommand(
            new Commands\GraphicSymbol(
                orientation: $orientation,
                height: $height,
                width: $width,
            ),
        );

        return $this->fieldData($symbol);
    }

    public function hasFontPreset(string $name): bool
    {
        return isset($this->fontPresets[$name]);
    }

    public function headColdWarning(bool $enabled = true): self
    {
        return $this->addCommand(new Commands\HeadColdWarning($enabled));
    }

    public function hostFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self {
        return $this->addCommand(
            new Commands\HostFormat(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function hostGraphic(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        return $this->addCommand(
            new Commands\HostGraphic(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function imageLoad(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        $this->addCommand(
            new Commands\ImageLoad(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function imageMove(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        $this->addCommand(
            new Commands\ImageMove(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function imageSave(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
        bool $printAfterStore = true,
    ): self {
        $this->addCommand(
            new Commands\ImageSave(
                device: $device,
                name: $name,
                extension: $extension,
                printAfterStore: $printAfterStore,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function labelHome(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\LabelHome($x, $y));
    }

    public function labelLength(int $length): self
    {
        return $this->addCommand(new Commands\LabelLength($length));
    }

    public function labelReversePrint(bool $reversePrint = true): self
    {
        return $this->addCommand(new Commands\LabelReversePrint($reversePrint));
    }

    public function labelShift(int $shift = 0): self
    {
        return $this->addCommand(new Commands\LabelShift($shift));
    }

    public function labelTop(int $dotRows): self
    {
        return $this->addCommand(new Commands\LabelTop($dotRows));
    }

    public function mapClear(bool $clear = true): self
    {
        return $this->addCommand(new Commands\MapClear($clear));
    }

    public function maximumLabelLength(int $dotRows): self
    {
        return $this->addCommand(new Commands\MaximumLabelLength($dotRows));
    }

    public function mediaDarkness(int $level): self
    {
        return $this->addCommand(new Commands\MediaDarkness($level));
    }

    public function mediaFeed(MediaFeedAction $powerUp, MediaFeedAction $headClose): self
    {
        return $this->addCommand(
            new Commands\MediaFeed(
                powerUp: $powerUp,
                headClose: $headClose,
            ),
        );
    }

    public function mediaTracking(MediaTrackingType $tracking): self
    {
        return $this->addCommand(new Commands\MediaTracking($tracking));
    }

    public function mediaType(PrintMethod $method): self
    {
        return $this->addCommand(new Commands\MediaType($method));
    }

    public function modeProtection(ProtectedMode $mode): self
    {
        return $this->addCommand(new Commands\ModeProtection($mode));
    }

    public function networkId(int $networkId): self
    {
        return $this->addCommand(new Commands\NetworkId($networkId));
    }

    public function objectDelete(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        $this->addCommand(
            new Commands\ObjectDelete(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function primaryDevice(NetworkDevice $device = NetworkDevice::Printer): self
    {
        return $this->addCommand(new Commands\PrimaryDevice($device));
    }

    public function printDirectoryLabel(
        DirectoryDevice $device = DirectoryDevice::Ram,
        string $name = '*',
        string $extension = '*',
    ): self {
        return $this->addCommand(
            new Commands\PrintDirectoryLabel(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function printerSleep(int $idleSeconds = 0, bool $shutdownWithLabelsQueued = false): self
    {
        return $this->addCommand(
            new Commands\PrinterSleep(
                idleSeconds: $idleSeconds,
                shutdownWithLabelsQueued: $shutdownWithLabelsQueued,
            ),
        );
    }

    public function printMirror(bool $mirror = true): self
    {
        return $this->addCommand(new Commands\PrintMirror($mirror));
    }

    public function printMode(PostPrintAction $mode = PostPrintAction::TearOff, bool $prepeel = true): self
    {
        return $this->addCommand(
            new Commands\PrintMode(
                mode: $mode,
                prepeel: $prepeel,
            ),
        );
    }

    public function printNewlines(bool $toggle = true): self
    {
        $this->printNewlines = $toggle;

        return $this;
    }

    public function printOrientation(LabelFlip $orientation): self
    {
        return $this->addCommand(new Commands\PrintOrientation($orientation));
    }

    public function printQuantity(int $quantity): self
    {
        return $this->addCommand(new Commands\PrintQuantity($quantity));
    }

    public function printRate(
        PrintSpeed $print = PrintSpeed::Ips2,
        PrintSpeed $slew = PrintSpeed::Ips6,
        PrintSpeed $backfeed = PrintSpeed::Ips2,
    ): self {
        return $this->addCommand(
            new Commands\PrintRate(
                print: $print,
                slew: $slew,
                backfeed: $backfeed,
            ),
        );
    }

    public function printStart(): self
    {
        return $this->addCommand(new Commands\PrintStart());
    }

    public function printWidth(int $width): self
    {
        return $this->addCommand(new Commands\PrintWidth($width));
    }

    public function raw(string $zpl): self
    {
        if ($zpl === '') {
            return $this;
        }

        return $this->addCommand(new Commands\RawCommand($zpl));
    }

    public function readAfiOrDsfidByte(
        int $fieldNumber = 0,
        RfidByteFormat $format = RfidByteFormat::Ascii,
        int $retries = 0,
        RfidMotion $motion = RfidMotion::Feed,
        RfidByteType $byteType = RfidByteType::Afi,
    ): self {
        return $this->addCommand(
            new Commands\ReadAfiOrDsfidByte(
                fieldNumber: $fieldNumber,
                format: $format,
                retries: $retries,
                motion: $motion,
                byteType: $byteType,
            ),
        );
    }

    public function readRfidTag(
        int $fieldNumber = 0,
        int $startingBlock = 0,
        int $numberOfBlocks = 1,
        RfidByteFormat $format = RfidByteFormat::Ascii,
        int $retries = 0,
        RfidMotion $motion = RfidMotion::Feed,
        RfidDataOrder $specialMode = RfidDataOrder::Normal,
    ): self {
        return $this->addCommand(
            new Commands\ReadRfidTag(
                fieldNumber: $fieldNumber,
                startingBlock: $startingBlock,
                numberOfBlocks: $numberOfBlocks,
                format: $format,
                retries: $retries,
                motion: $motion,
                specialMode: $specialMode,
            ),
        );
    }

    public function readWriteRfidFormat(
        RfidOperation $operation = RfidOperation::Write,
        RfidReadWriteFormat $format = RfidReadWriteFormat::Hexadecimal,
        ?int $startingBlock = null,
        ?int $numberOfBytes = null,
    ): self {
        return $this->addCommand(
            new Commands\ReadWriteRfidFormat(
                operation: $operation,
                format: $format,
                startingBlock: $startingBlock,
                numberOfBytes: $numberOfBytes,
            ),
        );
    }

    public function recallFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self {
        return $this->addCommand(
            new Commands\RecallFormat(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function recallGraphic(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
        int $magnificationX = 1,
        int $magnificationY = 1,
    ): self {
        $this->addCommand(
            new Commands\RecallGraphic(
                device: $device,
                name: $name,
                extension: $extension,
                magnificationX: $magnificationX,
                magnificationY: $magnificationY,
            ),
        );

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function removeFontPreset(string $name): self
    {
        if (!isset($this->fontPresets[$name])) {
            throw new FontPresetDoesNotExistException($name);
        }

        unset($this->fontPresets[$name]);

        return $this;
    }

    public function render(): string
    {
        if ($this->commands === []) {
            return '';
        }

        $separator = $this->printNewlines ? PHP_EOL : '';

        return implode($separator, array_map('strval', $this->commands)) . $separator;
    }

    public function reset(): self
    {
        $this->commands = [];
        $this->fontSettings = [];
        $this->barcodeDefaultSettings = new BarcodeDefaultSettings();
        $this->fontPresets = [];
        $this->pendingHexIndicator = null;
        $this->printNewlines = false;
        $this->addCommand(new Commands\StartFormat());

        return $this;
    }

    public function rfidBlockRetries(int $retries): self
    {
        return $this->addCommand(new Commands\RfidBlockRetries($retries));
    }

    public function searchWiredPrintServer(WiredPrintServerCheck $check = WiredPrintServerCheck::Skip): self
    {
        return $this->addCommand(new Commands\SearchWiredPrintServer($check));
    }

    public function selectDateTimeFormat(DateTimeFormat $format): self
    {
        return $this->addCommand(new Commands\SelectDateTimeFormat($format));
    }

    public function selectEncoding(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
    ): self {
        return $this->addCommand(
            new Commands\SelectEncoding(
                device: $device,
                name: $name,
            ),
        );
    }

    public function serializationData(string $startValue, string $increment = '1', bool $leadingZeros = false): self
    {
        if (str_contains($startValue, '^') || str_contains($startValue, '~')) {
            if ($this->pendingHexIndicator === null) {
                $this->fieldHexIndicator();
            }
            $startValue = FieldDataEncoder::escape($startValue, $this->pendingHexIndicator ?? '_');
        }

        $this->addCommand(new Commands\SerializationData($startValue, $increment, $leadingZeros));
        $this->pendingHexIndicator = null;

        return $this->addCommand(new Commands\FieldSeparator());
    }

    public function serializationField(string $startValue, string $mask, string $increment = '1'): self
    {
        return $this->appendField(
            $startValue,
            static fn (string $escaped): Commands => new Commands\FieldData($escaped),
            [new Commands\SerializationField($mask, $increment)],
        );
    }

    public function setAntennaParameters(
        Antenna $receive = Antenna::Diversity,
        Antenna $transmit = Antenna::Diversity,
    ): self {
        return $this->addCommand(
            new Commands\SetAntennaParameters(
                receive: $receive,
                transmit: $transmit,
            ),
        );
    }

    public function setClockMode(
        ClockMode $mode = ClockMode::StartTime,
        ?int $toleranceSeconds = null,
        ?ClockLanguage $language = null,
    ): self {
        return $this->addCommand(
            new Commands\SetClockMode(
                mode: $toleranceSeconds === null ? $mode : null,
                toleranceSeconds: $toleranceSeconds,
                language: $language,
            ),
        );
    }

    public function setDarkness(int $darkness): self
    {
        return $this->addCommand(new Commands\SetDarkness($darkness));
    }

    public function setDateTime(
        ?int $month = null,
        ?int $day = null,
        ?int $year = null,
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ClockTimeFormat $format = ClockTimeFormat::Military24Hour,
    ): self {
        return $this->addCommand(
            new Commands\SetDateTime(
                month: $month ?? (int) date('n'),
                day: $day ?? (int) date('j'),
                year: $year ?? (int) date('Y'),
                hour: $hour ?? (int) date('G'),
                minute: $minute ?? (int) date('i'),
                second: $second ?? (int) date('s'),
                format: $format,
            ),
        );
    }

    public function setMediaSensors(
        int $web,
        int $media,
        int $ribbon,
        int $labelLength,
        ?int $mediaLedIntensity = null,
        ?int $ribbonLedIntensity = null,
        ?int $markSensing = null,
        ?int $markMediaSensing = null,
        ?int $markLedSensing = null,
    ): self {
        return $this->addCommand(
            new Commands\SetMediaSensors(
                web: $web,
                media: $media,
                ribbon: $ribbon,
                labelLength: $labelLength,
                mediaLedIntensity: $mediaLedIntensity,
                ribbonLedIntensity: $ribbonLedIntensity,
                markSensing: $markSensing,
                markMediaSensing: $markMediaSensing,
                markLedSensing: $markLedSensing,
            ),
        );
    }

    public function setOffset(
        ClockSet $clockSet,
        int $monthsOffset = 0,
        int $daysOffset = 0,
        int $yearsOffset = 0,
        int $hoursOffset = 0,
        int $minutesOffset = 0,
        int $secondsOffset = 0,
    ): self {
        return $this->addCommand(
            new Commands\SetOffset(
                clockSet: $clockSet,
                monthsOffset: $monthsOffset,
                daysOffset: $daysOffset,
                yearsOffset: $yearsOffset,
                hoursOffset: $hoursOffset,
                minutesOffset: $minutesOffset,
                secondsOffset: $secondsOffset,
            ),
        );
    }

    public function setRfidPowerLevels(
        RfidPowerLevel $readPower = RfidPowerLevel::High,
        RfidPowerLevel $writePower = RfidPowerLevel::High,
        ?int $antenna = null,
    ): self {
        return $this->addCommand(
            new Commands\SetRfidPowerLevels(
                readPower: $readPower,
                writePower: $writePower,
                antenna: $antenna,
            ),
        );
    }

    public function setRfidTagPassword(
        string $password = '00',
        ?RfidPasswordMemoryBank $memoryBank = null,
        ?RfidLockStyle $lockStyle = null,
    ): self {
        return $this->addCommand(
            new Commands\SetRfidTagPassword(
                password: $password,
                memoryBank: $memoryBank,
                lockStyle: $lockStyle,
            ),
        );
    }

    public function setSmtp(string $serverAddress = '', string $domain = ''): self
    {
        return $this->addCommand(
            new Commands\SetSmtp(
                serverAddress: $serverAddress,
                domain: $domain,
            ),
        );
    }

    public function setSnmp(
        string $systemName = '',
        string $systemContact = '',
        string $systemLocation = '',
        string $getCommunity = 'public',
        string $setCommunity = 'public',
        string $trapCommunity = 'public',
    ): self {
        return $this->addCommand(
            new Commands\SetSnmp(
                systemName: $systemName,
                systemContact: $systemContact,
                systemLocation: $systemLocation,
                getCommunity: $getCommunity,
                setCommunity: $setCommunity,
                trapCommunity: $trapCommunity,
            ),
        );
    }

    public function setUnits(
        MeasurementUnit $unit = MeasurementUnit::Dots,
        ?int $baseDpi = null,
        ?int $conversionDpi = null,
    ): self {
        return $this->addCommand(
            new Commands\SetUnits(
                unit: $unit,
                baseDpi: $baseDpi,
                conversionDpi: $conversionDpi,
            ),
        );
    }

    public function setUpRfidParameters(
        ?int $tagType = null,
        ?int $position = null,
        ?int $voidLength = null,
        ?int $numberOfLabels = null,
        ?RfidErrorHandling $errorHandling = null,
        ?ApplicatorSignal $applicatorSignal = null,
        ?PrintSpeed $voidPrintSpeed = null,
    ): self {
        return $this->addCommand(
            new Commands\SetUpRfidParameters(
                tagType: $tagType,
                position: $position,
                voidLength: $voidLength,
                numberOfLabels: $numberOfLabels,
                errorHandling: $errorHandling,
                applicatorSignal: $applicatorSignal,
                voidPrintSpeed: $voidPrintSpeed,
            ),
        );
    }

    public function setWepMode(
        WepEncryptionMode $mode = WepEncryptionMode::Off,
        ?int $index = null,
        ?WepAuthenticationType $authentication = null,
        ?WepKeyStorage $keyStorage = null,
        ?string $key1 = null,
        ?string $key2 = null,
        ?string $key3 = null,
        ?string $key4 = null,
    ): self {
        return $this->addCommand(
            new Commands\SetWepMode(
                mode: $mode,
                index: $index,
                authentication: $authentication,
                keyStorage: $keyStorage,
                key1: $key1,
                key2: $key2,
                key3: $key3,
                key4: $key4,
            ),
        );
    }

    public function setZpl(ZplMode $mode = ZplMode::ZplII): self
    {
        return $this->addCommand(new Commands\SetZpl($mode));
    }

    public function slew(int $dotRows): self
    {
        return $this->addCommand(new Commands\Slew($dotRows));
    }

    /** Open a new ZPL format. Returns a builder with `^XA` already appended. */
    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
    }

    public function startPrint(int $dotRow): self
    {
        return $this->addCommand(new Commands\StartPrint($dotRow));
    }

    public function suppressBackfeed(): self
    {
        return $this->addCommand(new Commands\SuppressBackfeed());
    }

    public function tearOffAdjust(int $dotRows): self
    {
        return $this->addCommand(new Commands\TearOffAdjust($dotRows));
    }

    public function transferObject(
        StorageDevice $sourceDevice,
        StorageDevice $destinationDevice,
        string $sourceName = '*',
        string $sourceExtension = '*',
        string $destinationName = '*',
        string $destinationExtension = '*',
    ): self {
        return $this->addCommand(
            new Commands\TransferObject(
                sourceDevice: $sourceDevice,
                sourceName: $sourceName,
                sourceExtension: $sourceExtension,
                destinationDevice: $destinationDevice,
                destinationName: $destinationName,
                destinationExtension: $destinationExtension,
            ),
        );
    }

    public function uploadGraphics(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self {
        return $this->addCommand(
            new Commands\UploadGraphics(
                device: $device,
                name: $name,
                extension: $extension,
            ),
        );
    }

    public function webAuthTimeout(int $minutes = 5): self
    {
        return $this->addCommand(new Commands\WebAuthenticationTimeout($minutes));
    }

    public function when(bool|callable $predicate, callable $callback, ?callable $elseCallback = null): self
    {
        if (is_callable($predicate) ? $predicate() : $predicate) {
            $callback($this);
        } elseif ($elseCallback !== null) {
            $elseCallback($this);
        }

        return $this;
    }

    public function wiredNetworkSettings(
        IpResolution $ipResolution,
        string $ipAddress = '',
        string $subnetMask = '',
        string $defaultGateway = '',
        ?string $winsServer = null,
        ?bool $connectionTimeoutChecking = null,
        ?int $timeoutValue = null,
        ?int $arpInterval = null,
        ?int $basePortNumber = null,
    ): self {
        return $this->addCommand(
            new Commands\WiredNetworkSettings(
                ipResolution: $ipResolution,
                ipAddress: $ipAddress,
                subnetMask: $subnetMask,
                defaultGateway: $defaultGateway,
                winsServer: $winsServer,
                connectionTimeoutChecking: $connectionTimeoutChecking,
                timeoutValue: $timeoutValue,
                arpInterval: $arpInterval,
                basePortNumber: $basePortNumber,
            ),
        );
    }

    /**
     * Append a command to the internal list. All public mutation methods route through this,
     * and subclasses can call it to register their own `ZplCommand` implementations.
     */
    protected function addCommand(Commands $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    /** Lazy-allocate and return the `FontSettings` for the given font. */
    protected function fontSettingsFor(Font $font): FontSettings
    {
        return $this->fontSettings[$font->value] ??= new FontSettings();
    }

    /**
     * Shared orchestration for the field-content commands (`^FD`, `^FV`): auto-escape `^` and `~`
     * via `^FH` when present, append the field command built by `$makeField`, optionally append
     * any `$trailing` commands (e.g. `^SF`), then close it with `^FS`.
     *
     * @param callable(string): Commands $makeField
     * @param Commands[]                 $trailing  commands inserted between the field data and `^FS`
     *
     * @throws StringLengthOutOfRangeException
     */
    private function appendField(string $data, callable $makeField, array $trailing = []): self
    {
        if (str_contains($data, '^') || str_contains($data, '~')) {
            if ($this->pendingHexIndicator === null) {
                $this->fieldHexIndicator();
            }
            $data = FieldDataEncoder::escape($data, $this->pendingHexIndicator ?? '_');
        }

        $this->addCommand($makeField($data));
        $this->pendingHexIndicator = null;

        foreach ($trailing as $command) {
            $this->addCommand($command);
        }

        return $this->addCommand(new Commands\FieldSeparator());
    }
}
