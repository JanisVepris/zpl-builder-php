# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The public API is **unstable until 1.0** — minor versions may include breaking changes.

## [Unreleased]

### Added
- `Util\FieldDataEncoder::escape()` hex-escapes `^`, `~`, and the indicator char for safe inclusion of arbitrary text in `^FD` field data.
- `Util\ValueAssert::stringNotContains()` for asserting strings are free of banned substrings; `StringValueContainsBannedValuesException` raised on violation.
- `Enum\Font` (36 cases: `A`–`Z`, `Zero`–`Nine`) replaces the loose `int|string` font key.
- `Enum\LabelFlip` (renamed from `Enum\PrintOrientation` to avoid clashing with `ZplCommand\PrintOrientation`).
- `ZplCommand\RawCommand` value object + `ZplBuilder::raw(string)` escape hatch for emitting arbitrary ZPL fragments.

### Changed
- `ZplBuilder::fieldData()` auto-escapes `^` / `~` in user input by emitting `^FH_` and hex-encoding the data. Clean input still produces plain `^FD<data>^FS`.
- `ZplBuilder::printQuantity()` now emits `^PQ` immediately at the call site instead of being deferred to `end()`. No `^PQ` is emitted unless explicitly requested.
- `ZplBuilder::render()` is now non-mutating — calling `(string)$builder` no longer flips `$formatEnded` or appends to `$commands`, so repeated rendering and mid-build debug logging are safe.
- `ZplBuilder::reset()` now clears `$fontPresets` and `$printNewlines` along with the other per-label state.
- `ZplBuilder::fieldNum()` renamed to `fieldNumber()`.
- `ValueAssert::stringLength()` renamed to `stringLengthBytes()` and switched from `mb_strlen` to `strlen` so the byte-counting semantics match ZPL's printer-buffer limit and the parameter names.
- `FloatValueOutOfRangeException` now formats float values with `%g` instead of `%d`.
- Enum cases across `Code128Mode`, `Encoding`, `Orientation`, `PrintOrientation` (now `LabelFlip`) converted to PascalCase. Notable: `No_mode` → `None`, `ROTATE_0` → `Rotate0`, `USA1` → `Usa1`, `CODE_PAGE_850` → `CodePage850`.
- All `ZplCommand`, `ValueObject`, and exception classes marked `final`. Command and VO classes additionally marked `readonly` where compatible.
- `FieldData`, `FieldHexIndicator`, and `FieldComment` now reject literal `^` / `~` in their inputs via `ValueAssert::stringNotContains`.

### Removed
- `InvalidFieldCommentException` (replaced by `StringValueContainsBannedValuesException`).
- `mb_str_functions` PHP-CS-Fixer rule from `.php-cs-fixer.dist.php` (conflicts with byte-level protocol semantics).

## [0.30.2] and earlier

History prior to the v0.4 cleanup was not captured in this changelog. See `git log` and the GitHub releases page for details.
