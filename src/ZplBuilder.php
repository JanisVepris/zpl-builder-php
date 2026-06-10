<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Enum\ClockLanguage;
use Janisvepris\ZplBuilder\Enum\ClockMode;
use Janisvepris\ZplBuilder\Enum\ClockSet;
use Janisvepris\ZplBuilder\Enum\ClockTimeFormat;
use Janisvepris\ZplBuilder\Enum\Code128Mode;
use Janisvepris\ZplBuilder\Enum\Code49InterpretationLine;
use Janisvepris\ZplBuilder\Enum\Code49Mode;
use Janisvepris\ZplBuilder\Enum\DateTimeFormat;
use Janisvepris\ZplBuilder\Enum\Encoding;
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\Enum\FontExtension;
use Janisvepris\ZplBuilder\Enum\Justify;
use Janisvepris\ZplBuilder\Enum\LabelFlip;
use Janisvepris\ZplBuilder\Enum\LineColor;
use Janisvepris\ZplBuilder\Enum\Orientation;
use Janisvepris\ZplBuilder\Enum\PrintDirection;
use Janisvepris\ZplBuilder\Enum\StorageDevice;
use Janisvepris\ZplBuilder\Exception\ConflictingClockModeException;
use Janisvepris\ZplBuilder\Exception\DuplicateClockIndicatorException;
use Janisvepris\ZplBuilder\Exception\FloatValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\FontPresetDoesNotExistException;
use Janisvepris\ZplBuilder\Exception\IntegerValueOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringLengthOutOfRangeException;
use Janisvepris\ZplBuilder\Exception\StringValueContainsBannedValuesException;
use Janisvepris\ZplBuilder\Exception\TertiaryClockIndicatorWithoutSecondaryException;
use Janisvepris\ZplBuilder\Exception\UnsupportedFontExtensionException;
use Janisvepris\ZplBuilder\Util\FieldDataEncoder;
use Janisvepris\ZplBuilder\ValueObject\FontPreset;
use Janisvepris\ZplBuilder\ZplCommand as Commands;
use Stringable;

class ZplBuilder implements Stringable
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

    /**
     * Register a named font preset that can later be applied via `applyFontPreset()`.
     * Unspecified dimensions inherit from the font's current settings.
     */
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

    /**
     * Apply a previously registered font preset, emitting `^CF` with its stored dimensions.
     *
     * @throws FontPresetDoesNotExistException
     * @throws IntegerValueOutOfRangeException
     */
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

    /**
     * Draw an Aztec 2D barcode with the given data (`^B0` + `^FD ... ^FS`).
     *
     * Aztec sizes itself by magnification factor (`1..10`) rather than a `^BY` height. `$errorControl`
     * follows the spec's encoding: `0` = default correction, `1..99` = minimum correction percentage,
     * `101..104` = 1–4-layer compact symbol, `201..232` = 1–32-layer full-range symbol, `300` = Aztec
     * "Rune". `$symbolCount` (`1..26`) and `$structuredAppendId` (≤24 bytes, optional) drive structured
     * append; an empty ID is omitted from the output.
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
        int $errorControl = 0,
        bool $menuSymbol = false,
        int $symbolCount = 1,
        string $structuredAppendId = '',
    ): self {
        $this->addCommand(
            new Commands\BarcodeAztec(
                orientation: $orientation,
                magnification: $magnification,
                extendedChannelInterpretation: $extendedChannelInterpretation,
                errorControl: $errorControl,
                menuSymbol: $menuSymbol,
                symbolCount: $symbolCount,
                structuredAppendId: $structuredAppendId,
            ),
        );

        return $this->fieldData($data);
    }

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

    /**
     * Change the default font (`^CF`) and optionally its height and/or width.
     * Unspecified dimensions keep the last value set for that font.
     *
     * @throws IntegerValueOutOfRangeException
     */
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

    /** Set the printer's character encoding (`^CI`), with optional character remaps. */
    public function changeInternationalEncoding(Encoding $encoding, CharacterRemap ...$characterRemaps): self
    {
        return $this->addCommand(
            new Commands\ChangeInternationalEncoding($encoding, ...$characterRemaps),
        );
    }

    /**
     * Insert a non-printing comment into the ZPL output (`^FX`). Useful for debugging.
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function comment(string $text): self
    {
        return $this->addCommand(new Commands\FieldComment($text));
    }

    /** Finalise the format by appending `^XZ`. */
    public function end(): self
    {
        return $this->addCommand(new Commands\EndFormat());
    }

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
    ): self {
        return $this->addCommand(
            new Commands\FieldClock(
                primary: $primary,
                secondary: $secondary,
                tertiary: $tertiary,
            ),
        );
    }

    /**
     * Write text into the current field (`^FD ... ^FS`). Auto-escapes `^` and `~`
     * via `^FH_` if present, since the printer would otherwise treat them as command starts.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function fieldData(string $data): self
    {
        return $this->appendField(
            $data,
            static fn (string $escaped): Commands => new Commands\FieldData($escaped),
        );
    }

    /**
     * Declare the hex-escape character used by the next `^FD` (`^FH`).
     *
     * @throws StringLengthOutOfRangeException
     * @throws StringValueContainsBannedValuesException
     */
    public function fieldHexIndicator(string $indicator = '_'): self
    {
        $this->pendingHexIndicator = $indicator;

        return $this->addCommand(new Commands\FieldHexIndicator($indicator));
    }

    /**
     * Tag the next field with a number, for use with stored formats (`^FN`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldNumber(int $number): self
    {
        return $this->addCommand(new Commands\FieldNumber($number));
    }

    /** Set the orientation applied to subsequent fields (`^FW`). */
    public function fieldOrientation(Orientation $orientation): self
    {
        return $this->addCommand(new Commands\FieldOrientation($orientation));
    }

    /**
     * Position the next field at the given (x, y) coordinate in dots (`^FO`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldOrigin(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldOrigin($x, $y));
    }

    /**
     * Set multiple field origin locations for PDF417 (`^B7`) / MicroPDF417 (`^BF`)
     * structured-append printing (`^FM`). Up to 60 locations; printer ignores `^FM`
     * for other commands. Empty input is a no-op.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldOrigins(FieldOriginLocation ...$locations): self
    {
        if ($locations === []) {
            return $this;
        }

        return $this->addCommand(new Commands\MultipleFieldOrigin(...$locations));
    }

    /**
     * Set the print direction and additional inter-character gap for the next field (`^FP`).
     * Used for vertical and reverse text, commonly when printing Asian fonts.
     *
     * @throws IntegerValueOutOfRangeException
     */
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

    /**
     * Reverse-print the next field — it renders in the inverse of its background (`^FR`).
     * Applies to a single field; for whole-label reverse printing prefer `labelReversePrint()` (`^LR`).
     */
    public function fieldReversePrint(): self
    {
        return $this->addCommand(new Commands\FieldReversePrint());
    }

    /**
     * Position the next field at the given (x, y) coordinate in dots (`^FT`).
     *
     * Like `^FO`, but the typeset origin sits at the baseline of the last line of
     * text, so increasing the font size grows the field upward rather than downward.
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function fieldTypeset(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\FieldTypeset($x, $y));
    }

    /**
     * Write variable text into the current field (`^FV ... ^FS`). Behaves like `fieldData()`,
     * but the printer clears the field after the label prints — pair with `^MC` so high-throughput
     * formats reformat only the fields that change. Auto-escapes `^` and `~` via `^FH_` if present.
     *
     * @throws StringLengthOutOfRangeException
     */
    public function fieldVariable(string $data): self
    {
        return $this->appendField(
            $data,
            static fn (string $escaped): Commands => new Commands\FieldVariable($escaped),
        );
    }

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

    /**
     * Return the list of commands accumulated so far. Useful for testing and external rendering.
     *
     * @return Commands[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Return all currently registered font presets, keyed by name.
     *
     * @return array<string, FontPreset>
     */
    public function getFontPresets(): array
    {
        return $this->fontPresets;
    }

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

    /** Whether a font preset with the given name has been registered. */
    public function hasFontPreset(string $name): bool
    {
        return isset($this->fontPresets[$name]);
    }

    /**
     * Move the label's home origin to the given (x, y) coordinate (`^LH`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelHome(int $x = 0, int $y = 0): self
    {
        return $this->addCommand(new Commands\LabelHome($x, $y));
    }

    /**
     * Set the label's length in dots (`^LL`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function labelLength(int $length): self
    {
        return $this->addCommand(new Commands\LabelLength($length));
    }

    /** Toggle reverse-print — fields render white-on-black instead of black-on-white (`^LR`). */
    public function labelReversePrint(bool $reversePrint = true): self
    {
        return $this->addCommand(new Commands\LabelReversePrint($reversePrint));
    }

    /** Toggle whether `render()` separates each ZPL command with a newline. Off by default. */
    public function printNewlines(bool $toggle = true): self
    {
        $this->printNewlines = $toggle;

        return $this;
    }

    /** Flip the label between normal and inverted (`^PO`). */
    public function printOrientation(LabelFlip $orientation): self
    {
        return $this->addCommand(new Commands\PrintOrientation($orientation));
    }

    /**
     * Set how many labels to print (`^PQ`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function printQuantity(int $quantity): self
    {
        return $this->addCommand(new Commands\PrintQuantity($quantity));
    }

    /**
     * Set the label's print width in dots (`^PW`).
     *
     * @throws IntegerValueOutOfRangeException
     */
    public function printWidth(int $width): self
    {
        return $this->addCommand(new Commands\PrintWidth($width));
    }

    /**
     * Append a literal ZPL fragment without content validation. Use for commands the
     * builder does not yet have a dedicated method for. Empty input is a no-op —
     * nothing is appended to the command list.
     */
    public function raw(string $zpl): self
    {
        if ($zpl === '') {
            return $this;
        }

        return $this->addCommand(new Commands\RawCommand($zpl));
    }

    /**
     * Invoke a stored format from the printer's memory (`^XF`).
     *
     * @throws StringLengthOutOfRangeException
     */
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

    /**
     * Drop a previously registered font preset.
     *
     * @throws FontPresetDoesNotExistException
     */
    public function removeFontPreset(string $name): self
    {
        if (!isset($this->fontPresets[$name])) {
            throw new FontPresetDoesNotExistException($name);
        }

        unset($this->fontPresets[$name]);

        return $this;
    }

    /**
     * Render the accumulated commands as a ZPL string.
     * Pure — does not finalise the format. Call `end()` first if you want `^XZ`.
     */
    public function render(): string
    {
        if ($this->commands === []) {
            return '';
        }

        $separator = $this->printNewlines ? PHP_EOL : '';

        return implode($separator, array_map('strval', $this->commands)) . $separator;
    }

    /**
     * Discard all state and re-emit `^XA`. Clears the command list, font settings,
     * presets, barcode defaults, and the newline preference.
     */
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

    /** Select the date and time format shown on the configuration label and control panel (`^KD`). */
    public function selectDateTimeFormat(DateTimeFormat $format): self
    {
        return $this->addCommand(new Commands\SelectDateTimeFormat($format));
    }

    /**
     * Select a stored encoding table (`^SE`). The table is a `<name>.DAT` file on the given
     * storage device; the `.DAT` extension is fixed by the ZPL spec and applied automatically.
     *
     * @throws StringLengthOutOfRangeException
     */
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
    public function serializationField(string $startValue, string $mask, string $increment = '1'): self
    {
        return $this->appendField(
            $startValue,
            static fn (string $escaped): Commands => new Commands\FieldData($escaped),
            [new Commands\SerializationField($mask, $increment)],
        );
    }

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
    ): self {
        return $this->addCommand(
            new Commands\SetClockMode(
                mode: $toleranceSeconds === null ? $mode : null,
                toleranceSeconds: $toleranceSeconds,
                language: $language,
            ),
        );
    }

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

    /** Open a new ZPL format. Returns a builder with `^XA` already appended. */
    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
    }

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
