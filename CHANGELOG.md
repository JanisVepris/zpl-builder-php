# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The public API is **unstable until 1.0** — minor versions may include breaking changes.

## [Unreleased]

### Fixed

- `^BY` (barcode defaults) formatted the wide-to-narrow ratio with `%0.1f`, which honours `LC_NUMERIC`. On comma-decimal locales (e.g. `de_DE`, `fr_FR`) the emitted ZPL became `^BY2,3,0,100` and was parsed by the printer as four arguments. Now uses `%0.1F` for locale-independent output. ([`f212cfd`](https://github.com/JanisVepris/zpl-builder-php/commit/f212cfd))

### Breaking changes

- `ZplCommand\ChangeFont`, `ValueObject\FontPreset`, `FontSettings`, and `BarcodeDefaultSettings` constructors now validate height/width (and barcode module width / ratio) via `ValueAssert` and throw `IntegerValueOutOfRangeException` / `FloatValueOutOfRangeException` for out-of-range inputs. Previously the `ChangeFont` and `FontPreset` VOs accepted any int and the settings classes only validated through their setters, so direct instantiation could produce out-of-spec `^CF` output. ([`e7e1a1b`](https://github.com/JanisVepris/zpl-builder-php/commit/e7e1a1b))

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
