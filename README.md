# zpl-builder-php

A small PHP 8.3+ library that generates [ZPL II](https://docs.zebra.com/us/en/printers/software/zpl-pg/c-getting-started.html) (Zebra Programming Language) label payloads via a fluent builder API. No runtime dependencies beyond PHP itself.

> **Status:** work in progress — the public API is **unstable until 1.0** and minor releases may include breaking changes. See [`CHANGELOG.md`](CHANGELOG.md) for the per-version breakdown.

## Installation

```bash
composer require janisvepris/zpl-builder-php
```

Supported PHP versions: **8.3, 8.4, 8.5**.

## Quick example

```php
use Janisvepris\ZplBuilder\Enum\Font;
use Janisvepris\ZplBuilder\ZplBuilder;

$zpl = (string) ZplBuilder::start()
    ->labelHome(30, 30)
    ->changeFont(Font::Zero, 40, 20)
    ->fieldOrigin(50, 50)
    ->fieldData('Hello, ZPL!')
    ->fieldOrigin(50, 120)
    ->barcodeDefaults(3, 3.0, 100)
    ->barcodeCode128('ABC123')
    ->printQuantity(1)
    ->end();

// ^XA^LH30,30^CF0,40,20^FO50,50^FDHello, ZPL!^FS^FO50,120^BY3,3.0,100^BCN,100,Y,N,N,N^FDABC123^FS^PQ1^XZ
```

Send the resulting string to a Zebra printer over its preferred transport (raw TCP on port 9100, USB, serial, etc.).

## Features

- Fluent builder with one method per ZPL command, named after the command's purpose (`fieldOrigin`, `changeFont`, `barcodeCode128`, …).
- Typed enums for ZPL parameters (`Orientation`, `Justify`, `Code128Mode`, `Font`, `Encoding`, …) instead of bare strings.
- Constructor-time validation on all command value objects — out-of-range numeric inputs throw a typed `RangeException` subclass before the printer ever sees them.
- Auto-escape of `^` and `~` in field data via `^FH` so user content can't accidentally be interpreted as commands.
- Escape hatch: `->raw('…')` for any ZPL fragment the builder doesn't yet model.
- Subclassable — no class is `final`, so downstream consumers can add their own fluent methods or command value objects.

## Escape hatch for unsupported commands

The library doesn't yet have dedicated methods for every ZPL command. For anything missing, pass a literal fragment through `raw()`:

```php
ZplBuilder::start()
    ->raw('^MD15')          // media darkness — no native method yet
    ->raw('^PR4,4,4')       // print rate
    ->end();
```

## Reference

- [Zebra ZPL II Programming Guide](https://www.zebra.com/content/dam/support-dam/en/documentation/unrestricted/guide/software/zpl-zbi2-pg-en.pdf)
- [Online command reference (alphabetical index)](https://docs.zebra.com/us/en/printers/software/zpl-pg/c-zpl-zpl-commands.html)

## License

MIT — see [`LICENSE`](LICENSE).
