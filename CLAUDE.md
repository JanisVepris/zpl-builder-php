# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A PHP 8.3+ library that generates ZPL (Zebra Programming Language) label payloads via a fluent builder API. Distributed as a Composer package (`janisvepris/zpl-builder-php`), no runtime dependencies
beyond PHP itself. The README marks it as a work-in-progress — public API is unstable.

## Commands

All commands are defined as Composer scripts in `composer.json` and should be invoked through Composer so the bundled vendor binaries are used:

- `composer test` — run the PHPUnit suite (`test/Unit`)
- `composer stan` — run PHPStan at level 9 against `src/` and `test/`
- `composer cs` — check style with PHP-CS-Fixer (dry-run, shows diff)
- `composer cs-fix` — apply PHP-CS-Fixer changes
- `composer check-all` — runs `cs`, `stan`, `test` in order (read-only; safe for CI)
- `composer build` — runs `cs-fix`, `stan`, `test` (writes formatting changes)

Run a single test or a single class:

```bash
vendor/bin/phpunit --filter ZplBuilderTest
vendor/bin/phpunit test/Unit/Path/To/SomeTest.php
```

PHPUnit is configured with `failOnRisky=true`, `failOnWarning=true`, and `beStrictAboutCoverageMetadata=true`. Every test class must declare `#[CoversClass]` / `@covers` (enforced by
`php_unit_test_class_requires_covers` in `.php-cs-fixer.dist.php`); `test/UnitTestCase.php` uses `@coversNothing` as the base case.

PHPStan tmp dir is `.cache/phpstan`; PHP-CS-Fixer cache is `.cache/php-cs-fixer`; PHPUnit cache is `.cache/phpunit`. Treat `.cache/` as disposable.

## Architecture

The library has three layers, and the boundary between them is the most important thing to preserve when adding features:

### 1. `ZplBuilder` (entry point) — `src/ZplBuilder.php`

The fluent facade. Construction is via the static factory `ZplBuilder::start()`, which immediately appends a `StartFormat` (`^XA`) command. Every public mutation method appends one or more
`ZplCommand` instances to an internal `$commands` array and returns `$this` for chaining.

Stateful concerns owned by the builder (not by individual commands):

- `BarcodeDefaultSettings` — remembers the last `^BY` values so `barcodeCode128()` can inherit `height` when no explicit value is passed.
- `$fontSettings` — a map keyed by `Enum\Font` case values (`A`–`Z`, `0`–`9`) of `FontSettings` instances (lazily allocated on first access with defaults `height=9, width=5`). `changeFont()` writes only explicitly-provided dimensions into the cache and then always emits `^CF` with both height and width — omitted dimensions fall back to the cached value, which is the per-font default on the first call. So `changeFont(Font::A)` with no other args emits `^CFA,9,5`, and a later `changeFont(Font::A, width: 20)` emits `^CFA,9,20` (height remembered from the prior call). The builder's font-accepting methods (`changeFont`, `addFontPreset`, `applyFontPreset`) all type-hint `Enum\Font`.
- `$printNewlines` — when toggled, `render()` puts `PHP_EOL` between commands. Off by default; the emitted ZPL is a single contiguous string.

`ZplBuilder` implements `Stringable`; `__toString()` calls `render()`, which simply iterates `$this->commands` and concatenates their string forms. Render is pure — it never appends to `$this->commands` or otherwise mutates state. To finalise a format with `^XZ`, call `->end()` explicitly — it's a regular fluent method that appends `EndFormat` like any other command. The builder enforces no structural invariants beyond what `addCommand()` guarantees (validated VO inputs); composing valid ZPL (e.g. not adding fields after `end()`) is the caller's responsibility.

`printQuantity($n)` is a regular fluent method — it emits `^PQ<n>` immediately at the call site. A label without an explicit call emits no `^PQ`.

### 2. `ZplCommand` value objects — `src/ZplCommand/*`

Each ZPL command (`^XA`, `^FD`, `^FO`, `^BC`, `^CF`, `^CI`, `^FB`, …) is a `readonly class` implementing the `ZplCommand` interface (extends `Stringable`). They are immutable value objects: constructor validates inputs via `Util\ValueAssert`, and `__toString()` returns the formatted ZPL fragment built with `sprintf`. The trivial commands with no properties (`StartFormat`, `EndFormat`, `FieldSeparator`) are also `readonly class` — even though `readonly` is a no-op on a class without state, it keeps the contract uniform across the layer and forces any subclasses to remain readonly. No class is `final`; the library is meant to be freely extensible by downstream consumers (subclasses of a `readonly` class must themselves be `readonly`, per PHP).

`ZplCommand\RawCommand` exists as an escape hatch for callers who need to emit arbitrary ZPL the builder doesn't natively support — reached via `ZplBuilder::raw(string)`.

When adding a new ZPL command:

1. Create `src/ZplCommand/MyCommand.php` as a `readonly class` implementing `ZplCommand`. Expose two public typed constants: `public const string COMMAND = '^XX';` (the literal command sigil) and `public const string FORMAT = '...';` (the parameter-only sprintf template, or `''` for parameter-less commands). `__toString()` should be `return self::COMMAND . sprintf(self::FORMAT, …);` for parameterized commands, or just `return self::COMMAND;` for parameter-less.
2. Validate all numeric/string inputs in the constructor using `ValueAssert::int|float|stringLengthBytes|hexValue|stringNotContains`. Don't re-implement range checks inline.
3. Convert booleans destined for ZPL output via `Util\BoolToStr::conv()` (returns `'Y'`/`'N'`).
4. Add a fluent method to `ZplBuilder` that constructs the command and routes through `addCommand()` (never push to `$commands` directly — `addCommand()` is the single insertion point and may grow guards in future).
5. If the command pairs with `^FD ... ^FS` (most field-producing commands do), follow the `barcodeCode128()` pattern: emit the command, then call `$this->fieldData($data)`, which appends both `FieldData` and `FieldSeparator` (and auto-emits `^FH_` with hex-escaping if `$data` contains `^` or `~`).
6. Reference the documentation to know exactly which parameters are required vs optional, and their valid ranges.

### 3. Enums, exceptions, helpers — `src/Enum`, `src/Exception`, `src/Util`

- `Enum/*` — backed string enums whose `value` is the literal character ZPL expects (e.g. `Orientation::Rotate0 = 'N'`, `Code128Mode::None = 'N'`, `LabelFlip::Normal = 'N'`). Cases are PascalCase. Always prefer adding an enum case to passing raw strings.
- `Exception/*` — all custom exceptions extend the SPL exception that best matches their semantics (`RangeException`, `RuntimeException`, `InvalidArgumentException`, `UnexpectedValueException`, etc.). Not `final` — downstream consumers may extend them.
- `Util/ValueAssert` — single source of truth for input validation. `int` / `float` cover numeric range checks (general ZPL range is `0..32000`; specific commands pass narrower bounds). `stringLengthBytes` checks byte length (matches ZPL's printer-buffer limit, not character count). `stringNotContains` rejects strings containing banned substrings (defaults to `^` / `~`, the ZPL command terminators). `hexValue` checks that a string is hex-digit only.
- `Util/BoolToStr` — the only place that should map `bool` to `'Y'`/`'N'`.
- `Util/FieldDataEncoder` — `escape(string $raw, string $indicator = '_')` hex-escapes `^`, `~`, and the indicator itself for inclusion in `^FD` data after a `^FH<indicator>` declaration. Used internally by `ZplBuilder::fieldData()` when the input contains banned characters.

## Conventions

- `declare(strict_types=1);` at the top of every PHP file (enforced by PHP-CS-Fixer).
- PSR-12 plus `@PhpCsFixer` ruleset; short array syntax; trailing commas in multiline arrays/arguments/parameters/match; no Yoda style; `global_namespace_import` is on for classes but **off for
  functions and constants** — call global functions as `\sprintf(...)` only if the fixer requires it (it generally doesn't because they're called bare across the codebase).
- PSR-4: `Janisvepris\ZplBuilder\` → `src/`, `Janisvepris\ZplBuilder\Test\` → `test/`.
- PHPStan level 9 with `checkExplicitMixedMissingReturn` and `reportAlwaysTrueInLastCondition` enabled — every new method needs full type coverage including generic array shapes (`@var Commands[]`
  style).
- All exception-throwing methods document with `/** @throws ... */`.
- Constants are typed (`private const string COMMAND = ...`) — this is a PHP 8.3 feature and is used consistently.

## Changelog

`CHANGELOG.md` follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format with a project-specific section ordering: **Fixed**, **Added**, **Changed**, **Breaking changes** (in that order).

When recording a change:

- Put new work under `[Unreleased]` until a release is tagged. Don't backfill the historical version sections (`[0.30.2]` and earlier) — those tags predate this changelog and the per-tag context isn't reliably recoverable.
- Every bullet ends with the relevant commit hash(es) as full Markdown links so they're clickable everywhere — GitHub file view, release notes, etc. Inline form: ``([`305e3f8`](https://github.com/JanisVepris/zpl-builder-php/commit/305e3f8))``. For multiple commits, comma-separate inside one parenthesis: ``([`305e3f8`](…), [`6d857e6`](…))``. Reference-style (bottom-of-file) links were tried and reverted: they don't survive being copied into a standalone release-notes draft on GitHub.
- Pick the section by user-facing impact, not by implementation:
  - **Fixed** — bug fixes (incorrect output, broken behaviour, missing safety check that produced wrong ZPL).
  - **Added** — new public APIs, new value objects, new tooling/config files.
  - **Changed** — behavioural shifts in existing APIs that don't require callers to update their code.
  - **Breaking changes** — anything that requires callers to update code: renames, removed APIs, type-narrowed parameters, removed default behaviours, `final` on previously-extensible classes.
- A change with both a fix and a breaking-change aspect (e.g. tightening validation that now throws) lives in **Breaking changes** with the fix nature called out in the description.
