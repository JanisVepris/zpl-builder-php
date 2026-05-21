# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The public API is **unstable until 1.0** — minor versions may include breaking changes.

## [Unreleased]

### Fixed

- `FloatValueOutOfRangeException` formatted float values with `%d`, so `-0.5` rendered as `0`. Now uses `%g`. (`241fbc5`)
- `^FD` field data containing literal `^` or `~` produced broken ZPL — the printer interpreted them as the start of a new command. `fieldData()` now auto-escapes via `^FH_` + hex encoding; the VO itself rejects raw `^` / `~`. (`305e3f8`, `6d857e6`)
- `(string) $builder` permanently flipped `$formatEnded`, so logging a debug copy mid-build broke the builder. `render()` is now a pure iteration over `$this->commands`; the `$formatEnded` bookkeeping is gone entirely. (`7424e4b`, follow-up)
- `ZplBuilder::reset()` left `$fontPresets` and `$printNewlines` untouched — partial reset is surprising. It now clears them too. (`60ae6ce`)

### Added

- `Util\ValueAssert::stringNotContains()` + `StringValueContainsBannedValuesException` for asserting strings are free of banned substrings. (`305e3f8`)
- `Util\FieldDataEncoder::escape()` — hex-escapes `^`, `~`, and the indicator character for safe inclusion in `^FD` field data. (`6d857e6`)
- `ZplBuilder::raw(string)` escape hatch + `ZplCommand\RawCommand` VO for emitting arbitrary ZPL fragments the builder doesn't natively support. (`7e8d829`)
- `Enum\Font` with 36 cases (`A`–`Z`, `Zero`–`Nine`) replacing the loose `int|string` font key. (`dabd2b3`)
- `Enum\LabelFlip` (replaces the clashy `Enum\PrintOrientation`). (`a936842`)
- GitHub Actions CI workflow running `composer check-all` against PHP 8.3 + 8.4. (`2e25e50`)
- `CHANGELOG.md` and `.gitattributes` (consumers of `composer require` no longer get `/test`, `/tmp`, `/.cache`, IDE configs, etc.). (`2e25e50`)
- `composer.json` keywords, support links, `prefer-stable`, `sort-packages`. (`2e25e50`)

### Changed

- `ZplBuilder::printQuantity()` emits `^PQ` immediately at the call site instead of being deferred to `end()`. (`9fef2e2`)
- `ZplBuilder::fieldData()` auto-emits `^FH_` and hex-encodes when input contains `^` or `~`; clean input still produces plain `^FD<data>^FS`. (`6d857e6`)
- All `ZplCommand`, `ValueObject`, and exception classes marked `final`. Command and VO classes also `readonly` where compatible. (`bb41000`)
- `FieldData`, `FieldHexIndicator`, and `FieldComment` now reject literal `^` / `~` in their inputs via `ValueAssert::stringNotContains`. (`305e3f8`)

### Breaking changes

- `ZplBuilder::printQuantity()` is no longer implicit — labels without an explicit call no longer emit `^PQ1`. Add `->printQuantity($n)` if you need the command. (`9fef2e2`)
- `ZplBuilder::changeFont()` / `addFontPreset()` / `applyFontPreset()` and `ChangeFont` / `FontPreset` now require `Enum\Font` instead of `int|string`. Migrate `'A'` → `Font::A`, `0` → `Font::Zero`, etc. (`dabd2b3`)
- Enum case names converted to PascalCase. Notable: `Code128Mode::No_mode` → `None`, `Code128Mode::AUTO` → `Auto`, `Orientation::ROTATE_0` → `Rotate0`, `Encoding::USA1` → `Usa1`, `Encoding::CODE_PAGE_850` → `CodePage850`, `Encoding::UTF8` → `Utf8`, `PrintOrientation::NORMAL` → `Normal`. (`6d1d325`)
- `Enum\PrintOrientation` renamed to `Enum\LabelFlip`. The `PrintOrientationEnum` alias is gone — update imports. The `ZplCommand\PrintOrientation` VO and `ZplBuilder::printOrientation()` method names stay. (`a936842`)
- `ValueAssert::stringLength()` renamed to `stringLengthBytes()` and switched from `mb_strlen` to `strlen`. UTF-8 strings with multi-byte characters now consume more of the byte limit (matches ZPL's actual printer-buffer limit). (`241fbc5`, `c4810fb`)
- `ZplBuilder::fieldNum()` renamed to `fieldNumber()`. (`3f3df1b`)
- `InvalidFieldCommentException` removed — `FieldComment` now throws `StringValueContainsBannedValuesException` for the same conditions. (`305e3f8`)
- All command/VO/exception classes are `final` — subclassing them is no longer possible. Use composition or the new `raw(string)` escape hatch. (`bb41000`)
- `render()` / `(string)$b` no longer auto-appends `^XZ`. Call `->end()` explicitly to finalise the format. `end()` is now a regular fluent method that appends `EndFormat` like any other command.
- `CommandAfterEndException` removed. The builder no longer guards against mutations after `end()` — composing valid ZPL (e.g. not adding fields after `end()`) is the caller's responsibility.

## [0.30.2] and earlier

History prior to the v0.4 cleanup was not captured in this changelog. See `git log` and the GitHub releases page for details.
