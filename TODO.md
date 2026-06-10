# ZPL II command coverage

Checklist of every ZPL II command documented in the [Zebra ZPL II Programming Guide](https://www.servopack.de/support/zebra/ZPLII-Prog.pdf). Checked items have a dedicated builder method (see [`src/ZplCommand/`](src/ZplCommand/)); unchecked items currently require [`ZplBuilder::raw('…')`](src/ZplBuilder.php) as the escape hatch.

## Fonts, fields and text

- [x] `^A` — Scalable/Bitmapped Font (per-field)
- [x] `^A@` — Use Font Name to Call Font
- [x] `^CF` — Change Alphanumeric Default Font
- [x] `^CI` — Change International Font/Encoding
- [x] `^CW` — Font Identifier
- [x] `^FB` — Field Block
- [x] `^FC` — Field Clock (Real-Time Clock data)
- [x] `^FD` — Field Data
- [x] `^FH` — Field Hexadecimal Indicator
- [x] `^FM` — Multiple Field Origin Locations
- [x] `^FN` — Field Number
- [x] `^FO` — Field Origin
- [x] `^FP` — Field Parameter
- [x] `^FR` — Field Reverse Print
- [x] `^FS` — Field Separator
- [x] `^FT` — Field Typeset
- [x] `^FV` — Field Variable
- [x] `^FW` — Field Orientation
- [x] `^FX` — Comment
- [x] `^KD` — Select Date and Time Format (for Real-Time Clock)
- [x] `^SE` — Select Encoding
- [x] `^SF` — Serialization Field
- [x] `^SL` — Set Mode and Language (for Real-Time Clock)
- [x] `^SN` — Serialization Data
- [x] `^SO` — Set Offset (for Real-Time Clock)
- [x] `^ST` — Set Date and Time (for Real-Time Clock)
- [x] `^TO` — Transfer Object

## Barcodes

- [x] `^B0` — Aztec
- [x] `^B1` — Code 11
- [x] `^B2` — Interleaved 2 of 5
- [x] `^B3` — Code 39
- [x] `^B4` — Code 49
- [x] `^B5` — Planet Code
- [x] `^B7` — PDF417
- [x] `^B8` — EAN-8
- [x] `^B9` — UPC-E
- [x] `^BA` — Code 93
- [x] `^BB` — CODABLOCK
- [x] `^BC` — Code 128 (Subsets A, B, and C)
- [x] `^BD` — UPS MaxiCode
- [x] `^BE` — EAN-13
- [x] `^BF` — Micro-PDF417
- [x] `^BI` — Industrial 2 of 5
- [x] `^BJ` — Standard 2 of 5
- [x] `^BK` — ANSI Codabar
- [x] `^BL` — LOGMARS
- [x] `^BM` — MSI
- [ ] `^BP` — Plessey
- [ ] `^BQ` — QR Code
- [ ] `^BR` — RSS (Reduced Space Symbology)
- [ ] `^BS` — UPC/EAN Extensions
- [ ] `^BT` — TLC39
- [ ] `^BU` — UPC-A
- [ ] `^BX` — Data Matrix
- [x] `^BY` — Bar Code Field Default
- [ ] `^BZ` — POSTNET

## Graphics and images

- [x] `^GB` — Graphic Box
- [ ] `^GC` — Graphic Circle
- [ ] `^GD` — Graphic Diagonal Line
- [ ] `^GE` — Graphic Ellipse
- [ ] `^GF` — Graphic Field
- [ ] `^GS` — Graphic Symbol
- [ ] `^HG` — Host Graphic
- [ ] `^HY` — Upload Graphics
- [ ] `^ID` — Object Delete
- [ ] `^IL` — Image Load
- [ ] `^IM` — Image Move
- [ ] `^IS` — Image Save
- [ ] `^XG` — Recall Graphic
- [ ] `~DG` — Download Graphics
- [ ] `~DN` — Abort Download Graphic
- [ ] `~DY` — Download Graphics / Native TrueType or OpenType Font
- [ ] `~EG` — Erase Download Graphics

## Label layout and format control

- [ ] `^DF` — Download Format
- [ ] `^HF` — Host Format
- [x] `^LH` — Label Home
- [x] `^LL` — Label Length
- [x] `^LR` — Label Reverse Print
- [ ] `^LS` — Label Shift
- [ ] `^LT` — Label Top
- [ ] `^PF` — Slew Given Number of Dot Rows
- [ ] `^PM` — Printing Mirror Image of Label
- [x] `^XA` — Start Format
- [ ] `^XB` — Suppress Backfeed
- [x] `^XF` — Recall Format
- [x] `^XZ` — End Format

## Printing control and media

- [ ] `^CM` — Change Memory Letter Designation
- [ ] `^CO` — Cache On
- [ ] `^CV` — Code Validation
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
- [x] `^PO` — Print Orientation
- [x] `^PQ` — Print Quantity
- [ ] `^PR` — Print Rate
- [x] `^PW` — Print Width
- [ ] `^SP` — Start Print
- [ ] `^SS` — Set Media Sensors
- [ ] `^SZ` — Set ZPL
- [ ] `^ZZ` — Printer Sleep
- [ ] `~PR` — Applicator Reprint
- [ ] `~PS` — Print Start
- [ ] `~SD` — Set Darkness
- [ ] `~TA` — Tear-off Adjust Position

## Host I/O, diagnostics, printer state (lower priority — typically managed out-of-band)

- [ ] `^HH` — Configuration Label Return
- [ ] `^HV` — Host Verification
- [ ] `^HW` — Host Directory List
- [ ] `^HZ` — Display Description Information
- [ ] `^JB` — Initialize Flash Memory
- [ ] `^JJ` — Set Auxiliary Port
- [ ] `^JM` — Set Dots per Millimeter
- [ ] `^JS` — Sensor Select
- [ ] `^JT` — Head Test Interval
- [ ] `^JU` — Configuration Update
- [ ] `^JW` — Set Ribbon Tension
- [ ] `^JZ` — Reprint After Error
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
- [ ] `~HB` — Battery Status
- [ ] `~HD` — Head Diagnostic
- [ ] `~HI` — Host Identification
- [ ] `~HM` — Host RAM Status
- [ ] `~HS` — Host Status Return
- [ ] `~HU` — Return ZebraNet Alert Configuration
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

## Networking, wireless and RFID (likely out of scope for label generation)

- [ ] `^HR` — Calibrate RFID Transponder Position
- [ ] `^NB` — Search for Wired Print Server during Network Boot
- [ ] `^NI` — Network ID Number
- [ ] `^NN` — Set SNMP
- [ ] `^NP` — Set Primary/Secondary Device
- [ ] `^NS` — Change Networking Settings
- [ ] `^NT` — Set SMTP
- [ ] `^NW` — Set Web Authentication Timeout Value
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
- [ ] `~NC` — Network Connect
- [ ] `~NR` — Set All Network Printers Transparent
- [ ] `~NT` — Set Currently Connected Printer Transparent
- [ ] `~RV` — Report RFID Encoding Results
- [ ] `~WC` — Print Configuration Label
- [ ] `~WL` — Print Network Configuration Label
- [ ] `~WR` — Reset Wireless Card
