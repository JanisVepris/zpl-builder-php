<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

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
use Janisvepris\ZplBuilder\Enum\RfidByteFormat;
use Janisvepris\ZplBuilder\Enum\RfidByteType;
use Janisvepris\ZplBuilder\Enum\RfidMotion;
use Janisvepris\ZplBuilder\Enum\RssSymbologyType;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Enum\WiredPrintServerCheck;
use Janisvepris\ZplBuilder\Enum\ZplMode;
use Janisvepris\ZplBuilder\Exception\ConflictingClockModeException;
use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Exception\UnsupportedFontExtensionException;
use Janisvepris\ZplBuilder\ValueObject\AztecErrorControl;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplCommand as Commands;
use Stringable;

/**
 * The public contract of the fluent ZPL builder facade.
 *
 * Declares every public instance method of {@see ZplBuilder} so consumers can depend on
 * the interface rather than the concrete class. The static `start()` factory is intentionally
 * omitted — it constructs the concrete builder and is not part of the polymorphic contract.
 * Fluent methods return `self` (this interface); the implementing class narrows that to its
 * own type as a covariant override, so chaining a concrete builder keeps the concrete type.
 */
interface ZplBuilderInterface extends Stringable
{
    /**
     * Abort an in-progress graphic download and return the printer to normal print mode (`~DN`).
     */
    public function abortDownloadGraphic(): self;

    /**
     * Register a named font preset that can later be applied via `applyFontPreset()`.
     * Unspecified dimensions inherit from the font's current settings.
     */
    public function addFontPreset(
        string $name,
        Font $font,
        ?int $height = null,
        ?int $width = null,
    ): self;

    /**
     * Reprint the last label, mirroring the applicator's Reprint signal (`~PR`). Supported only on
     * PAX/PAX2-series printers, and only when applicator reprint is enabled via `^JJ`.
     */
    public function applicatorReprint(): self;

    /**
     * Apply a previously registered font preset, emitting `^CF` with its stored dimensions.
     *
     * @throws FontPresetDoesNotExistException
     * @throws IntegerValueOutOfRangeException
     */
    public function applyFontPreset(string $name): self;

    /**
     * Draw an Aztec 2D barcode with the given data (`^B0` + `^FD ... ^FS`).
     *
     * Aztec sizes itself by magnification factor (`1..10`) rather than a `^BY` height. `$errorControl`
     * is an `AztecErrorControl` value object whose named constructors map to the spec's combined
     * error/size field — `defaultLevel()`, `errorCorrectionPercentage()`, `compactSymbol()`,
     * `fullRangeSymbol()`, `rune()` — defaulting to `defaultLevel()` when omitted. `$symbolCount`
     * (`1..26`) and `$structuredAppendId` (≤24 bytes, optional) drive structured append; an empty
     * ID is omitted from the output.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function barcodeAztec(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $magnification = 1,
        bool $extendedChannelInterpretation = false,
        ?AztecErrorControl $errorControl = null,
        bool $menuSymbol = false,
        int $symbolCount = 1,
        string $structuredAppendId = '',
    ): self;

    /**
     * Draw an ANSI Codabar (USD-4 / NW-7 / 2 of 7) barcode with the given data
     * (`^BK` + `^FD ... ^FS`). Falls back to the `^BY` default height when none is
     * provided. `$startCharacter` and `$stopCharacter` are the Codabar control
     * characters (`A`–`D`). The check-digit parameter is fixed to `N` by the spec.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCodabar(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        CodabarCharacter $startCharacter = CodabarCharacter::A,
        CodabarCharacter $stopCharacter = CodabarCharacter::A,
    ): self;

    /**
     * Draw a CODABLOCK stacked barcode with the given data (`^BB` + `^FD ... ^FS`).
     * `$rowHeight` is the per-row bar height (its own default of `8` dots, not `^BY`).
     * `$charactersPerRow` (`2..62`) and `$rows` are optional — omit both for a single
     * row. The `$rows` range depends on `$mode`: `1..22` for `CodablockMode::ModeA`,
     * `2..4` for `ModeE`/`ModeF`. `$security` adds row check-sums (it can only be
     * disabled in `ModeA`; the printer forces it on for `ModeE`/`ModeF`).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCodablock(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $rowHeight = 8,
        bool $security = true,
        ?int $charactersPerRow = null,
        ?int $rows = null,
        CodablockMode $mode = CodablockMode::ModeF,
    ): self;

    /**
     * Draw a Code 11 (USD-8) barcode with the given data (`^B1` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$checkDigit`
     * selects one check digit (`Y`) versus two (`N`).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCode11(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $checkDigit = false,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a Code 128 barcode with the given data (`^BC` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCode128(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $useUccCheckDigit = false,
        Code128Mode $mode = Code128Mode::None,
    ): self;

    /**
     * Draw a Code 39 (USD-3 / 3 of 9) barcode with the given data (`^B3` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$checkDigit`
     * adds a Mod-43 check digit. Code 39 auto-generates the `*` start/stop character.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCode39(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $checkDigit = false,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a Code 49 multi-row barcode with the given data (`^B4` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided — the height is a
     * per-row multiplier of the module width. `$interpretationLine` chooses whether the
     * interpretation line prints and where (`Code49InterpretationLine`); `$mode` selects
     * the starting encoding mode (`Code49Mode`, default automatic).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCode49(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        Code49InterpretationLine $interpretationLine = Code49InterpretationLine::None,
        Code49Mode $mode = Code49Mode::Automatic,
    ): self;

    /**
     * Draw a Code 93 (USS-93) barcode with the given data (`^BA` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$printCheckDigit`
     * adds the two Mod-47 check characters. Code 93 encodes the full 128-character
     * ASCII set via paired substitute characters.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeCode93(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = false,
    ): self;

    /**
     * Draw a Data Matrix 2D barcode with the given data (`^BX` + `^FD ... ^FS`).
     * `$moduleHeight` is the dimension of an individual square element; leaving it `0`
     * (the default) makes the printer derive the element size from the `^BY` symbol
     * height. `$quality` selects the ECC level (`DataMatrixQuality`; `Ecc200` is
     * recommended). `$columns` and `$rows` (`9..49`) optionally force the symbol size,
     * `$formatId` (`1..6`) selects the field-data format for ECC 0–140, and
     * `$escapeChar` overrides the default `~` escape character used with ECC 200. Each
     * optional parameter is omitted from the output when left `null`.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeDataMatrix(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $moduleHeight = 0,
        DataMatrixQuality $quality = DataMatrixQuality::Ecc200,
        ?int $columns = null,
        ?int $rows = null,
        ?int $formatId = null,
        ?string $escapeChar = null,
    ): self;

    /**
     * Set defaults for subsequent barcodes — module width, wide-to-narrow ratio,
     * and bar height (`^BY`).
     *
     * @throws FloatValueOutOfRangeException
     * @throws IntegerValueOutOfRangeException
     */
    public function barcodeDefaults(
        int $moduleWidth = 2,
        float $wideToNarrowRatio = 3.0,
        int $height = 10,
    ): self;

    /**
     * Draw an EAN-13 barcode with the given data (`^BE` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. EAN-13 expects
     * exactly twelve digits; the printer pads or truncates on the left with zeros and
     * appends the Mod-10 check digit.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeEan13(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw an EAN-8 barcode with the given data (`^B8` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. EAN-8 expects
     * exactly seven digits; the printer pads or truncates on the left with zeros.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeEan8(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw an Industrial 2 of 5 barcode with the given data (`^BI` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. Industrial 2 of 5
     * is a discrete, self-checking numeric symbology whose data is carried entirely in
     * the bars (the spaces are fixed-width).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeIndustrial2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw an Interleaved 2 of 5 barcode with the given data (`^B2` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$checkDigit`
     * adds a Mod 10 check digit. The printer pads an odd number of digits with a
     * leading zero, since Interleaved 2 of 5 encodes digit pairs.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeInterleaved2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $checkDigit = false,
    ): self;

    /**
     * Draw a LOGMARS barcode with the given data (`^BL` + `^FD ... ^FS`). LOGMARS is a
     * Code 39 variant used by the U.S. Department of Defense, so it has no
     * print-interpretation-line toggle — only the above/below placement. Falls back to
     * the `^BY` default height when none is provided. Lowercase data is upper-cased by
     * the printer, and a Mod-43 check digit is always added.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeLogmars(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a UPS MaxiCode 2D barcode with the given data (`^BD` + `^FD ... ^FS`).
     * MaxiCode has no interpretation line and ignores `^BY`; it sizes itself by `$mode`.
     * `$symbolNumber` (`1..8`) and `$totalSymbols` (`1..8`) place this symbol within a
     * structured-append sequence. Modes `2`/`3` expect the structured-carrier `^FD`
     * layout (a high-priority message followed by a low-priority message).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeMaxiCode(
        string $data,
        MaxiCodeMode $mode = MaxiCodeMode::StructuredCarrierNumeric,
        int $symbolNumber = 1,
        int $totalSymbols = 1,
    ): self;

    /**
     * Draw a Micro-PDF417 2D stacked barcode with the given data (`^BF` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height (per-row, in dots) when none is provided.
     * `$mode` (`0..33`) selects the fixed row/column/error-correction combination from
     * the spec's Micro-PDF417 mode table; the field data must fit the chosen mode.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeMicroPdf417(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        int $mode = 0,
    ): self;

    /**
     * Draw an MSI (modified Plessey) barcode with the given data (`^BM` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$checkDigit`
     * selects the check-digit scheme (`MsiCheckDigit`), and
     * `$insertCheckDigitInInterpretation` mirrors that check digit into the
     * interpretation line.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeMsi(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        MsiCheckDigit $checkDigit = MsiCheckDigit::OneMod10,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $insertCheckDigitInInterpretation = false,
    ): self;

    /**
     * Draw a PDF417 2D stacked barcode with the given data (`^B7` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height (per-row, in dots) when none is provided.
     * `$securityLevel` (`0..8`) sets error detection/correction — `0` is detection only.
     * `$columns` (`1..30`) and `$rows` (`3..90`) are optional; leave either `null` to let
     * the printer derive it from the 1:2 aspect ratio. For structured-append printing
     * across multiple symbols, position them with `fieldOrigins()` (`^FM`).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodePdf417(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        int $securityLevel = 0,
        ?int $columns = null,
        ?int $rows = null,
        bool $truncate = false,
    ): self;

    /**
     * Draw a Planet Code barcode with the given data (`^B5` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. Accepted characters
     * are the digits `0`–`9`; bar height is capped at `BarcodePlanetCode::MAX_HEIGHT`
     * (9999) dots, narrower than most barcodes.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodePlanetCode(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = false,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a Plessey barcode with the given data (`^BP` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$printCheckDigit`
     * appends the Plessey check digit. Plessey is a pulse-width-modulated, non-self-
     * checking numeric symbology.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodePlessey(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        bool $printCheckDigit = false,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a POSTNET barcode with the given data (`^BZ` + `^FD ... ^FS`). POSTNET
     * automates U.S. mail handling and encodes the digits `0`–`9` as tall/short bars.
     * Falls back to the `^BY` default height when none is provided. The interpretation
     * line defaults to off per the spec.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodePostnet(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = false,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a QR Code 2D barcode with the given data (`^BQ` + `^FD ... ^FS`). QR Code
     * sizes itself by `$magnification` (`1..10`) and ignores `^FW`, so orientation is
     * always normal. `$errorCorrection` and `$maskValue` (`1..7`) are optional command
     * parameters; they are omitted from the output when left `null`. The error-correction
     * level and input mode are normally carried by the `^FD` switches instead (for
     * example `QA,<data>` for automatic input at the high-reliability level), so `$data`
     * is passed through verbatim.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeQrCode(
        string $data,
        QrModel $model = QrModel::Model2,
        int $magnification = 1,
        ?QrErrorCorrection $errorCorrection = null,
        ?int $maskValue = null,
    ): self;

    /**
     * Draw an RSS (Reduced Space Symbology) barcode with the given data
     * (`^BR` + `^FD ... ^FS`). `$symbologyType` selects the family member
     * (`RssSymbologyType`); `$magnification` is `1..10`; `$separatorHeight` is `1` or
     * `2`; `$barcodeHeight` (the linear-portion height, default `25`) only applies to the
     * UCC/EAN composite types; and `$segmentWidth` (`2..22`, even values only, default
     * `22`) applies to RSS Expanded. Note the orientation defaults to `Rotate90` per the
     * spec.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeRss(
        string $data,
        Orientation $orientation = Orientation::Rotate90,
        RssSymbologyType $symbologyType = RssSymbologyType::Rss14,
        int $magnification = 1,
        int $separatorHeight = 1,
        int $barcodeHeight = 25,
        int $segmentWidth = 22,
    ): self;

    /**
     * Draw a Standard 2 of 5 barcode with the given data (`^BJ` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. Standard 2 of 5
     * is a discrete, self-checking numeric symbology whose data is carried entirely in
     * the bars.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeStandard2of5(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
    ): self;

    /**
     * Draw a TLC39 barcode with the given data (`^BT` + `^FD ... ^FS`). TLC39 is the
     * TCIF CLEI standard for telecommunications equipment: a Code 39 ECI number followed
     * by an optional Micro-PDF417 carrying the serial number and additional data.
     * `$code39Width` (`1..10`), `$wideToNarrowRatio` (`2.0..3.0`) and `$code39Height`
     * (`1..9999`) size the Code 39 portion; `$microPdfWidth` (`1..10`) and
     * `$microPdfRowHeight` (`1..255`) size the Micro-PDF417 portion. The defaults match
     * the TCIF-compliant 200/300 dpi values.
     *
     * @throws FloatValueOutOfRangeException
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeTlc39(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        int $code39Width = 2,
        float $wideToNarrowRatio = 2.0,
        int $code39Height = 40,
        int $microPdfWidth = 2,
        int $microPdfRowHeight = 4,
    ): self;

    /**
     * Draw a UPC-A barcode with the given data (`^BU` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$printCheckDigit`
     * (default on) prints the Mod-10 check digit. UPC-A expects exactly eleven digits;
     * the printer pads or truncates on the left with zeros.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeUpcA(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = true,
    ): self;

    /**
     * Draw a UPC-E barcode with the given data (`^B9` + `^FD ... ^FS`).
     * Falls back to the `^BY` default height when none is provided. `$printCheckDigit`
     * (default on) prints the check digit. UPC-E expects exactly ten characters — a
     * five-digit manufacturer code and five-digit product code — and the printer
     * calculates the zero-suppressed form.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeUpcE(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = false,
        bool $printCheckDigit = true,
    ): self;

    /**
     * Draw a UPC/EAN extension barcode with the given data (`^BS` + `^FD ... ^FS`).
     * This is the two- or five-digit add-on used alongside a UPC-A (`^BU`) or UPC-E
     * (`^B9`) symbol. Falls back to the `^BY` default height when none is provided.
     * Field data is exactly two or five digits. Note the interpretation line defaults to
     * printing above the code per the spec.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function barcodeUpcEanExtensions(
        string $data,
        Orientation $orientation = Orientation::Rotate0,
        ?int $height = null,
        bool $printInterpretation = true,
        bool $printInterpretationAboveCode = true,
    ): self;

    /**
     * Resize the printer's scalable-font character cache (`^CO`). `$enabled` toggles the cache,
     * `$additionalMemory` is the amount of memory (in K) to add beyond the default 40K cache, and
     * `$type` selects the buffer (`CacheType::Normal`, or `Internal` for large Asian fonts). Not
     * required on firmware x.12+, where the cache grows automatically.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function cacheOn(
        bool $enabled = true,
        int $additionalMemory = 40,
        CacheType $type = CacheType::Normal,
    ): self;

    /**
     * Initiate an RFID transponder-position calibration for the loaded RFID media (`^HR`),
     * returning a results table to the host. `$startString` and `$endString` (each 1–64 bytes)
     * bracket the table in the host output. Not supported by all printers.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function calibrateRfidTransponder(string $startString = 'start', string $endString = 'end'): self;

    /**
     * Change the default font (`^CF`) and optionally its height and/or width.
     * Unspecified dimensions keep the last value set for that font.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function changeFont(Font $font, ?int $height = null, ?int $width = null): self;

    /** Set the printer's character encoding (`^CI`), with optional character remaps. */
    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self;

    /**
     * Reassign the printer's memory-device letter designations (`^CM`). Each parameter selects which
     * physical device the corresponding default letter (`B:`, `E:`, `R:`, `A:`) points to; pass
     * `MemoryLetter::None` to leave a letter unassigned. The defaults keep every letter pointing at
     * its own device. The printer resets all letters to their defaults if two parameters collide.
     */
    public function changeMemoryLetters(
        MemoryLetter $aliasForB = MemoryLetter::MemoryCardB,
        MemoryLetter $aliasForE = MemoryLetter::Flash,
        MemoryLetter $aliasForR = MemoryLetter::Ram,
        MemoryLetter $aliasForA = MemoryLetter::MemoryCardA,
    ): self;

    /**
     * Toggle bar-code data validation (`^CV`). When enabled, the printer checks each bar code's data
     * against its symbology and prints an `INVALID - X` message in place of any bar code that fails.
     * Remains active across formats until disabled or the printer is turned off.
     */
    public function codeValidation(bool $enabled = true): self;

    /**
     * Insert a non-printing comment into the ZPL output (`^FX`). Useful for debugging.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function comment(string $text): self;

    /**
     * Open a stored-format download so the commands that follow are saved under the given name
     * rather than printed (`^DF`). Pair with `recallFormat()` (`^XF`) to merge the stored format
     * with variable data. Standalone command — it emits only `^DF…`, with no `^FD … ^FS`. The
     * device defaults to `R:` (RAM) and the extension to `ZPL`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function downloadFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self;

    /**
     * Download an ASCII-hex graphic image into a printer storage device (`~DG`). `$totalBytes` and
     * `$bytesPerRow` are the totals the caller computes for the image. Standalone command — no
     * `^FD … ^FS`. A caret or tilde in `$data` is rejected — either would abort the download. The
     * device defaults to `R:` (RAM) and the extension to `GRF`.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function downloadGraphics(
        string $name,
        int $totalBytes,
        int $bytesPerRow,
        string $data,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Download a graphic or native TrueType/OpenType font object into a printer storage device
     * (`~DY`). `$format` selects how `$data` is encoded and `$extension` the stored object type;
     * `$data` may be empty when the file is sent as a separate transmission. Standalone command —
     * no `^FD … ^FS`. A caret or tilde in `$data` is rejected — either would abort the download.
     * The device defaults to `R:` (RAM).
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function downloadObject(
        string $name,
        DownloadFormat $format,
        int $totalBytes,
        DownloadExtension $extension = DownloadExtension::Grf,
        int $bytesPerRow = 0,
        string $data = '',
        StorageDevice $device = StorageDevice::Ram,
    ): self;

    /** Finalise the format by appending `^XZ`. */
    public function end(): self;

    /**
     * Erase all downloaded graphics from the printer's storage (`~EG`).
     */
    public function eraseDownloadGraphics(): self;

    /**
     * Format the next field as a multi-line text block with the given width,
     * maximum line count, line spacing, justification, and hanging indent (`^FB`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldBlock(
        int $width = 0,
        int $maxLines = 1,
        int $lineSpacing = 0,
        Justify $justify = Justify::Left,
        int $hangingIndent = 0,
    ): self;

    /**
     * Set the Real-Time Clock indicators that the next `^FD` will substitute (`^FC`).
     * Secondary and tertiary indicators are optional; tertiary requires secondary.
     *
     * @throws DuplicateClockIndicatorException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     * @throws TertiaryClockIndicatorWithoutSecondaryException
     */
    public function fieldClock(
        string $primary = '%',
        ?string $secondary = null,
        ?string $tertiary = null,
    ): self;

    /**
     * Write text into the current field (`^FD ... ^FS`). Auto-escapes `^` and `~`
     * via `^FH_` if present, since the printer would otherwise treat them as command starts.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function fieldData(string $data): self;

    /**
     * Declare the hex-escape character used by the next `^FD` (`^FH`).
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function fieldHexIndicator(string $indicator = '_'): self;

    /**
     * Tag the next field with a number, for use with stored formats (`^FN`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldNumber(int $number): self;

    /** Set the orientation applied to subsequent fields (`^FW`). */
    public function fieldOrientation(Orientation $orientation): self;

    /**
     * Position the next field at the given (x, y) coordinate in dots (`^FO`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldOrigin(int $x = 0, int $y = 0): self;

    /**
     * Set multiple field origin locations for PDF417 (`^B7`) / MicroPDF417 (`^BF`)
     * structured-append printing (`^FM`). Up to 60 locations; printer ignores `^FM`
     * for other commands. Empty input is a no-op.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldOrigins(FieldOriginLocation ...$locations): self;

    /**
     * Set the print direction and additional inter-character gap for the next field (`^FP`).
     * Used for vertical and reverse text, commonly when printing Asian fonts.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldParameter(
        PrintDirection $direction = PrintDirection::Horizontal,
        int $gap = 0,
    ): self;

    /**
     * Reverse-print the next field — it renders in the inverse of its background (`^FR`).
     * Applies to a single field; for whole-label reverse printing prefer `labelReversePrint()` (`^LR`).
     */
    public function fieldReversePrint(): self;

    /**
     * Position the next field at the given (x, y) coordinate in dots (`^FT`).
     *
     * Like `^FO`, but the typeset origin sits at the baseline of the last line of
     * text, so increasing the font size grows the field upward rather than downward.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldTypeset(int $x = 0, int $y = 0): self;

    /**
     * Write variable text into the current field (`^FV ... ^FS`). Behaves like `fieldData()`,
     * but the printer clears the field after the label prints — pair with `^MC` so high-throughput
     * formats reformat only the fields that change. Auto-escapes `^` and `~` via `^FH_` if present.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function fieldVariable(string $data): self;

    /**
     * Select the font for the next field (`^A`). Unlike `changeFont()` (`^CF`, the default
     * font), this applies to the upcoming `^FD`/`^FV` field only; the printer reverts to the
     * `^CF` default afterwards. Height and width are in dots (scalable fonts: 10 to 32000).
     * Chain `fieldData()` after this to emit the text.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function font(
        Font $font,
        Orientation $orientation = Orientation::Rotate0,
        int $height = Commands\ScalableBitmappedFont::MIN_DIMENSION,
        int $width = Commands\ScalableBitmappedFont::MIN_DIMENSION,
    ): self;

    /**
     * Select a downloaded/resident font by its file name for subsequent fields (`^A@`).
     *
     * Unlike `changeFont()` (`^CF`), which uses the single-character font designator, this
     * references the font by its stored file name and extension. It is a per-field selector —
     * it emits only the `^A@…` command and pairs with a following `^FD … ^FS` of your own.
     * Height and width are in dots; the device defaults to `R:` (RAM) per the spec. Only the
     * `.FNT` and `.TTF` extensions are accepted (`FontName::SUPPORTED_EXTENSIONS`); for `.TTE`
     * assign an identifier with `fontIdentifier()` (`^CW`) and reference it via `changeFont()`/`font()`.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     * @throws UnsupportedFontExtensionException
     */
    public function fontByName(
        string $name,
        int $height,
        int $width,
        FontExtension $extension = FontExtension::Font,
        StorageDevice $device = StorageDevice::Ram,
        Orientation $orientation = Orientation::Rotate0,
    ): self;

    /**
     * Assign a font identifier letter to a downloaded or resident font file (`^CW`).
     *
     * Maps a single-character font designator (the same vocabulary `^CF`/`^A` use) to a stored
     * font file, so subsequent references to that letter print the downloaded font in place of —
     * or, for an unused letter, in addition to — the built-in font. The mapping lasts only until
     * power-off or until the same letter is remapped, so it must be re-sent each print job on
     * volatile devices. Standalone command — it emits only `^CW…`, with no `^FD … ^FS`. The drive
     * defaults to `R:` (RAM) and the extension to `.FNT`, matching `fontByName()`.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function fontIdentifier(
        Font $font,
        string $name,
        FontExtension $extension = FontExtension::Font,
        StorageDevice $device = StorageDevice::Ram,
    ): self;

    /**
     * Return the list of commands accumulated so far. Useful for testing and external rendering.
     *
     * @return Commands[]
     */
    public function getCommands(): array;

    /**
     * Return all currently registered font presets, keyed by name.
     *
     * @return array<string, FontPreset>
     */
    public function getFontPresets(): array;

    /**
     * Draw a rectangle or line of the given width × height with the chosen thickness,
     * color, and corner rounding (`^GB ... ^FS`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function graphicBox(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
        int $rounding = 0,
    ): self;

    /**
     * Draw a circle of the given diameter with the chosen border thickness and color (`^GC ... ^FS`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function graphicCircle(
        int $diameter,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
    ): self;

    /**
     * Draw a straight diagonal line across a bounding box of the given width × height, with the
     * chosen thickness, color, and lean direction (`^GD ... ^FS`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function graphicDiagonalLine(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
        DiagonalOrientation $orientation = DiagonalOrientation::RightLeaning,
    ): self;

    /**
     * Draw an ellipse of the given width × height with the chosen border thickness and color
     * (`^GE ... ^FS`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function graphicEllipse(
        int $width,
        int $height,
        int $thickness = 1,
        LineColor $color = LineColor::Black,
    ): self;

    /**
     * Download a graphic image directly into the printer's bitmap storage at the current field
     * origin (`^GF ... ^FS`). `$byteCount`, `$fieldCount`, and `$bytesPerRow` are the totals the
     * caller computes for the image; `$compression` selects how `$data` is encoded. A caret or
     * tilde in `$data` is rejected — either would abort the printer's download.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function graphicField(
        int $byteCount,
        int $fieldCount,
        int $bytesPerRow,
        string $data,
        GraphicFieldCompression $compression = GraphicFieldCompression::AsciiHex,
    ): self;

    /**
     * Emit a graphic symbol — registered trademark, copyright, and similar marks — at the given
     * height and width and orientation (`^GS` then `^FD<symbol>^FS`). `$symbol` is the field-data
     * character that selects which symbol the printer draws.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function graphicSymbol(
        string $symbol,
        int $height,
        int $width,
        Orientation $orientation = Orientation::Rotate0,
    ): self;

    /** Whether a font preset with the given name has been registered. */
    public function hasFontPreset(string $name): bool;

    /**
     * Enable or disable the head cold warning indicator (`^MW`), used with RS-485 printer
     * communications. A value is required — the printer ignores the command otherwise.
     */
    public function headColdWarning(bool $enabled = true): self;

    /**
     * Send a stored format from the printer back to the host (`^HF`). Standalone command — it emits
     * only `^HF…`, with no `^FD … ^FS`. The device defaults to `R:` (RAM) and the extension to
     * `ZPL`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function hostFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self;

    /**
     * Upload a stored graphic from the printer to the host (`^HG`). Standalone command — it emits
     * only `^HG…`, with no `^FD … ^FS`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function hostGraphic(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Load a stored image at the start of a label format and merge it with the field data that
     * follows (`^IL ... ^FS`). The image is always positioned at the origin (`^FO0,0`). The device
     * defaults to `R:` (RAM) and the extension to `GRF`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function imageLoad(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Move a stored image directly into the bitmap at the current field origin (`^IM ... ^FS`).
     * Like `^XG` but without magnification. The device defaults to `R:` (RAM) and the extension to
     * `GRF`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function imageMove(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Save the current label format as a stored image rather than a ZPL script (`^IS ... ^FS`).
     * `$printAfterStore` (default `true`) also prints the label after saving. The device defaults
     * to `R:` (RAM) and the extension to `GRF` (`PNG` is also accepted).
     *
     * @throws StringLengthOutOfRangeException
     */
    public function imageSave(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
        bool $printAfterStore = true,
    ): self;

    /**
     * Move the label's home origin to the given (x, y) coordinate (`^LH`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelHome(int $x = 0, int $y = 0): self;

    /**
     * Set the label's length in dots (`^LL`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelLength(int $length): self;

    /** Toggle reverse-print — fields render white-on-black instead of black-on-white (`^LR`). */
    public function labelReversePrint(bool $reversePrint = true): self;

    /**
     * Shift all field positions left by the given number of dots for compatibility with Z-130/Z-220
     * formats (`^LS`). Accepts -9999 to 9999 and defaults to 0 (no shift). Must precede the first
     * `^FS` to be honoured by existing Zebra printers.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelShift(int $shift = 0): self;

    /**
     * Move the entire label format up or down relative to the top edge of the label (`^LT`).
     * Accepts -120 to 120 dot rows; a negative value moves the format towards the top edge, a
     * positive value away from it.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelTop(int $dotRows): self;

    /**
     * Retain the current label bitmap after printing instead of clearing it (`^MC`). Pass `false`
     * to keep the default clear-after-print behaviour. Pair with `fieldVariable()` (`^FV`) to build
     * a label template whose static portion carries over to the next label.
     */
    public function mapClear(bool $clear = true): self;

    /**
     * Set the maximum label length in dot rows (`^ML`). Accepts 0 to 32000. For calibration to
     * work, this must be equal to or greater than the actual label length.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function maximumLabelLength(int $dotRows): self;

    /**
     * Adjust the print darkness relative to the printer's current setting (`^MD`). Accepts -30 to
     * 30; each call is applied against the configuration-label value, and the result is clamped to
     * the printer's overall range. A `~SD` value, if set, is added on top of this.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function mediaDarkness(int $level): self;

    /**
     * Set what the printer does to the media at power-up and after the printhead closes (`^MF`).
     * Each action feeds to the first web, runs calibration (`~JC`), detects label length (`~JL`),
     * or does not feed. Both defaults are platform-dependent, so both arguments are required.
     */
    public function mediaFeed(MediaFeedAction $powerUp, MediaFeedAction $headClose): self;

    /**
     * Tell the printer how to track label boundaries (`^MN`): continuous media (length set by
     * `^LL`) or non-continuous media sensed by web or registration mark. A value is required — the
     * printer ignores the command otherwise.
     */
    public function mediaTracking(MediaTrackingType $tracking): self;

    /**
     * Select the printing method for the media in use (`^MT`): thermal transfer (ribbon) or direct
     * thermal (heat-sensitive media, no ribbon). A value is required — the printer ignores the
     * command otherwise.
     */
    public function mediaType(PrintMethod $method): self;

    /**
     * Disable (lock) a control-panel mode function so its setting can no longer be changed (`^MP`).
     * Each call protects one function — darkness, position, calibration, pause, feed, cancel, menu,
     * or all mode-saves — or re-enables every mode (`ProtectedMode::EnableAll`). A value is required.
     */
    public function modeProtection(ProtectedMode $mode): self;

    /**
     * Assign the printer's network ID number for RS-485 communications (`^NI`). Accepts 1 to 999
     * and renders as a three-digit value. Must be set before the printer can be used on the network.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function networkId(int $networkId): self;

    /**
     * Delete objects (graphics, fonts, stored formats) from a printer storage device (`^ID ... ^FS`).
     * The `*` wildcard is accepted in `$name` and `$extension` to delete groups of objects (e.g.
     * `*`/`GRF` deletes every `.GRF` object). The device defaults to `R:` (RAM) and the extension
     * to `GRF`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function objectDelete(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Choose whether the printer uses its own or the print server's LAN/WLAN settings at boot
     * (`^NP`). Defaults to the printer's own settings.
     */
    public function primaryDevice(NetworkDevice $device = NetworkDevice::Printer): self;

    /**
     * Place the printer in idle/shutdown mode after a period of inactivity (`^ZZ`). `$idleSeconds`
     * (0–999999) is the idle time before shutdown — 0 disables automatic shutdown.
     * `$shutdownWithLabelsQueued` shuts down even with labels still queued (`false`, the default,
     * prints them first). Only valid on PA400 and PT400 battery-powered printers.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function printerSleep(int $idleSeconds = 0, bool $shutdownWithLabelsQueued = false): self;

    /**
     * Toggle printing the entire label as a mirror image, flipped left to right (`^PM`). Remains
     * active until disabled (`^PMN`) or the printer is turned off.
     */
    public function printMirror(bool $mirror = true): self;

    /**
     * Set what the printer does after a label or batch finishes printing (`^MM`). `$mode` selects
     * the post-print action — tear-off, peel-off, rewind, applicator, cutter, or delayed cutter —
     * and `$prepeel` toggles prepeel in peel-off mode. The available modes depend on the printer.
     */
    public function printMode(PostPrintAction $mode = PostPrintAction::TearOff, bool $prepeel = true): self;

    /** Toggle whether `render()` separates each ZPL command with a newline. Off by default. */
    public function printNewlines(bool $toggle = true): self;

    /** Flip the label between normal and inverted (`^PO`). */
    public function printOrientation(LabelFlip $orientation): self;

    /**
     * Set how many labels to print (`^PQ`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function printQuantity(int $quantity): self;

    /**
     * Set the print, slew, and backfeed speeds in inches per second (`^PR`). Each speed is a value
     * the printer supports (`PrintSpeed`); the printer caps any value above its maximum rate. The
     * defaults match the spec: print and backfeed at 2 ips, slew at 6 ips.
     */
    public function printRate(
        PrintSpeed $print = PrintSpeed::Ips2,
        PrintSpeed $slew = PrintSpeed::Ips6,
        PrintSpeed $backfeed = PrintSpeed::Ips2,
    ): self;

    /**
     * Resume printing on a printer that is in Pause Mode (`~PS`), equivalent to pressing PAUSE on
     * the control panel while the printer is already paused.
     */
    public function printStart(): self;

    /**
     * Set the label's print width in dots (`^PW`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function printWidth(int $width): self;

    /**
     * Append a literal ZPL fragment without content validation. Use for commands the
     * builder does not yet have a dedicated method for. Empty input is a no-op —
     * nothing is appended to the command list.
     */
    public function raw(string $zpl): self;

    /**
     * Read a tag's AFI or DSFID byte into a field for printing or return to the host (`^RA`).
     * `$fieldNumber` (0–9999) is the `^FN` field the data is read into; `$format` selects ASCII or
     * hexadecimal output; `$retries` (0–10) is the read-retry count; `$motion` controls label feed;
     * and `$byteType` selects the AFI or DSFID byte. Not supported by all printers.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function readAfiOrDsfidByte(
        int $fieldNumber = 0,
        RfidByteFormat $format = RfidByteFormat::Ascii,
        int $retries = 0,
        RfidMotion $motion = RfidMotion::Feed,
        RfidByteType $byteType = RfidByteType::Afi,
    ): self;

    /**
     * Invoke a stored format from the printer's memory (`^XF`).
     *
     * @throws StringLengthOutOfRangeException
     */
    public function recallFormat(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'ZPL',
    ): self;

    /**
     * Recall a stored graphic for printing at the current field origin, optionally magnified on
     * each axis (`^XG ... ^FS`). `$magnificationX` and `$magnificationY` accept 1 to 10 and default
     * to 1. The device defaults to `R:` (RAM) and the extension to `GRF`.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringLengthOutOfRangeException
     */
    public function recallGraphic(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
        int $magnificationX = 1,
        int $magnificationY = 1,
    ): self;

    /**
     * Drop a previously registered font preset.
     *
     * @throws FontPresetDoesNotExistException
     */
    public function removeFontPreset(string $name): self;

    /**
     * Render the accumulated commands as a ZPL string.
     * Pure — does not finalise the format. Call `end()` first if you want `^XZ`.
     */
    public function render(): string;

    /**
     * Discard all state and re-emit `^XA`. Clears the command list, font settings,
     * presets, barcode defaults, and the newline preference.
     */
    public function reset(): self;

    /**
     * Tell the printer whether to check for a wired print server at network boot (`^NB`). When set
     * to skip (the default), the wireless print server is used as the primary; when set to check, a
     * detected wired print server takes priority.
     */
    public function searchWiredPrintServer(WiredPrintServerCheck $check = WiredPrintServerCheck::Skip): self;

    /** Select the date and time format shown on the configuration label and control panel (`^KD`). */
    public function selectDateTimeFormat(DateTimeFormat $format): self;

    /**
     * Select a stored encoding table (`^SE`). The table is a `<name>.DAT` file on the given
     * storage device; the `.DAT` extension is fixed by the ZPL spec and applied automatically.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function selectEncoding(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
    ): self;

    /**
     * Serialize the next field: emit `^SN<startValue>,<increment>,<leadingZeros>` then `^FS`,
     * so the printer auto-increments (or decrements) the field on each successive label (`^SN`).
     *
     * Unlike `serializationField()` (`^SF`, a mask applied alongside a `^FD`), `^SN` *replaces*
     * the `^FD` — the starting value is carried by the command itself. `$startValue` is the field's
     * starting value (auto-escaped via `^FH` if it contains `^` / `~`, like `fieldData()`); the
     * right-most run of up to 12 digits is the indexed portion. `$increment` is the value added per
     * label and defaults to `1`; prefix it with `-` to decrement. `$leadingZeros` controls whether
     * leading zeros are printed (`Y`) or suppressed (`N`, the default). Start value and increment may
     * not contain `^`, `~`, or `,` (which would corrupt the parameter list) and must each be 1–3072
     * bytes (`SerializationData::MAX_VALUE_BYTES`); out-of-spec inputs throw
     * `StringValueContainsBannedValuesException` or `StringLengthOutOfRangeException`.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function serializationData(string $startValue, string $increment = '1', bool $leadingZeros = false): self;

    /**
     * Serialize the next field: emit `^FD<startValue>` then `^SF<mask>,<increment>` then `^FS`,
     * so the printer auto-increments the field on each successive label (`^SF`).
     *
     * `$startValue` is the field's starting value (auto-escaped via `^FH` if it contains `^` / `~`,
     * like `fieldData()`). `$mask` defines the serialization scheme — one placeholder per character
     * to serialize: `D`=decimal, `H`=hex, `O`=octal, `A`=alphabetic, `N`=alphanumeric, `%`=skip
     * (each accepts upper or lower case). `$increment` is the value added per label and defaults to
     * `1` (a decimal one). Mask and increment may not contain `^`, `~`, or `,`, and their combined
     * length must not exceed `SerializationField::MAX_COMBINED_BYTES` (3072).
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function serializationField(string $startValue, string $mask, string $increment = '1'): self;

    /**
     * Set the Real-Time Clock's mode of operation and language for printing (`^SL`).
     * Slot `a` takes either a `ClockMode` (default `StartTime`) or a numeric tolerance
     * in seconds (0–999); supplying both throws. A null language omits slot `b`, leaving
     * the language selected via `^KL` or the control panel. Must precede the first `^FO`.
     *
     * @throws ConflictingClockModeException
     * @throws IntegerValueOutOfRangeException
     */
    public function setClockMode(
        ClockMode $mode = ClockMode::StartTime,
        ?int $toleranceSeconds = null,
        ?ClockLanguage $language = null,
    ): self;

    /**
     * Set the absolute print darkness (`~SD`), the equivalent of the control-panel darkness
     * setting. Accepts 0 to 30 and renders as a two-digit value; a `^MD` adjustment, if set, is
     * added on top.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function setDarkness(int $darkness): self;

    /**
     * Set the Real-Time Clock date and time (`^ST`). Each component defaults to the
     * corresponding value of the current system time; the time format defaults to
     * 24-hour military. Accepted ranges: month `1..12`, day `1..31`, year `1998..2097`,
     * hour `0..23`, minute `0..59`, second `0..59`.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function setDateTime(
        ?int $month = null,
        ?int $day = null,
        ?int $year = null,
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ClockTimeFormat $format = ClockTimeFormat::Military24Hour,
    ): self;

    /**
     * Override the media, web, ribbon, and label-length values set during media calibration
     * (`^SS`). `$web`, `$media`, and `$ribbon` are sensor readings (0–100); `$labelLength` is in
     * dots (1–32000). The remaining LED-intensity and mark-sensing parameters (each 0–100) are
     * optional — omit the trailing ones to leave them at their calibrated values. Maximum values
     * are platform-dependent.
     *
     * @throws IntegerValueOutOfRangeException
     */
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
    ): self;

    /**
     * Set the secondary or tertiary Real-Time Clock offset from the primary clock (`^SO`).
     * Each offset (months, days, years, hours, minutes, seconds) defaults to 0 and accepts
     * `-32000` to `32000`. Only one secondary (`SO2`) offset may be used per label; use a
     * tertiary (`SO3`) offset when more than one is required.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function setOffset(
        ClockSet $clockSet,
        int $monthsOffset = 0,
        int $daysOffset = 0,
        int $yearsOffset = 0,
        int $hoursOffset = 0,
        int $minutesOffset = 0,
        int $secondsOffset = 0,
    ): self;

    /**
     * Set the printer's SMTP server address and print-server domain for e-mail alerts (`^NT`). The
     * server address is a dotted-quad string; the domain is a host domain name (e.g. `zebra.com`).
     *
     * @throws StringValueContainsBannedValuesException
     */
    public function setSmtp(string $serverAddress = '', string $domain = ''): self;

    /**
     * Set the printer's SNMP parameters (`^NN`): system name (≤17 bytes), contact and location
     * (≤50 bytes each), and the get/set (≤19 bytes) and trap (≤20 bytes) community names. The
     * community names default to `public`.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function setSnmp(
        string $systemName = '',
        string $systemContact = '',
        string $systemLocation = '',
        string $getCommunity = 'public',
        string $setCommunity = 'public',
        string $trapCommunity = 'public',
    ): self;

    /**
     * Set the units the printer interprets coordinates in (`^MU`), and optionally convert a format
     * between resolutions. `$unit` selects dots, inches, or millimetres (carried field-to-field
     * until changed). Pass both `$baseDpi` (150/200/300) and `$conversionDpi` (300/600) to scale a
     * format authored at one resolution to another; `$conversionDpi` is ignored unless `$baseDpi`
     * is also given. Best placed at the start of the label.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function setUnits(
        MeasurementUnit $unit = MeasurementUnit::Dots,
        ?int $baseDpi = null,
        ?int $conversionDpi = null,
    ): self;

    /**
     * Select the ZPL language the printer interprets (`^SZ`): legacy ZPL or ZPL II (the default).
     * Remains active until changed again or the printer is turned off.
     */
    public function setZpl(ZplMode $mode = ZplMode::ZplII): self;

    /**
     * Slew (feed without printing) the given number of dot rows from the bottom of the label
     * (`^PF`), speeding up printing when the bottom of the label is blank. Accepts 0 to 32000.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function slew(int $dotRows): self;

    /**
     * Start printing the label at the given dot row before the rest of the format is composed
     * (`^SP`), improving throughput on complex labels. Accepts 0 to 32000.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function startPrint(int $dotRow): self;

    /**
     * Suppress the forward feed to the tear-off position for this label (`^XB`), improving
     * throughput when batch printing. The last label in a batch should omit this command.
     */
    public function suppressBackfeed(): self;

    /**
     * Adjust the media rest position after printing (`~TA`), shifting where the label is torn or
     * cut. Accepts -120 to 120 dot rows and renders as a 3-digit value; the step size doubles on
     * 600 dpi printers.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function tearOffAdjust(int $dotRows): self;

    /**
     * Copy an object (graphic, font, …) from one storage device to another (`^TO`).
     *
     * Mirrors the spec's `^TOs:o.x,d:o.x` wire format: a source `device:name.extension`
     * and a destination `device:name.extension`. Standalone command — it emits only
     * `^TO…`, with no `^FD … ^FS`. The `*` wildcard is accepted in any name/extension to
     * transfer multiple objects (e.g. `LOGO*`/`*`); both names and extensions default to
     * `*`, so omitting them copies every matching object and keeps its extension. Source
     * and destination devices should differ — the printer ignores the command otherwise.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function transferObject(
        StorageDevice $sourceDevice,
        StorageDevice $destinationDevice,
        string $sourceName = '*',
        string $sourceExtension = '*',
        string $destinationName = '*',
        string $destinationExtension = '*',
    ): self;

    /**
     * Upload a stored graphic object from the printer to the host in any supported format (`^HY`).
     * An extension of `GRF` uploads the raw bitmap; `PNG` uploads the compressed bitmap. Standalone
     * command — it emits only `^HY…`, with no `^FD … ^FS`.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function uploadGraphics(
        string $name,
        StorageDevice $device = StorageDevice::Ram,
        string $extension = 'GRF',
    ): self;

    /**
     * Set the timeout (in minutes) before the printer re-prompts for its password on the web pages
     * (`^NW`). Accepts 0 (no secure pages without the password) to 255 minutes; defaults to 5.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function webAuthTimeout(int $minutes = 5): self;

    /**
     * Conditionally apply a callback to the builder. If `$predicate` is a boolean, `$callback`
     * is applied when it is `true`; if it is a callable, `$callback` is applied when it returns
     * `true` when invoked. When the predicate is falsy and `$elseCallback` is provided, that
     * callback is applied instead. Each callback receives this builder as its only argument and
     * mutates it in place; any value it returns is ignored. `when()` always returns this builder.
     *
     * @param bool|callable(): bool              $predicate
     * @param callable(ZplBuilder): mixed        $callback
     * @param null|(callable(ZplBuilder): mixed) $elseCallback
     */
    public function when(bool|callable $predicate, callable $callback, ?callable $elseCallback = null): self;

    /**
     * Change the printer's wired print-server network settings (`^NS`). `$ipResolution` selects how
     * the IP address is obtained; `$ipAddress`, `$subnetMask`, and `$defaultGateway` are dotted-quad
     * strings. The trailing parameters — WINS server, connection-timeout checking, timeout value
     * (0–9999 s), ARP broadcast interval, and base RAW port (0–99999) — are optional; omit the
     * trailing ones to leave them unchanged.
     *
     * @throws IntegerValueOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
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
    ): self;
}
