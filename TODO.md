# Unimplemented ZPL II commands

Checklist of ZPL II commands documented in the [Zebra ZPL II Programming Guide](https://www.servopack.de/support/zebra/ZPLII-Prog.pdf) that this library does not yet model. Implemented commands live in [`src/ZplCommand/`](src/ZplCommand/) and are not listed here.

Until a command has a dedicated builder method, [`ZplBuilder::raw('…')`](src/ZplBuilder.php) is the escape hatch.

## Fonts, fields and text

- [ ] `^A` — Scalable/Bitmapped Font (per-field)
- [ ] `^A@` — Use Font Name to Call Font
- [ ] `^CW` — Font Identifier
- [ ] `^FC` — Field Clock (Real-Time Clock data)
- [ ] `^FM` — Multiple Field Origin Locations
- [ ] `^FP` — Field Parameter
- [ ] `^FR` — Field Reverse Print
- [ ] `^FT` — Field Typeset
- [ ] `^FV` — Field Variable
- [ ] `^KD` — Select Date and Time Format (for Real-Time Clock)
- [ ] `^SE` — Select Encoding
- [ ] `^SF` — Serialization Field
- [ ] `^SL` — Set Mode and Language (for Real-Time Clock)
- [ ] `^SN` — Serialization Data
- [ ] `^SO` — Set Offset (for Real-Time Clock)
- [ ] `^ST` — Set Date and Time (for Real-Time Clock)
- [ ] `^TO` — Transfer Object

## Barcodes

- [ ] `^B0` — Aztec
- [ ] `^B1` — Code 11
- [ ] `^B2` — Interleaved 2 of 5
- [ ] `^B3` — Code 39
- [ ] `^B4` — Code 49
- [ ] `^B5` — Planet Code
- [ ] `^B7` — PDF417
- [ ] `^B8` — EAN-8
- [ ] `^B9` — UPC-E
- [ ] `^BA` — Code 93
- [ ] `^BB` — CODABLOCK
- [ ] `^BD` — UPS MaxiCode
- [ ] `^BE` — EAN-13
- [ ] `^BF` — Micro-PDF417
- [ ] `^BI` — Industrial 2 of 5
- [ ] `^BJ` — Standard 2 of 5
- [ ] `^BK` — ANSI Codabar
- [ ] `^BL` — LOGMARS
- [ ] `^BM` — MSI
- [ ] `^BP` — Plessey
- [ ] `^BQ` — QR Code
- [ ] `^BR` — RSS (Reduced Space Symbology)
- [ ] `^BS` — UPC/EAN Extensions
- [ ] `^BT` — TLC39
- [ ] `^BU` — UPC-A
- [ ] `^BX` — Data Matrix
- [ ] `^BZ` — POSTNET

## Graphics and images

- [ ] `^GC` — Graphic Circle
- [ ] `^GD` — Graphic Diagonal Line
- [ ] `^GE` — Graphic Ellipse
- [ ] `^GF` — Graphic Field
- [ ] `^GS` — Graphic Symbol
- [ ] `^ID` — Object Delete
- [ ] `^IL` — Image Load
- [ ] `^IM` — Image Move
- [ ] `^IS` — Image Save
- [ ] `^XG` — Recall Graphic
- [ ] `^HG` — Host Graphic
- [ ] `^HY` — Upload Graphics
- [ ] `~DG` — Download Graphics
- [ ] `~DN` — Abort Download Graphic
- [ ] `~DY` — Download Graphics / Native TrueType or OpenType Font
- [ ] `~EG` — Erase Download Graphics

## Label layout and format control

- [ ] `^LS` — Label Shift
- [ ] `^LT` — Label Top
- [ ] `^PF` — Slew Given Number of Dot Rows
- [ ] `^PM` — Printing Mirror Image of Label
- [ ] `^XB` — Suppress Backfeed
- [ ] `^DF` — Download Format
- [ ] `^HF` — Host Format

## Printing control and media

- [ ] `^PR` — Print Rate
- [ ] `~PR` — Applicator Reprint
- [ ] `~PS` — Print Start
- [ ] `~SD` — Set Darkness
- [ ] `~TA` — Tear-off Adjust Position
- [ ] `^MC` — Map Clear
- [ ] `^MD` — Media Darkness
- [ ] `^MF` — Media Feed
- [ ] `^ML` — Maximum Label Length
- [ ] `^MM` — Print Mode
- [ ] `^MN` — Media Tracking
- [ ] `^MP` — Mode Protection
- [ ] `^MT` — Media Type
- [ ] `^MU` — Set Units of Measurement
- [ ] `^MW` — Modify Head Cold Warning
- [ ] `^SP` — Start Print
- [ ] `^SS` — Set Media Sensors
- [ ] `^SZ` — Set ZPL
- [ ] `^ZZ` — Printer Sleep
- [ ] `^CM` — Change Memory Letter Designation
- [ ] `^CO` — Cache On
- [ ] `^CV` — Code Validation

## Host I/O, diagnostics, printer state (lower priority — typically managed out-of-band)

- [ ] `^HH` — Configuration Label Return
- [ ] `^HV` — Host Verification
- [ ] `^HW` — Host Directory List
- [ ] `^HZ` — Display Description Information
- [ ] `~HB` — Battery Status
- [ ] `~HD` — Head Diagnostic
- [ ] `~HI` — Host Identification
- [ ] `~HM` — Host RAM Status
- [ ] `~HS` — Host Status Return
- [ ] `~HU` — Return ZebraNet Alert Configuration
- [ ] `^JB` — Initialize Flash Memory
- [ ] `^JJ` — Set Auxiliary Port
- [ ] `^JM` — Set Dots per Millimeter
- [ ] `^JS` — Sensor Select
- [ ] `^JT` — Head Test Interval
- [ ] `^JU` — Configuration Update
- [ ] `^JW` — Set Ribbon Tension
- [ ] `^JZ` — Reprint After Error
- [ ] `~JA` — Cancel All
- [ ] `~JB` — Reset Optional Memory
- [ ] `~JC` — Set Media Sensor Calibration
- [ ] `~JD` — Enable Communications Diagnostics
- [ ] `~JE` — Disable Diagnostics
- [ ] `~JF` — Set Battery Condition
- [ ] `~JG` — Graphing Sensor Calibration
- [ ] `~JL` — Set Label Length
- [ ] `~JN` — Head Test Fatal
- [ ] `~JO` — Head Test Non-Fatal
- [ ] `~JP` — Pause and Cancel Format
- [ ] `~JR` — Power On Reset
- [ ] `~JS` — Change Backfeed Sequence
- [ ] `~JX` — Cancel Current Partially Input Format
- [ ] `~KB` — Kill Battery
- [ ] `~RO` — Reset Advanced Counter
- [ ] `^KL` — Define Language
- [ ] `^KN` — Define Printer Name
- [ ] `^KP` — Define Password
- [ ] `^SC` — Set Serial Communications
- [ ] `^SQ` — Halt ZebraNet Alert
- [ ] `^SR` — Set Printhead Resistance
- [ ] `^SX` — Set ZebraNet Alert
- [ ] `~DB` — Download Bitmap Font
- [ ] `~DE` — Download Encoding
- [ ] `~DS` — Download Intellifont (Scalable Font)
- [ ] `~DT` — Download Bounded TrueType Font
- [ ] `~DU` — Download Unbounded TrueType Font

## Networking, wireless and RFID (likely out of scope for label generation)

- [ ] `^NB` — Search for Wired Print Server during Network Boot
- [ ] `^NI` — Network ID Number
- [ ] `^NN` — Set SNMP
- [ ] `^NP` — Set Primary/Secondary Device
- [ ] `^NS` — Change Networking Settings
- [ ] `^NT` — Set SMTP
- [ ] `^NW` — Set Web Authentication Timeout Value
- [ ] `~NC` — Network Connect
- [ ] `~NR` — Set All Network Printers Transparent
- [ ] `~NT` — Set Currently Connected Printer Transparent
- [ ] `^WA` — Set Antenna Parameters
- [ ] `^WD` — Print Directory Label
- [ ] `^WE` — Set WEP Mode
- [ ] `^WF` — Encode AFI or DSFID Byte
- [ ] `^WI` — Change Wireless Network Settings
- [ ] `^WL` — Set LEAP Parameters
- [ ] `^WP` — Set Wireless Password
- [ ] `^WR` — Set Transmit Rate
- [ ] `^WS` — Set Wireless Card Values
- [ ] `^WT` — Write (Encode) Tag
- [ ] `^WV` — Verify RFID Encoding Operation
- [ ] `~WC` — Print Configuration Label
- [ ] `~WL` — Print Network Configuration Label
- [ ] `~WR` — Reset Wireless Card
- [ ] `^HR` — Calibrate RFID Transponder Position
- [ ] `^RA` — Read AFI or DSFID Byte
- [ ] `^RB` — Define EPC Data Structure
- [ ] `^RE` — Enable/Disable E.A.S. Bit
- [ ] `^RF` — Read or Write RFID Format
- [ ] `^RI` — Get RFID Tag ID
- [ ] `^RM` — Enable RFID Motion
- [ ] `^RN` — Detect Multiple RFID Tags in Encoding Field
- [ ] `^RR` — Specify RFID Retries for a Block
- [ ] `^RS` — Set Up RFID Parameters
- [ ] `^RT` — Read RFID Tag
- [ ] `^RW` — Set RFID Read and Write Power Levels
- [ ] `^RZ` — Set RFID Tag Password and Lock Tag
- [ ] `~RV` — Report RFID Encoding Results
