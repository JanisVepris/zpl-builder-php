<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder;

use Janisvepris\ZplBuilder\Enum\Code128Mode;
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

    /** Open a new ZPL format. Returns a builder with `^XA` already appended. */
    public static function start(): self
    {
        $builder = new self();

        return $builder->addCommand(new Commands\StartFormat());
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
