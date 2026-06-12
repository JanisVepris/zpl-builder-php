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
- [x] `^BP` — Plessey
- [x] `^BQ` — QR Code
- [x] `^BR` — RSS (Reduced Space Symbology)
- [x] `^BS` — UPC/EAN Extensions
- [x] `^BT` — TLC39
- [x] `^BU` — UPC-A
- [x] `^BX` — Data Matrix
- [x] `^BY` — Bar Code Field Default
- [x] `^BZ` — POSTNET

## Graphics and images

- [x] `^GB` — Graphic Box
- [x] `^GC` — Graphic Circle
- [x] `^GD` — Graphic Diagonal Line
- [x] `^GE` — Graphic Ellipse
- [x] `^GF` — Graphic Field
- [x] `^GS` — Graphic Symbol
- [x] `^HG` — Host Graphic
- [x] `^HY` — Upload Graphics
- [x] `^ID` — Object Delete
- [x] `^IL` — Image Load
- [x] `^IM` — Image Move
- [x] `^IS` — Image Save
- [x] `^XG` — Recall Graphic
- [x] `~DG` — Download Graphics
- [x] `~DN` — Abort Download Graphic
- [x] `~DY` — Download Graphics / Native TrueType or OpenType Font
- [x] `~EG` — Erase Download Graphics

## Label layout and format control

- [x] `^DF` — Download Format
- [x] `^HF` — Host Format
- [x] `^LH` — Label Home
- [x] `^LL` — Label Length
- [x] `^LR` — Label Reverse Print
- [x] `^LS` — Label Shift
- [x] `^LT` — Label Top
- [x] `^PF` — Slew Given Number of Dot Rows
- [x] `^PM` — Printing Mirror Image of Label
- [x] `^XA` — Start Format
- [x] `^XB` — Suppress Backfeed
- [x] `^XF` — Recall Format
- [x] `^XZ` — End Format

## Printing control and media

- [x] `^CM` — Change Memory Letter Designation
- [x] `^CO` — Cache On
- [x] `^CV` — Code Validation
- [x] `^MC` — Map Clear
- [x] `^MD` — Media Darkness
- [x] `^MF` — Media Feed
- [x] `^ML` — Maximum Label Length
- [x] `^MM` — Print Mode
- [x] `^MN` — Media Tracking
- [x] `^MP` — Mode Protection
- [x] `^MT` — Media Type
- [x] `^MU` — Set Units of Measurement
- [x] `^MW` — Modify Head Cold Warning
- [x] `^PO` — Print Orientation
- [x] `^PQ` — Print Quantity
- [x] `^PR` — Print Rate
- [x] `^PW` — Print Width
- [x] `^SP` — Start Print
- [x] `^SS` — Set Media Sensors
- [x] `^SZ` — Set ZPL
- [x] `^ZZ` — Printer Sleep
- [x] `~PR` — Applicator Reprint
- [x] `~PS` — Print Start
- [x] `~SD` — Set Darkness
- [x] `~TA` — Tear-off Adjust Position

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

- [x] `^HR` — Calibrate RFID Transponder Position
- [x] `^NB` — Search for Wired Print Server during Network Boot
- [x] `^NI` — Network ID Number
- [x] `^NN` — Set SNMP
- [x] `^NP` — Set Primary/Secondary Device
- [x] `^NS` — Change Networking Settings
- [x] `^NT` — Set SMTP
- [x] `^NW` — Set Web Authentication Timeout Value
- [x] `^RA` — Read AFI or DSFID Byte
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
