# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The public API is **unstable until 1.0** — minor versions may include breaking changes.

## [Unreleased]

### Added

- `ZplBuilder::downloadFormat()` and `ZplCommand\DownloadFormat` add support for `^DF` (Download Format), opening a stored-format download so subsequent commands are saved under a name for later recall with `^XF`; the device defaults to `R:` (RAM) and the extension to `ZPL` ([`d5a5906`](https://github.com/JanisVepris/zpl-builder-php/commit/d5a5906))
- `ZplBuilder::hostFormat()` and `ZplCommand\HostFormat` add support for `^HF` (Host Format), sending a stored format from the printer back to the host; the device defaults to `R:` (RAM) and the extension to `ZPL` ([`f74086b`](https://github.com/JanisVepris/zpl-builder-php/commit/f74086b))
- `ZplBuilder::labelShift()` and `ZplCommand\LabelShift` add support for `^LS` (Label Shift), shifting all field positions left by -9999 to 9999 dots for Z-130/Z-220 compatibility; defaults to 0 (no shift) ([`cacfbf2`](https://github.com/JanisVepris/zpl-builder-php/commit/cacfbf2))
- `ZplBuilder::labelTop()` and `ZplCommand\LabelTop` add support for `^LT` (Label Top), moving the entire label format -120 to 120 dot rows relative to the top edge ([`295f173`](https://github.com/JanisVepris/zpl-builder-php/commit/295f173))
- `ZplBuilder::slew()` and `ZplCommand\Slew` add support for `^PF` (Slew Given Number of Dot Rows), feeding 0 to 32000 dot rows without printing to speed up labels with a blank bottom ([`0339bf4`](https://github.com/JanisVepris/zpl-builder-php/commit/0339bf4))
- `ZplBuilder::printMirror()` and `ZplCommand\PrintMirror` add support for `^PM` (Printing Mirror Image of Label), printing the entire label flipped left to right ([`43ac7c5`](https://github.com/JanisVepris/zpl-builder-php/commit/43ac7c5))
- `ZplBuilder::suppressBackfeed()` and `ZplCommand\SuppressBackfeed` add support for `^XB` (Suppress Backfeed), suppressing the forward feed to the tear-off position for the current label to improve batch-printing throughput ([`b70e001`](https://github.com/JanisVepris/zpl-builder-php/commit/b70e001))
- `ZplBuilder::changeMemoryLetters()` and `ZplCommand\ChangeMemoryLetters` add support for `^CM` (Change Memory Letter Designation), reassigning the printer's `B:`/`E:`/`R:`/`A:` memory-device letters, with `Enum\MemoryLetter` selecting each target (`None` to leave a letter unassigned) ([`ab2671e`](https://github.com/JanisVepris/zpl-builder-php/commit/ab2671e))
- `ZplBuilder::cacheOn()` and `ZplCommand\CacheOn` add support for `^CO` (Cache On), resizing the scalable-font character cache, with `Enum\CacheType` selecting the normal or internal (Asian-font) buffer ([`762ff82`](https://github.com/JanisVepris/zpl-builder-php/commit/762ff82))
- `ZplBuilder::codeValidation()` and `ZplCommand\CodeValidation` add support for `^CV` (Code Validation), toggling whether the printer validates each bar code's data and prints an error message in place of an invalid bar code ([`0ac38c5`](https://github.com/JanisVepris/zpl-builder-php/commit/0ac38c5))
- `ZplBuilder::mapClear()` and `ZplCommand\MapClear` add support for `^MC` (Map Clear), retaining the current label bitmap across labels instead of clearing it after printing ([`8a8772f`](https://github.com/JanisVepris/zpl-builder-php/commit/8a8772f))
- `ZplBuilder::mediaDarkness()` and `ZplCommand\MediaDarkness` add support for `^MD` (Media Darkness), adjusting print darkness by -30 to 30 relative to the printer's current setting ([`c1f2529`](https://github.com/JanisVepris/zpl-builder-php/commit/c1f2529))
- `ZplBuilder::mediaFeed()` and `ZplCommand\MediaFeed` add support for `^MF` (Media Feed), setting the media feed action at power-up and after head-close, with `Enum\MediaFeedAction` selecting each action ([`3f2fe1c`](https://github.com/JanisVepris/zpl-builder-php/commit/3f2fe1c))
- `ZplBuilder::maximumLabelLength()` and `ZplCommand\MaximumLabelLength` add support for `^ML` (Maximum Label Length), setting the maximum label length in dot rows (0 to 32000) for calibration ([`0e0cb9d`](https://github.com/JanisVepris/zpl-builder-php/commit/0e0cb9d))
- `ZplBuilder::printMode()` and `ZplCommand\PrintMode` add support for `^MM` (Print Mode), selecting the post-print action and prepeel option, with `Enum\PostPrintAction` selecting tear-off/peel-off/rewind/applicator/cutter/delayed-cutter ([`92d74c7`](https://github.com/JanisVepris/zpl-builder-php/commit/92d74c7))
- `ZplBuilder::mediaTracking()` and `ZplCommand\MediaTracking` add support for `^MN` (Media Tracking), telling the printer whether the media is continuous or non-continuous (web/mark sensing), with `Enum\MediaTrackingType` selecting the mode ([`ece0065`](https://github.com/JanisVepris/zpl-builder-php/commit/ece0065))
- `ZplBuilder::modeProtection()` and `ZplCommand\ModeProtection` add support for `^MP` (Mode Protection), locking individual control-panel mode functions, with `Enum\ProtectedMode` selecting which to disable (or `EnableAll`) ([`1068ee0`](https://github.com/JanisVepris/zpl-builder-php/commit/1068ee0))
- `ZplBuilder::mediaType()` and `ZplCommand\MediaType` add support for `^MT` (Media Type), selecting the print method, with `Enum\PrintMethod` choosing thermal transfer (ribbon) or direct thermal ([`3705006`](https://github.com/JanisVepris/zpl-builder-php/commit/3705006))
- `ZplBuilder::setUnits()` and `ZplCommand\SetUnits` add support for `^MU` (Set Units of Measurement), selecting dots/inches/millimetres via `Enum\MeasurementUnit` and optionally converting a format between resolutions ([`4b4116e`](https://github.com/JanisVepris/zpl-builder-php/commit/4b4116e))
- `ZplBuilder::headColdWarning()` and `ZplCommand\HeadColdWarning` add support for `^MW` (Modify Head Cold Warning), enabling or disabling the head cold warning indicator ([`b74e49b`](https://github.com/JanisVepris/zpl-builder-php/commit/b74e49b))
- `ZplBuilder::printRate()` and `ZplCommand\PrintRate` add support for `^PR` (Print Rate), setting the print, slew, and backfeed speeds, with `Enum\PrintSpeed` selecting each speed in inches per second ([`861386c`](https://github.com/JanisVepris/zpl-builder-php/commit/861386c))
- `ZplBuilder::startPrint()` and `ZplCommand\StartPrint` add support for `^SP` (Start Print), starting the print at a given dot row before the rest of the format is composed ([`cdf5b64`](https://github.com/JanisVepris/zpl-builder-php/commit/cdf5b64))
- `ZplBuilder::setMediaSensors()` and `ZplCommand\SetMediaSensors` add support for `^SS` (Set Media Sensors), overriding the web/media/ribbon/label-length calibration values, with optional LED-intensity and mark-sensing parameters ([`bef404e`](https://github.com/JanisVepris/zpl-builder-php/commit/bef404e))
- `ZplBuilder::setZpl()` and `ZplCommand\SetZpl` add support for `^SZ` (Set ZPL), selecting the ZPL language version, with `Enum\ZplMode` choosing legacy ZPL or ZPL II ([`4479b9a`](https://github.com/JanisVepris/zpl-builder-php/commit/4479b9a))
- `ZplBuilder::printerSleep()` and `ZplCommand\PrinterSleep` add support for `^ZZ` (Printer Sleep), placing battery-powered printers into idle/shutdown mode after a configurable idle time ([`a384475`](https://github.com/JanisVepris/zpl-builder-php/commit/a384475))
- `ZplBuilder::applicatorReprint()` and `ZplCommand\ApplicatorReprint` add support for `~PR` (Applicator Reprint), reprinting the last label on PAX/PAX2-series printers ([`e835f51`](https://github.com/JanisVepris/zpl-builder-php/commit/e835f51))
- `ZplBuilder::printStart()` and `ZplCommand\PrintStart` add support for `~PS` (Print Start), resuming printing on a printer that is in Pause Mode ([`9f6d754`](https://github.com/JanisVepris/zpl-builder-php/commit/9f6d754))
- `ZplBuilder::setDarkness()` and `ZplCommand\SetDarkness` add support for `~SD` (Set Darkness), setting the absolute print darkness (0 to 30) ([`047693b`](https://github.com/JanisVepris/zpl-builder-php/commit/047693b))
- `ZplBuilder::tearOffAdjust()` and `ZplCommand\TearOffAdjust` add support for `~TA` (Tear-off Adjust Position), shifting the media rest position (-120 to 120 dot rows) where the label is torn or cut ([`f5d1f0c`](https://github.com/JanisVepris/zpl-builder-php/commit/f5d1f0c))
- `ZplBuilder::calibrateRfidTransponder()` and `ZplCommand\CalibrateRfidTransponder` add support for `^HR` (Calibrate RFID Transponder Position), initiating an RFID transponder-position calibration that returns a results table to the host
- `ZplBuilder::searchWiredPrintServer()` and `ZplCommand\SearchWiredPrintServer` add support for `^NB` (Search for Wired Print Server during Network Boot), with `Enum\WiredPrintServerCheck` choosing whether to check for a wired print server at boot
- `ZplBuilder::networkId()` and `ZplCommand\NetworkId` add support for `^NI` (Network ID Number), assigning the printer's RS-485 network ID (1 to 999)
- `ZplBuilder::setSnmp()` and `ZplCommand\SetSnmp` add support for `^NN` (Set SNMP), setting the system name/contact/location and get/set/trap community names
- `ZplBuilder::primaryDevice()` and `ZplCommand\PrimaryDevice` add support for `^NP` (Set Primary/Secondary Device), with `Enum\NetworkDevice` choosing whether the printer's or print server's network settings are used at boot
- `ZplBuilder::wiredNetworkSettings()` and `ZplCommand\WiredNetworkSettings` add support for `^NS` (Change Wired Networking Settings), with `Enum\IpResolution` selecting IP resolution plus address/subnet/gateway and optional WINS/timeout/ARP/port parameters
- `ZplBuilder::setSmtp()` and `ZplCommand\SetSmtp` add support for `^NT` (Set SMTP), setting the SMTP server address and print-server domain for e-mail alerts
- `ZplBuilder::webAuthTimeout()` and `ZplCommand\WebAuthenticationTimeout` add support for `^NW` (Set Web Authentication Timeout Value), setting the printer web-page password timeout (0 to 255 minutes)
- `ZplBuilder::readAfiOrDsfidByte()` and `ZplCommand\ReadAfiOrDsfidByte` add support for `^RA` (Read AFI or DSFID Byte), with `Enum\RfidByteFormat`, `Enum\RfidMotion`, and `Enum\RfidByteType` selecting the output format, label motion, and byte to read
- `ZplBuilder::defineEpcDataStructure()` and `ZplCommand\DefineEpcDataStructure` add support for `^RB` (Define EPC Data Structure), defining the total bit size and per-partition bit sizes for EPC tag encoding
- `ZplBuilder::enableEasBit()` and `ZplCommand\EnableEasBit` add support for `^RE` (Enable/Disable E.A.S. Bit), toggling the Electronic Article Surveillance bit on supported ISO15693 tags
- `ZplBuilder::readWriteRfidFormat()` and `ZplCommand\ReadWriteRfidFormat` add support for `^RF` (Read or Write RFID Format), with `Enum\RfidOperation` and `Enum\RfidReadWriteFormat` selecting the read/write operation and data format
- `ZplBuilder::getRfidTagId()` and `ZplCommand\GetRfidTagId` add support for `^RI` (Get RFID Tag ID), reading a tag's serial number into a field, with `Enum\RfidDataOrder` selecting byte order
- `ZplBuilder::enableRfidMotion()` and `ZplCommand\EnableRfidMotion` add support for `^RM` (Enable RFID Motion), toggling whether the label moves when it reaches the program position
- `ZplBuilder::detectMultipleRfidTags()` and `ZplCommand\DetectMultipleRfidTags` add support for `^RN` (Detect Multiple RFID Tags in Encoding Field), toggling the multiple-tag check before reading or writing
- `ZplBuilder::rfidBlockRetries()` and `ZplCommand\RfidBlockRetries` add support for `^RR` (Specify RFID Retries for a Block), setting how many times the printer retries a single tag block (0 to 10)
- `ZplBuilder::setUpRfidParameters()` and `ZplCommand\SetUpRfidParameters` add support for `^RS` (Set Up RFID Parameters), configuring tag type, transponder position, void length, retry count, and error handling, with `Enum\RfidErrorHandling` and `Enum\ApplicatorSignal`
- `ZplBuilder::readRfidTag()` and `ZplCommand\ReadRfidTag` add support for `^RT` (Read RFID Tag), reading tag block data into a field (legacy command; `readWriteRfidFormat()` is preferred)
- `ZplBuilder::setRfidPowerLevels()` and `ZplCommand\SetRfidPowerLevels` add support for `^RW` (Set RFID Read and Write Power Levels), with `Enum\RfidPowerLevel` selecting the read and write power and an optional antenna port
- `ZplBuilder::setRfidTagPassword()` and `ZplCommand\SetRfidTagPassword` add support for `^RZ` (Set RFID Tag Password and Lock Tag), with `Enum\RfidPasswordMemoryBank` and `Enum\RfidLockStyle` selecting the Gen 2 memory bank and lock style
- `ZplBuilder::setAntennaParameters()` and `ZplCommand\SetAntennaParameters` add support for `^WA` (Set Antenna Parameters), with `Enum\Antenna` selecting the receive and transmit antenna
- `ZplBuilder::printDirectoryLabel()` and `ZplCommand\PrintDirectoryLabel` add support for `^WD` (Print Directory Label), listing bar codes/fonts/objects on a device, with `Enum\DirectoryDevice` selecting the device (including resident objects)
- `ZplBuilder::setWepMode()` and `ZplCommand\SetWepMode` add support for `^WE` (Set WEP Mode), configuring WEP encryption with `Enum\WepEncryptionMode`, `Enum\WepAuthenticationType`, and `Enum\WepKeyStorage` plus up to four encryption keys
- `ZplBuilder::encodeAfiOrDsfidByte()` and `ZplCommand\EncodeAfiOrDsfidByte` add support for `^WF` (Encode AFI or DSFID Byte), with `Enum\RfidWriteProtect` controlling write protection alongside the motion/format/byte-type enums
- `ZplBuilder::changeWirelessNetworkSettings()` and `ZplCommand\ChangeWirelessNetworkSettings` add support for `^WI` (Change Wireless Network Settings), mirroring `^NS` with `Enum\IpResolution` and optional trailing WINS/timeout/ARP/port parameters
- `ZplBuilder::setLeapParameters()` and `ZplCommand\SetLeapParameters` add support for `^WL` (Set LEAP Parameters), enabling Cisco LEAP authentication with a username and password, with `Enum\LeapMode` toggling the mode
- `ZplBuilder::setWirelessPassword()` and `ZplCommand\SetWirelessPassword` add support for `^WP` (Set Wireless Password), setting the four-digit wireless print-server password (emitted zero-padded)
- `ZplBuilder::setTransmitRate()` and `ZplCommand\SetTransmitRate` add support for `^WR` (Set Transmit Rate), toggling the 1/2/5.5/11 Mb/s transmit rates with `Enum\TransmitPower` selecting the power level
- `ZplBuilder::setWirelessCardValues()` and `ZplCommand\SetWirelessCardValues` add support for `^WS` (Set Wireless Card Values), setting the ESSID with `Enum\WirelessOperatingMode` and `Enum\WirelessPreamble`
- `ZplBuilder::writeRfidTag()` and `ZplCommand\WriteRfidTag` add support for `^WT` (Write/Encode Tag), encoding field data to the current RFID tag with block, retries, motion, write-protect, format, and verify options
- `ZplBuilder::verifyRfidEncoding()` and `ZplCommand\VerifyRfidEncoding` add support for `^WV` (Verify RFID Encoding Operation), toggling RFID write verification
- `ZplBuilder::networkConnect()` and `ZplCommand\NetworkConnect` add support for `~NC` (Network Connect), selecting an RS-485 printer by its three-digit network ID

## [0.62.0] - 2026-06-12

### Added

- `ZplBuilder::graphicCircle()` and `ZplCommand\GraphicCircle` add support for `^GC` (Graphic Circle). The border thickness accepts a minimum of 1 dot — the value the spec documents as the default when the parameter is omitted — rather than the parameter table's stated minimum of 2 ([`0ac98fd`](https://github.com/JanisVepris/zpl-builder-php/commit/0ac98fd))
- `ZplBuilder::graphicDiagonalLine()` and `ZplCommand\GraphicDiagonalLine` add support for `^GD` (Graphic Diagonal Line), with the `Enum\DiagonalOrientation` selecting the line's lean direction (`R`/`L`) ([`06ce633`](https://github.com/JanisVepris/zpl-builder-php/commit/06ce633))
- `ZplBuilder::graphicEllipse()` and `ZplCommand\GraphicEllipse` add support for `^GE` (Graphic Ellipse). As with `^GC`, the border thickness accepts a minimum of 1 dot — the spec's documented default — rather than the parameter table's stated minimum of 2 ([`d8d7fe3`](https://github.com/JanisVepris/zpl-builder-php/commit/d8d7fe3))
- `ZplBuilder::graphicField()` and `ZplCommand\GraphicField` add support for `^GF` (Graphic Field), downloading a bitmap directly into the printer at the current field origin, with the `Enum\GraphicFieldCompression` selecting the data encoding (`A`/`B`/`C`) ([`a434d6d`](https://github.com/JanisVepris/zpl-builder-php/commit/a434d6d))
- `ZplBuilder::graphicSymbol()` and `ZplCommand\GraphicSymbol` add support for `^GS` (Graphic Symbol); the symbol selector is emitted as `^FD` field data following the `^GS` command ([`180054e`](https://github.com/JanisVepris/zpl-builder-php/commit/180054e))
- `ZplBuilder::hostGraphic()` and `ZplCommand\HostGraphic` add support for `^HG` (Host Graphic), uploading a stored graphic to the host; the device defaults to `R:` (RAM) and the extension to `GRF` ([`d48795c`](https://github.com/JanisVepris/zpl-builder-php/commit/d48795c))
- `ZplBuilder::uploadGraphics()` and `ZplCommand\UploadGraphics` add support for `^HY` (Upload Graphics), uploading a stored graphic object to the host in any supported format (`GRF` raw bitmap or `PNG` compressed); the device defaults to `R:` (RAM) and the extension to `GRF` ([`8571eb1`](https://github.com/JanisVepris/zpl-builder-php/commit/8571eb1))
- `ZplBuilder::objectDelete()` and `ZplCommand\ObjectDelete` add support for `^ID` (Object Delete), deleting stored objects from a printer storage device; the `*` wildcard is accepted in the name and extension to delete groups of objects ([`1c60595`](https://github.com/JanisVepris/zpl-builder-php/commit/1c60595))
- `ZplBuilder::imageLoad()` and `ZplCommand\ImageLoad` add support for `^IL` (Image Load), loading a stored image at the origin and merging it with the label's field data; the device defaults to `R:` (RAM) and the extension to `GRF` ([`8e6236e`](https://github.com/JanisVepris/zpl-builder-php/commit/8e6236e))
- `ZplBuilder::imageMove()` and `ZplCommand\ImageMove` add support for `^IM` (Image Move), moving a stored image into the bitmap at the current field origin (like `^XG` without magnification); the device defaults to `R:` (RAM) and the extension to `GRF` ([`14fc3be`](https://github.com/JanisVepris/zpl-builder-php/commit/14fc3be))
- `ZplBuilder::imageSave()` and `ZplCommand\ImageSave` add support for `^IS` (Image Save), saving the current label format as a stored image; `$printAfterStore` (default `true`) controls whether the label also prints, and the device defaults to `R:` (RAM) and the extension to `GRF` ([`78bdf0b`](https://github.com/JanisVepris/zpl-builder-php/commit/78bdf0b))
- `ZplBuilder::recallGraphic()` and `ZplCommand\RecallGraphic` add support for `^XG` (Recall Graphic), recalling a stored graphic for printing with optional per-axis magnification (1 to 10, default 1); the device defaults to `R:` (RAM) and the extension to `GRF` ([`e34d1c5`](https://github.com/JanisVepris/zpl-builder-php/commit/e34d1c5))
- `ZplBuilder::downloadGraphics()` and `ZplCommand\DownloadGraphics` add support for `~DG` (Download Graphics), downloading an ASCII-hex graphic image into a printer storage device; the device defaults to `R:` (RAM) and the extension to `GRF` ([`8792fbe`](https://github.com/JanisVepris/zpl-builder-php/commit/8792fbe))
- `ZplBuilder::abortDownloadGraphic()` and `ZplCommand\AbortDownloadGraphic` add support for `~DN` (Abort Download Graphic), aborting an in-progress graphic download and returning the printer to normal print mode ([`0e97ec7`](https://github.com/JanisVepris/zpl-builder-php/commit/0e97ec7))
- `ZplBuilder::downloadObject()` and `ZplCommand\DownloadObject` add support for `~DY` (Download Graphics / Native TrueType or OpenType Font), with the `Enum\DownloadFormat` selecting the data encoding and `Enum\DownloadExtension` the stored object type; the data may be empty when the file is sent as a separate transmission ([`d96259a`](https://github.com/JanisVepris/zpl-builder-php/commit/d96259a))
- `ZplBuilder::eraseDownloadGraphics()` and `ZplCommand\EraseDownloadGraphics` add support for `~EG` (Erase Download Graphics), erasing all downloaded graphics from the printer's storage ([`828fdd7`](https://github.com/JanisVepris/zpl-builder-php/commit/828fdd7))

## [0.61.0] - 2026-06-11

### Added

- `ZplBuilderInterface` declares the full public contract of `ZplBuilder` — every public instance method except the static `start()` factory — so consumers can type-hint, dependency-inject, and mock against the interface instead of the concrete class. `ZplBuilder` implements it, and the authoritative method documentation now lives on the interface as the single source of truth (the implementation inherits it) ([`f5a3d20`](https://github.com/JanisVepris/zpl-builder-php/commit/f5a3d20))
- `ZplBuilder::when()` conditionally applies a callback to the builder. The predicate is a `bool` or a `callable(): bool`; when it is truthy `$callback` runs, otherwise the optional third argument `$elseCallback` runs if provided. Each callback receives the builder and mutates it in place (its return value is ignored), and `when()` always returns the builder so chaining continues ([`8b9f208`](https://github.com/JanisVepris/zpl-builder-php/commit/8b9f208))
- `ZplBuilder::barcodePostnet()` and `ZplCommand\BarcodePostnet` add support for `^BZ` (POSTNET Bar Code) ([`d193c26`](https://github.com/JanisVepris/zpl-builder-php/commit/d193c26))
- `ZplBuilder::barcodeDataMatrix()` and `ZplCommand\BarcodeDataMatrix` add support for `^BX` (Data Matrix Bar Code), with the `Enum\DataMatrixQuality` selecting the ECC level ([`7bae34e`](https://github.com/JanisVepris/zpl-builder-php/commit/7bae34e))
- `ZplBuilder::barcodeUpcA()` and `ZplCommand\BarcodeUpcA` add support for `^BU` (UPC-A Bar Code) ([`c02c0c8`](https://github.com/JanisVepris/zpl-builder-php/commit/c02c0c8))
- `ZplBuilder::barcodeTlc39()` and `ZplCommand\BarcodeTlc39` add support for `^BT` (TLC39 Bar Code) ([`f1d5eda`](https://github.com/JanisVepris/zpl-builder-php/commit/f1d5eda))
- `ZplBuilder::barcodeUpcEanExtensions()` and `ZplCommand\BarcodeUpcEanExtensions` add support for `^BS` (UPC/EAN Extensions Bar Code) ([`3731d58`](https://github.com/JanisVepris/zpl-builder-php/commit/3731d58))
- `ZplBuilder::barcodeRss()` and `ZplCommand\BarcodeRss` add support for `^BR` (RSS / Reduced Space Symbology Bar Code), with the `Enum\RssSymbologyType` selecting the RSS-14-family member ([`fb64baf`](https://github.com/JanisVepris/zpl-builder-php/commit/fb64baf))
- `ZplBuilder::barcodeQrCode()` and `ZplCommand\BarcodeQrCode` add support for `^BQ` (QR Code Bar Code), with the `Enum\QrModel` and `Enum\QrErrorCorrection` enums selecting the model and (optional) error-correction level ([`dd393d5`](https://github.com/JanisVepris/zpl-builder-php/commit/dd393d5))
- `ZplBuilder::barcodePlessey()` and `ZplCommand\BarcodePlessey` add support for `^BP` (Plessey Bar Code) ([`c830fb5`](https://github.com/JanisVepris/zpl-builder-php/commit/c830fb5))
- `ZplBuilder::barcodeMsi()` and `ZplCommand\BarcodeMsi` add support for `^BM` (MSI Bar Code), with the `Enum\MsiCheckDigit` selecting the check-digit scheme ([`348ffcd`](https://github.com/JanisVepris/zpl-builder-php/commit/348ffcd))
- `ZplBuilder::barcodeLogmars()` and `ZplCommand\BarcodeLogmars` add support for `^BL` (LOGMARS Bar Code) ([`98c8183`](https://github.com/JanisVepris/zpl-builder-php/commit/98c8183))
- `ZplBuilder::barcodeCodabar()` and `ZplCommand\BarcodeCodabar` add support for `^BK` (ANSI Codabar Bar Code), with the `Enum\CodabarCharacter` (`A`–`D`) selecting the start/stop characters ([`ac89753`](https://github.com/JanisVepris/zpl-builder-php/commit/ac89753))
- `ZplBuilder::barcodeStandard2of5()` and `ZplCommand\BarcodeStandard2of5` add support for `^BJ` (Standard 2 of 5 Bar Code) ([`971eba5`](https://github.com/JanisVepris/zpl-builder-php/commit/971eba5))
- `ZplBuilder::barcodeIndustrial2of5()` and `ZplCommand\BarcodeIndustrial2of5` add support for `^BI` (Industrial 2 of 5 Bar Code) ([`2b6df93`](https://github.com/JanisVepris/zpl-builder-php/commit/2b6df93))
- `ZplBuilder::barcodeMicroPdf417()` and `ZplCommand\BarcodeMicroPdf417` add support for `^BF` (Micro-PDF417 Bar Code) ([`4f21fce`](https://github.com/JanisVepris/zpl-builder-php/commit/4f21fce))
- `ZplBuilder::barcodeEan13()` and `ZplCommand\BarcodeEan13` add support for `^BE` (EAN-13 Bar Code) ([`713b327`](https://github.com/JanisVepris/zpl-builder-php/commit/713b327))
- `ZplBuilder::barcodeMaxiCode()` and `ZplCommand\BarcodeMaxiCode` add support for `^BD` (UPS MaxiCode Bar Code), with the `Enum\MaxiCodeMode` selecting the symbol mode ([`d0fb445`](https://github.com/JanisVepris/zpl-builder-php/commit/d0fb445))
- `ZplBuilder::barcodeCodablock()` and `ZplCommand\BarcodeCodablock` add support for `^BB` (CODABLOCK Bar Code), with the `Enum\CodablockMode` (`ModeA`/`ModeE`/`ModeF`) selecting the character set ([`ea3ddb6`](https://github.com/JanisVepris/zpl-builder-php/commit/ea3ddb6))
- `ZplBuilder::barcodeCode93()` and `ZplCommand\BarcodeCode93` add support for `^BA` (Code 93 Bar Code) ([`7e8d454`](https://github.com/JanisVepris/zpl-builder-php/commit/7e8d454))
- `ZplBuilder::barcodeUpcE()` and `ZplCommand\BarcodeUpcE` add support for `^B9` (UPC-E Bar Code) ([`0dd033e`](https://github.com/JanisVepris/zpl-builder-php/commit/0dd033e))
- `ZplBuilder::barcodeEan8()` and `ZplCommand\BarcodeEan8` add support for `^B8` (EAN-8 Bar Code) ([`c1d6020`](https://github.com/JanisVepris/zpl-builder-php/commit/c1d6020))
- `ZplBuilder::barcodePdf417()` and `ZplCommand\BarcodePdf417` add support for `^B7` (PDF417 Bar Code) ([`637b847`](https://github.com/JanisVepris/zpl-builder-php/commit/637b847))
- `ZplBuilder::barcodePlanetCode()` and `ZplCommand\BarcodePlanetCode` add support for `^B5` (Planet Code Bar Code) ([`d2c4ed5`](https://github.com/JanisVepris/zpl-builder-php/commit/d2c4ed5))
- `ZplBuilder::barcodeCode49()` and `ZplCommand\BarcodeCode49` add support for `^B4` (Code 49 Bar Code) ([`f1e9487`](https://github.com/JanisVepris/zpl-builder-php/commit/f1e9487))
- `ZplBuilder::barcodeCode39()` and `ZplCommand\BarcodeCode39` add support for `^B3` (Code 39 / USD-3 / 3 of 9 Bar Code) ([`6d29a39`](https://github.com/JanisVepris/zpl-builder-php/commit/6d29a39))
- `ZplBuilder::barcodeInterleaved2of5()` and `ZplCommand\BarcodeInterleaved2of5` add support for `^B2` (Interleaved 2 of 5 Bar Code) ([`d8f948f`](https://github.com/JanisVepris/zpl-builder-php/commit/d8f948f))
- `ZplBuilder::barcodeCode11()` and `ZplCommand\BarcodeCode11` add support for `^B1` (Code 11 / USD-8 Bar Code) ([`aaa9b54`](https://github.com/JanisVepris/zpl-builder-php/commit/aaa9b54))
- `ZplBuilder::barcodeAztec()` and `ZplCommand\BarcodeAztec` add support for `^B0` (Aztec Bar Code) ([`6b5470e`](https://github.com/JanisVepris/zpl-builder-php/commit/6b5470e), [`f5bb0fa`](https://github.com/JanisVepris/zpl-builder-php/commit/f5bb0fa))

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
