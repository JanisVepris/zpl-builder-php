# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The public API is **unstable until 1.0** — minor versions may include breaking changes.

## [Unreleased]

### Added

- `ZplBuilder::barcodeLogmars()` and `ZplCommand\BarcodeLogmars` add support for `^BL` (LOGMARS Bar Code)
- `ZplBuilder::barcodeCodabar()` and `ZplCommand\BarcodeCodabar` add support for `^BK` (ANSI Codabar Bar Code), with the `Enum\CodabarCharacter` (`A`–`D`) selecting the start/stop characters
- `ZplBuilder::barcodeStandard2of5()` and `ZplCommand\BarcodeStandard2of5` add support for `^BJ` (Standard 2 of 5 Bar Code)
- `ZplBuilder::barcodeIndustrial2of5()` and `ZplCommand\BarcodeIndustrial2of5` add support for `^BI` (Industrial 2 of 5 Bar Code)
- `ZplBuilder::barcodeMicroPdf417()` and `ZplCommand\BarcodeMicroPdf417` add support for `^BF` (Micro-PDF417 Bar Code)
- `ZplBuilder::barcodeEan13()` and `ZplCommand\BarcodeEan13` add support for `^BE` (EAN-13 Bar Code)
- `ZplBuilder::barcodeMaxiCode()` and `ZplCommand\BarcodeMaxiCode` add support for `^BD` (UPS MaxiCode Bar Code), with the `Enum\MaxiCodeMode` selecting the symbol mode
- `ZplBuilder::barcodeCodablock()` and `ZplCommand\BarcodeCodablock` add support for `^BB` (CODABLOCK Bar Code), with the `Enum\CodablockMode` (`ModeA`/`ModeE`/`ModeF`) selecting the character set
- `ZplBuilder::barcodeCode93()` and `ZplCommand\BarcodeCode93` add support for `^BA` (Code 93 Bar Code)
- `ZplBuilder::barcodeUpcE()` and `ZplCommand\BarcodeUpcE` add support for `^B9` (UPC-E Bar Code)
- `ZplBuilder::barcodeEan8()` and `ZplCommand\BarcodeEan8` add support for `^B8` (EAN-8 Bar Code)
- `ZplBuilder::barcodePdf417()` and `ZplCommand\BarcodePdf417` add support for `^B7` (PDF417 Bar Code)
- `ZplBuilder::barcodePlanetCode()` and `ZplCommand\BarcodePlanetCode` add support for `^B5` (Planet Code Bar Code)
- `ZplBuilder::barcodeCode49()` and `ZplCommand\BarcodeCode49` add support for `^B4` (Code 49 Bar Code)
- `ZplBuilder::barcodeCode39()` and `ZplCommand\BarcodeCode39` add support for `^B3` (Code 39 / USD-3 / 3 of 9 Bar Code)
- `ZplBuilder::barcodeInterleaved2of5()` and `ZplCommand\BarcodeInterleaved2of5` add support for `^B2` (Interleaved 2 of 5 Bar Code)
- `ZplBuilder::barcodeCode11()` and `ZplCommand\BarcodeCode11` add support for `^B1` (Code 11 / USD-8 Bar Code)
- `ZplBuilder::barcodeAztec()` and `ZplCommand\BarcodeAztec` add support for `^B0` (Aztec Bar Code)

## [0.60.0] - 2026-06-10

### Added

- `ZplBuilder::transferObject()` and `ZplCommand\TransferObject` add support for `^TO` (Transfer Object), which copies an object (graphic, font, …) from one storage device to another using the spec's
  `s:o.x,d:o.x` (`device:name.extension`) wire format. Source and destination devices are `Enum\StorageDevice` cases (`R:`, `E:`, `B:`, `A:`); the four name/extension parts are plain strings —
  modelled as strings rather than `FontExtension` because `^TO` transfers arbitrary object types (`.GRF`, `.FNT`, …) and the spec permits the `*` wildcard for names and extensions to transfer multiple
  objects in one command (e.g. `R:LOGO*.GRF,B:NEW*.GRF`). All four name/extension arguments default to `*`, so omitting them copies every matching object and keeps each one's extension. Names are
  capped at `TransferObject::MAX_NAME_BYTES` (16) and extensions at `TransferObject::MAX_EXTENSION_BYTES` (3); each part rejects the `d:name.ext` separators (`^`, `~`, `:`, `.`, `,`) while
  intentionally allowing `*`. Out-of-spec inputs throw `StringLengthOutOfRangeException` (empty or over-length name/extension) or `StringValueContainsBannedValuesException` (a part contains a
  separator). ([`8ac2ea5`](https://github.com/JanisVepris/zpl-builder-php/commit/8ac2ea5))
- `ZplBuilder::setDateTime()` and `ZplCommand\SetDateTime` add support for `^ST` (Set Date and Time), which sets the printer's Real-Time Clock. Parameters are month (`1..12`), day (`1..31`), year (
  `1998..2097`, emitted as four digits), hour (`0..23`), minute (`0..59`), and second (`0..59`) — all zero-padded on the wire — plus the time format, a new `Enum\ClockTimeFormat`: `Am` (`A`), `Pm` (
  `P`), and `Military24Hour` (`M`, the spec default). At the builder layer each numeric component defaults to the matching value of the current system time and the format defaults to `Military24Hour`,
  so `setDateTime()` with no arguments stamps "now". It is a standalone command — it emits only `^ST…`, with no `^FD … ^FS`. Out-of-range components throw `IntegerValueOutOfRangeException`. ([
  `2b41d79`](https://github.com/JanisVepris/zpl-builder-php/commit/2b41d79))
- `ZplBuilder::setOffset()` and `ZplCommand\SetOffset` add support for `^SO` (Set Offset), which sets the secondary (`SO2`) or tertiary (`SO3`) Real-Time Clock offset from the primary clock. The clock
  to offset is selected via the new `Enum\ClockSet` (`Secondary`/`Tertiary`); the six offset values (months, days, years, hours, minutes, seconds) each default to `0` at the builder layer and accept
  `-32000` to `32000`. Out-of-range offsets throw `IntegerValueOutOfRangeException`. ([`043498e`](https://github.com/JanisVepris/zpl-builder-php/commit/043498e))
- `ZplBuilder::serializationData()` and `ZplCommand\SerializationData` add support for `^SN` (Serialization Data), which makes the printer auto-increment (or decrement) a field on each successive
  label. Unlike `^SF` (Serialization Field), which applies a mask alongside a `^FD`, `^SN` *replaces* the `^FD` — the starting value is carried by the command itself. The method emits
  `^SN<startValue>,<increment>,<leadingZeros>` (auto-escaping `^` and `~` via `^FH` like `fieldData()`), then `^FS`. The right-most run of up to 12 digits in the starting value is the indexed portion;
  `increment` is the value added per label and defaults to `1` (prefix it with `-` to decrement), and `leadingZeros` chooses whether leading zeros are printed (`Y`) or suppressed (`N`, the default).
  Start value and increment may not contain `^`, `~`, or `,` (which would corrupt the parameter list) and must each be 1–3072 bytes (`SerializationData::MAX_VALUE_BYTES`); out-of-spec inputs throw
  `StringValueContainsBannedValuesException` or `StringLengthOutOfRangeException`. ([`da3237b`](https://github.com/JanisVepris/zpl-builder-php/commit/da3237b))
- `ZplBuilder::setClockMode()` and `ZplCommand\SetClockMode` add support for `^SL` (Set Mode and Language for Real-Time Clock), which selects the RTC's mode of operation and the language used for
  printing day/month names; it must precede the first `^FO`. The mode slot accepts either an `Enum\ClockMode` (`StartTime` = `S`, the default; `TimeNow` = `T`) or a numeric `toleranceSeconds` in the
  0–999 range — supplying both throws `ConflictingClockModeException`. The optional `Enum\ClockLanguage` (`English` = `1` … `Finnish` = `12`) is emitted only when given; omitting it leaves the
  language selected via `^KL` or the control panel. An out-of-range tolerance throws `IntegerValueOutOfRangeException`. ([`46e2435`](https://github.com/JanisVepris/zpl-builder-php/commit/46e2435))
- `ZplBuilder::selectEncoding()` and `ZplCommand\SelectEncoding` add support for `^SE` (Select Encoding), which activates a stored `<name>.DAT` encoding table on the printer. The table name is
  required (1–8 bytes); the storage device is an `Enum\StorageDevice` (`R:`/`E:`/`B:`/`A:`) defaulting to `R:`, and the `.DAT` extension is fixed by the spec and appended automatically. A name shorter
  than 1 byte or longer than 8 bytes throws `StringLengthOutOfRangeException`. ([`e761e2f`](https://github.com/JanisVepris/zpl-builder-php/commit/e761e2f))
- `ZplBuilder::selectDateTimeFormat()` and `ZplCommand\SelectDateTimeFormat` add support for `^KD` (Select Date and Time Format), which selects how the Real-Time Clock's date and time are presented on
  the configuration label and the printer's control-panel display. The format is a new `Enum\DateTimeFormat`: `VersionNumber` (`0`, firmware version number — the spec default, also the fallback when
  no RTC hardware is present), `MonthDayYear24Hour` (`1`), `MonthDayYear12Hour` (`2`), `DayMonthYear24Hour` (`3`), and `DayMonthYear12Hour` (`4`). It is a standalone configuration command — it emits
  only `^KD…`, with no `^FD … ^FS`. ([`0f13e47`](https://github.com/JanisVepris/zpl-builder-php/commit/0f13e47))
- `ZplBuilder::fontIdentifier()` and `ZplCommand\FontIdentifier` add support for `^CW` (Font Identifier), which maps a single-character font designator (the `Enum\Font` vocabulary, `A`–`Z` / `0`–`9`)
  to a downloaded or resident font file, so later `^CF`/`^A` references to that letter print the downloaded font in place of — or, for an unused letter, in addition to — the built-in one. The mapping
  lasts only until power-off or until the same letter is remapped. It is a standalone command — it emits only `^CW…`, with no `^FD … ^FS`. The drive reuses `Enum\StorageDevice` (default `R:`, matching
  `fontByName()`) and the extension reuses `Enum\FontExtension` (default `.FNT`); the font file name must be 1–8 bytes (`FontIdentifier::MAX_NAME_BYTES`) and may not contain `^`, `~`, `:`, `.`, or
  `,` (which would corrupt the `d:name.ext` wire format), otherwise `StringLengthOutOfRangeException` or `StringValueContainsBannedValuesException` is thrown. ([
  `c96210d`](https://github.com/JanisVepris/zpl-builder-php/commit/c96210d))
- `Enum\FontExtension::TrueTypeExtension` (`.TTE`, TrueType extension/collection) — a third extension alongside `.FNT` and `.TTF`, accepted by `^CW` (`fontIdentifier()`). `^A@` (`fontByName()`) does *
  *not** accept it — see the `FontName` note below. ([`c96210d`](https://github.com/JanisVepris/zpl-builder-php/commit/c96210d))
- `Exception\UnsupportedFontExtensionException` (extends `InvalidArgumentException`) and `ZplCommand\FontName::SUPPORTED_EXTENSIONS` — `^A@` (`fontByName()`) now rejects any extension outside its
  supported set (`.FNT`, `.TTF`), throwing `UnsupportedFontExtensionException`. This keeps the shared `Enum\FontExtension` (which gained `.TTE` for `^CW`) from silently widening what `^A@` accepts;
  for `.TTE` fonts, assign an identifier with `fontIdentifier()` (`^CW`) and reference it via `changeFont()`/`font()`. ([`c96210d`](https://github.com/JanisVepris/zpl-builder-php/commit/c96210d))
- `ZplBuilder::serializationField()` and `ZplCommand\SerializationField` add support for `^SF` (Serialization Field), which makes the printer auto-increment a field on each successive label. The
  method emits `^FD<startValue>` (auto-escaping `^` and `~` via `^FH` like `fieldData()`), then `^SF<mask>,<increment>`, then `^FS`. The mask defines the serialization scheme one placeholder per
  character — `D` (decimal), `H` (hexadecimal), `O` (octal), `A` (alphabetic), `N` (alphanumeric), or `%` (skip), each accepting upper or lower case — and the increment is the value added per label,
  defaulting to `1` (a decimal one). Mask and increment may not contain `^`, `~`, or `,` (which would corrupt the two-parameter list) and their combined length must not exceed
  `SerializationField::MAX_COMBINED_BYTES` (3072); out-of-spec inputs throw `StringValueContainsBannedValuesException` or `StringLengthOutOfRangeException`. ([
  `fef307b`](https://github.com/JanisVepris/zpl-builder-php/commit/fef307b))
- `ZplBuilder::font()` and `ZplCommand\ScalableBitmappedFont` add support for `^A` (Scalable/Bitmapped Font), which selects the font for the next field only — unlike `changeFont()` (`^CF`, the default
  font), the printer reverts to the `^CF` default after that field. Reuses `Enum\Font` (`A`–`Z`, `0`–`9`) for the font designator and `Enum\Orientation` (`N`/`R`/`I`/`B`) for field orientation,
  defaulting to normal. Height and width are in dots, defaulting to the 10-dot minimum (`ScalableBitmappedFont::MIN_DIMENSION`); values outside `10..32000` throw `IntegerValueOutOfRangeException`. As
  a field modifier it emits only the `^A` fragment — chain `fieldData()` afterward to write the text. ([`b517111`](https://github.com/JanisVepris/zpl-builder-php/commit/b517111))
- `ZplBuilder::fontByName()` and `ZplCommand\FontName` add support for `^A@` (Use Font Name to Call Font), which selects a downloaded or resident font by its stored file name and extension rather than
  the single-character designator used by `^CF`. Like `^A`, it is a per-field selector — it emits only the `^A@…` command and pairs with a following `^FD … ^FS` of your own. Orientation reuses
  `Enum\Orientation` (default `N`) and the drive reuses `Enum\StorageDevice` (default `R:`, matching `^XF`); the extension is a new `Enum\FontExtension` (`.FNT` font, `.TTF` TrueType) defaulting to
  `.FNT`. Height and width are in dots and must be `0..32000` or `IntegerValueOutOfRangeException` is thrown; the font name must be 1–16 bytes (`FontName::MAX_NAME_BYTES`) and may not contain `^`,
  `~`, `:`, `.`, or `,` (which would corrupt the `d:name.ext` wire format), otherwise `StringLengthOutOfRangeException` or `StringValueContainsBannedValuesException` is thrown. ([
  `c3f1d1d`](https://github.com/JanisVepris/zpl-builder-php/commit/c3f1d1d))
- `ZplBuilder::fieldVariable()` and `ZplCommand\FieldVariable` add support for `^FV` (Field Variable), which writes field content like `^FD` but the printer clears the field after the label prints —
  pair it with `^MC` so high-throughput formats reformat only the fields that change. Like `fieldData()`, it auto-escapes `^` and `~` via `^FH` (the spec confirms `^FH` applies to `^FV`) and closes
  the field with `^FS`; data over `FieldVariable::MAX_DATA_BYTES` (3072) throws `StringLengthOutOfRangeException`. ([`3ab725a`](https://github.com/JanisVepris/zpl-builder-php/commit/3ab725a))
- `ZplBuilder::fieldTypeset()` and `ZplCommand\FieldTypeset` add support for `^FT` (Field Typeset), which positions the next field at an `(x, y)` coordinate in dots. Like `^FO`, but the typeset origin
  sits at the baseline of the last line of text, so increasing the font size grows the field upward rather than downward. Both coordinates default to `0` and must be `0..32000` or
  `IntegerValueOutOfRangeException` is thrown. ([`bcb2d84`](https://github.com/JanisVepris/zpl-builder-php/commit/bcb2d84))
- `ZplBuilder::fieldReversePrint()` and `ZplCommand\FieldReversePrint` add support for `^FR` (Field Reverse Print), which makes the next field render in the inverse of its background (white-on-black
  over a black area). The command is parameter-less and applies to a single field; for whole-label reverse printing use `labelReversePrint()` (`^LR`). ([
  `8bce2e2`](https://github.com/JanisVepris/zpl-builder-php/commit/8bce2e2))
- `ZplBuilder::fieldParameter()` and `ZplCommand\FieldParameter` add support for `^FP` (Field Parameter), which sets the print direction and additional inter-character gap for the next field — used
  for vertical and reverse text, commonly when printing Asian fonts. Direction is a new `Enum\PrintDirection` (`Horizontal`, `Vertical`, `Reverse`) defaulting to `Horizontal`; the gap defaults to `0`
  and must be `0..9999` (`FieldParameter::MAX_GAP`) or `IntegerValueOutOfRangeException` is thrown. ([`bf4398f`](https://github.com/JanisVepris/zpl-builder-php/commit/bf4398f))
- `ZplBuilder::fieldClock()` and `ZplCommand\FieldClock` add support for `^FC` (Field Clock), which sets the primary, secondary, and tertiary clock-indicator characters that the next `^FD` substitutes
  with Real-Time Clock values. Primary defaults to `%`; secondary and tertiary are optional. Out-of-spec inputs throw `DuplicateClockIndicatorException` (two indicators collide),
  `TertiaryClockIndicatorWithoutSecondaryException` (positional gap), `StringLengthOutOfRangeException` (indicator must be a single byte), or `StringValueContainsBannedValuesException` (indicator
  cannot be `^`, `~`, or `,` — those would corrupt the wire format). ([`068b0ef`](https://github.com/JanisVepris/zpl-builder-php/commit/068b0ef))
- `ZplBuilder::fieldOrigins(FieldOriginLocation ...$locations)` and `ZplCommand\MultipleFieldOrigin` add support for `^FM` (Multiple Field Origin Locations), used to place multiple symbols when
  printing PDF417 (`^B7`) / MicroPDF417 (`^BF`) structured-append bar codes. Each location is a `FieldOriginLocation`, constructed via `FieldOriginLocation::at($x, $y)` for a positioned symbol or
  `FieldOriginLocation::excluded()` to render the per-pair `e,e` skip-this-symbol marker. Empty input to `fieldOrigins()` is a no-op; passing more than 60 locations throws
  `IntegerValueOutOfRangeException` (spec maximum). ([`6aecca2`](https://github.com/JanisVepris/zpl-builder-php/commit/6aecca2))

## [0.50.0] - 2026-05-26

### Added

- Public class constants for previously-magic numeric bounds: `Util\ValueAssert::MAX_DIMENSION` (32000), `ZplCommand\FieldData::MAX_DATA_BYTES` (3072), `ZplCommand\FieldComment::MAX_TEXT_BYTES` (
  3072), `ZplCommand\FieldBlock::MAX_PARAM` (9999). Internal validators now reference these constants instead of repeating literals; tests and callers can reference them when constructing boundary
  inputs. ([`75e19c4`](https://github.com/JanisVepris/zpl-builder-php/commit/75e19c4))
- Every `ZplCommand\*` value object now exposes two public typed constants: `COMMAND` (the literal command sigil, e.g. `'^BC'`, `'^FD'`, `'^XA'`) and `FORMAT` (the parameter-only sprintf template,
  e.g. `'%s,%d,%s,%s,%s,%s'`; empty for parameter-less commands). Previously `FORMAT` was private and held the full command-plus-params template; the split lets callers reference either piece
  programmatically (e.g. when adding their own command in a subclass or parsing emitted ZPL). `RawCommand` is unchanged — it doesn't model a fixed command literal. ([
  `c7ad2ef`](https://github.com/JanisVepris/zpl-builder-php/commit/c7ad2ef))

### Fixed

- `^BY` (barcode defaults) formatted the wide-to-narrow ratio with `%0.1f`, which honours `LC_NUMERIC`. On comma-decimal locales (e.g. `de_DE`, `fr_FR`) the emitted ZPL became `^BY2,3,0,100` and was
  parsed by the printer as four arguments. Now uses `%0.1F` for locale-independent output. ([`f212cfd`](https://github.com/JanisVepris/zpl-builder-php/commit/f212cfd))
- `ZplBuilder::changeFont()` partially mutated its cached `FontSettings` before raising on an invalid argument. A failed call like `changeFont(Font::A, 30, -1)` wrote height=30 into the cache but
  never emitted `^CF`, so the very next no-arg `changeFont(Font::A)` used the leaked height. `changeFont()` and `barcodeDefaults()` now build a fresh settings value (validated via its constructor) and
  swap the cached reference atomically — partial writes on failure are no longer possible. ([`0569b4d`](https://github.com/JanisVepris/zpl-builder-php/commit/0569b4d))
- `ZplBuilder::fieldData()` ignored an explicit `fieldHexIndicator()` declaration and always emitted `^FH_`, then escaped with `_`. A caller doing `fieldHexIndicator('%')->fieldData('foo^bar')` got
  `^FH%^FH_^FDfoo_5Ebar^FS` — the user's `%` was dead-letter ZPL and the data was escaped with the wrong character. The builder now tracks a pending indicator, reuses it for escape in the next
  `fieldData()`, and clears it at the `^FS` boundary per the ZPL spec. ([`7e74085`](https://github.com/JanisVepris/zpl-builder-php/commit/7e74085))

### Breaking changes

- `ZplCommand\ChangeFont`, `ValueObject\FontPreset`, `FontSettings`, and `BarcodeDefaultSettings` constructors now validate height/width (and barcode module width / ratio) via `ValueAssert` and throw
  `IntegerValueOutOfRangeException` / `FloatValueOutOfRangeException` for out-of-range inputs. Previously the `ChangeFont` and `FontPreset` VOs accepted any int and the settings classes only validated
  through their setters, so direct instantiation could produce out-of-spec `^CF` output. ([`e7e1a1b`](https://github.com/JanisVepris/zpl-builder-php/commit/e7e1a1b))
- `Util\FieldDataEncoder::escape()` now validates the `$indicator` argument (length 1 byte, not `^` or `~`) and throws `StringLengthOutOfRangeException` or `StringValueContainsBannedValuesException`.
  Previously multi-byte or empty indicators emitted PHP deprecation/warning notices and produced invalid ZPL, and `^` / `~` indicators silently produced a `^FH` declaration the printer can't parse.
  Matches the validation already enforced by `ZplCommand\FieldHexIndicator`. ([`fcdbaf5`](https://github.com/JanisVepris/zpl-builder-php/commit/fcdbaf5))
- `ZplBuilder::barcodeDefaults()` no-arg height changed from `100` to `10` to match `BarcodeDefaultSettings`'s own constructor default. Previously calling `barcodeDefaults()` with no args jumped to
  100 while never calling it left the cached height at 10 — the two paths now agree. Callers depending on the old `100` default need to pass it explicitly. ([
  `07c3662`](https://github.com/JanisVepris/zpl-builder-php/commit/07c3662))
- `ZplBuilder::__construct()` is now `protected`. `start()` is the sole external entry point; subclasses can still call `parent::__construct()`. Previously `new ZplBuilder()` produced a builder
  without the `^XA` start-of-format command and silently diverged from the `start()`-based flow. ([`b58ab19`](https://github.com/JanisVepris/zpl-builder-php/commit/b58ab19))
- `ZplBuilder::removeFontPreset()` now throws `FontPresetDoesNotExistException` when the name isn't registered, matching `applyFontPreset()`. Previously remove silently no-opped, hiding typos in
  preset names. ([`70b3cca`](https://github.com/JanisVepris/zpl-builder-php/commit/70b3cca))
- `ZplCommand\FieldOrientation` constructor parameter renamed from `$fieldRotation` to `$orientation`, and `ZplBuilder::fieldOrientation()` parameter renamed from `$rotation` to `$orientation`. Both
  match the class/method name and the ZPL spec's "field orientation" terminology. Callers using the named-arg forms (`new FieldOrientation(fieldRotation: …)` or `fieldOrientation(rotation: …)`) must
  update to `orientation:`. Positional callers are unaffected. ([`47a3d4a`](https://github.com/JanisVepris/zpl-builder-php/commit/47a3d4a), [
  `74307f2`](https://github.com/JanisVepris/zpl-builder-php/commit/74307f2))
- `FloatValueOutOfRangeException`, `IntegerValueOutOfRangeException`, and `StringLengthOutOfRangeException` now extend `RangeException` (RuntimeException) instead of `OutOfRangeException` (
  LogicException). The values these exceptions guard against come from runtime user input, so the SPL semantics match. Callers that caught the SPL parent `OutOfRangeException` / `LogicException` must
  update to `RangeException` / `RuntimeException`; catching the specific library class still works. ([`1d31397`](https://github.com/JanisVepris/zpl-builder-php/commit/1d31397))

### Changed

- `ZplBuilder::addCommand()` and `ZplBuilder::fontSettingsFor()` relaxed from `private` to `protected`. Subclasses can now register their own `ZplCommand` implementations and read the lazy-allocated
  per-font state cache without reimplementing the facade. No impact on existing callers — `ZplBuilder` is intentionally non-final and this formalises the extension surface. ([
  `fcb5e38`](https://github.com/JanisVepris/zpl-builder-php/commit/fcb5e38))
- All classes are now non-`final`. v0.40.0 marked every command, VO, and exception `final`; v0.50.0 reverses that policy across the board so downstream consumers can subclass anything in the library.
  `readonly` stays where it was (subclasses of a `readonly` class must themselves be `readonly` per PHP). No impact on existing callers; new extension surface for subclassers. ([
  `fb8d134`](https://github.com/JanisVepris/zpl-builder-php/commit/fb8d134))
- The parameter-less commands `StartFormat`, `EndFormat`, and `FieldSeparator` are now `readonly class` (previously plain `class` since they have no state). Keeps the ZplCommand layer uniformly
  `readonly` — subclassers of these three must also be `readonly`, matching every other command VO. ([`397443c`](https://github.com/JanisVepris/zpl-builder-php/commit/397443c))
- `ZplBuilder::raw('')` is now a true no-op — empty input short-circuits inside `raw()` and nothing is appended to the command list. Previously a `RawCommand('')` entry was added that rendered as the
  empty string, so `getCommands()` length bumped while the rendered ZPL gained nothing. The rendered output is unchanged; callers inspecting `getCommands()` may see a smaller count. ([
  `e9f89fc`](https://github.com/JanisVepris/zpl-builder-php/commit/e9f89fc))

## [0.40.1] - 2026-05-21

### Fixed

- `FloatValueOutOfRangeException` formatted float values with `%d`, so `-0.5` rendered as `0`. Now uses `%g`. ([`241fbc5`](https://github.com/JanisVepris/zpl-builder-php/commit/241fbc5))
- `^FD` field data containing literal `^` or `~` produced broken ZPL — the printer interpreted them as the start of a new command. `fieldData()` now auto-escapes via `^FH_` + hex encoding; the VO
  itself rejects raw `^` / `~`. ([`305e3f8`](https://github.com/JanisVepris/zpl-builder-php/commit/305e3f8), [`6d857e6`](https://github.com/JanisVepris/zpl-builder-php/commit/6d857e6))
- `(string) $builder` permanently flipped `$formatEnded`, so logging a debug copy mid-build broke the builder. `render()` is now a pure iteration over `$this->commands`; the `$formatEnded` bookkeeping
  is gone entirely. ([`7424e4b`](https://github.com/JanisVepris/zpl-builder-php/commit/7424e4b), [`1f8752c`](https://github.com/JanisVepris/zpl-builder-php/commit/1f8752c))
- `ZplBuilder::reset()` left `$fontPresets` and `$printNewlines` untouched — partial reset is surprising. It now clears them too. ([
  `60ae6ce`](https://github.com/JanisVepris/zpl-builder-php/commit/60ae6ce))

### Added

- `Util\ValueAssert::stringNotContains()` + `StringValueContainsBannedValuesException` for asserting strings are free of banned substrings. ([
  `305e3f8`](https://github.com/JanisVepris/zpl-builder-php/commit/305e3f8))
- `Util\FieldDataEncoder::escape()` — hex-escapes `^`, `~`, and the indicator character for safe inclusion in `^FD` field data. ([
  `6d857e6`](https://github.com/JanisVepris/zpl-builder-php/commit/6d857e6))
- `ZplBuilder::raw(string)` escape hatch + `ZplCommand\RawCommand` VO for emitting arbitrary ZPL fragments the builder doesn't natively support. ([
  `7e8d829`](https://github.com/JanisVepris/zpl-builder-php/commit/7e8d829))
- `Enum\Font` with 36 cases (`A`–`Z`, `Zero`–`Nine`) replacing the loose `int|string` font key. ([`dabd2b3`](https://github.com/JanisVepris/zpl-builder-php/commit/dabd2b3))
- `Enum\LabelFlip` (replaces the clashy `Enum\PrintOrientation`). ([`a936842`](https://github.com/JanisVepris/zpl-builder-php/commit/a936842))
- GitHub Actions CI workflow running `cs`, `stan`, and `test` as separate jobs across PHP 8.3 / 8.4 / 8.5. ([`2e25e50`](https://github.com/JanisVepris/zpl-builder-php/commit/2e25e50), [
  `f5c9e8d`](https://github.com/JanisVepris/zpl-builder-php/commit/f5c9e8d))
- `CHANGELOG.md` and `.gitattributes` (consumers of `composer require` no longer get `/test`, `/tmp`, `/.cache`, IDE configs, etc.). ([
  `2e25e50`](https://github.com/JanisVepris/zpl-builder-php/commit/2e25e50))
- `composer.json` keywords, support links, `prefer-stable`, `sort-packages`. ([`2e25e50`](https://github.com/JanisVepris/zpl-builder-php/commit/2e25e50))
- `ZplBuilder::getCommands()` accessor for test introspection and external rendering. ([`36243bc`](https://github.com/JanisVepris/zpl-builder-php/commit/36243bc))
- `ZplBuilder::hasFontPreset()`, `removeFontPreset()`, `getFontPresets()` for preset registry observability. ([`36243bc`](https://github.com/JanisVepris/zpl-builder-php/commit/36243bc))
- Full unit-test coverage of `ZplBuilder` — every public method now has at least one positive test, 53 tests in `ZplBuilderTest` total. ([
  `887d4ed`](https://github.com/JanisVepris/zpl-builder-php/commit/887d4ed))

### Changed

- `ZplBuilder::printQuantity()` emits `^PQ` immediately at the call site instead of being deferred to `end()`. ([`9fef2e2`](https://github.com/JanisVepris/zpl-builder-php/commit/9fef2e2))
- `ZplBuilder::fieldData()` auto-emits `^FH_` and hex-encodes when input contains `^` or `~`; clean input still produces plain `^FD<data>^FS`. ([
  `6d857e6`](https://github.com/JanisVepris/zpl-builder-php/commit/6d857e6))
- All `ZplCommand`, `ValueObject`, and exception classes marked `final`. Command and VO classes also `readonly` where compatible. ([
  `bb41000`](https://github.com/JanisVepris/zpl-builder-php/commit/bb41000))
- `FieldData`, `FieldHexIndicator`, and `FieldComment` now reject literal `^` / `~` in their inputs via `ValueAssert::stringNotContains`. ([
  `305e3f8`](https://github.com/JanisVepris/zpl-builder-php/commit/305e3f8))
- `FontSettings` instances are now lazily allocated on first access (via the new private `fontSettingsFor()` helper) instead of pre-creating all 36 cases up front in the constructor and `reset()`. ([
  `36243bc`](https://github.com/JanisVepris/zpl-builder-php/commit/36243bc))
- `render()` rewritten as `implode(…) . $separator` instead of a manual `.=` loop. Trailing newline behaviour preserved. ([`36243bc`](https://github.com/JanisVepris/zpl-builder-php/commit/36243bc))
- Every public `ZplBuilder` method now has a short docblock describing what it does and which ZPL command it emits. Methods that can throw also document the exception types via `@throws`. ([
  `436494f`](https://github.com/JanisVepris/zpl-builder-php/commit/436494f), [`caef0aa`](https://github.com/JanisVepris/zpl-builder-php/commit/caef0aa))
- Dropped the `jetbrains/phpstorm-attributes` dev dependency and removed `#[Pure]` from exception constructors. The attribute was IDE-only and didn't earn its keep. ([
  `caef0aa`](https://github.com/JanisVepris/zpl-builder-php/commit/caef0aa))
- PHP-CS-Fixer now enforces alphabetical class member ordering via `ordered_class_elements`. ([`3a49a05`](https://github.com/JanisVepris/zpl-builder-php/commit/3a49a05))

### Breaking changes

- `ZplBuilder::printQuantity()` is no longer implicit — labels without an explicit call no longer emit `^PQ1`. Add `->printQuantity($n)` if you need the command. ([
  `9fef2e2`](https://github.com/JanisVepris/zpl-builder-php/commit/9fef2e2))
- `ZplBuilder::changeFont()` / `addFontPreset()` / `applyFontPreset()` and `ChangeFont` / `FontPreset` now require `Enum\Font` instead of `int|string`. Migrate `'A'` → `Font::A`, `0` → `Font::Zero`,
  etc. ([`dabd2b3`](https://github.com/JanisVepris/zpl-builder-php/commit/dabd2b3))
- Enum case names converted to PascalCase. Notable: `Code128Mode::No_mode` → `None`, `Code128Mode::AUTO` → `Auto`, `Orientation::ROTATE_0` → `Rotate0`, `Encoding::USA1` → `Usa1`,
  `Encoding::CODE_PAGE_850` → `CodePage850`, `Encoding::UTF8` → `Utf8`, `PrintOrientation::NORMAL` → `Normal`. ([`6d1d325`](https://github.com/JanisVepris/zpl-builder-php/commit/6d1d325))
- `Enum\PrintOrientation` renamed to `Enum\LabelFlip`. The `PrintOrientationEnum` alias is gone — update imports. The `ZplCommand\PrintOrientation` VO and `ZplBuilder::printOrientation()` method names
  stay. ([`a936842`](https://github.com/JanisVepris/zpl-builder-php/commit/a936842))
- `ValueAssert::stringLength()` renamed to `stringLengthBytes()` and switched from `mb_strlen` to `strlen`. UTF-8 strings with multi-byte characters now consume more of the byte limit (matches ZPL's
  actual printer-buffer limit). ([`241fbc5`](https://github.com/JanisVepris/zpl-builder-php/commit/241fbc5), [`c4810fb`](https://github.com/JanisVepris/zpl-builder-php/commit/c4810fb))
- `ZplBuilder::fieldNum()` renamed to `fieldNumber()`. ([`3f3df1b`](https://github.com/JanisVepris/zpl-builder-php/commit/3f3df1b))
- `InvalidFieldCommentException` removed — `FieldComment` now throws `StringValueContainsBannedValuesException` for the same conditions. ([
  `305e3f8`](https://github.com/JanisVepris/zpl-builder-php/commit/305e3f8))
- All command/VO/exception classes are `final` — subclassing them is no longer possible. Use composition or the new `raw(string)` escape hatch. ([
  `bb41000`](https://github.com/JanisVepris/zpl-builder-php/commit/bb41000))
- `render()` / `(string)$b` no longer auto-appends `^XZ`. Call `->end()` explicitly to finalise the format. `end()` is now a regular fluent method that appends `EndFormat` like any other command. ([
  `1f8752c`](https://github.com/JanisVepris/zpl-builder-php/commit/1f8752c))
- `CommandAfterEndException` removed. The builder no longer guards against mutations after `end()` — composing valid ZPL (e.g. not adding fields after `end()`) is the caller's responsibility. ([
  `1f8752c`](https://github.com/JanisVepris/zpl-builder-php/commit/1f8752c))

## [0.30.2] and earlier

History prior to the v0.4 cleanup was not captured in this changelog. See `git log` and the GitHub releases page for details.
