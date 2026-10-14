---
name: Thermal SCADA Telemetry Engine
colors:
  surface: '#0c1324'
  surface-dim: '#0c1324'
  surface-bright: '#33394c'
  surface-container-lowest: '#070d1f'
  surface-container-low: '#151b2d'
  surface-container: '#191f31'
  surface-container-high: '#23293c'
  surface-container-highest: '#2e3447'
  on-surface: '#dce1fb'
  on-surface-variant: '#bcc9cd'
  inverse-surface: '#dce1fb'
  inverse-on-surface: '#2a3043'
  outline: '#869397'
  outline-variant: '#3d494c'
  surface-tint: '#4cd7f6'
  primary: '#4cd7f6'
  on-primary: '#003640'
  primary-container: '#06b6d4'
  on-primary-container: '#00424f'
  inverse-primary: '#00687a'
  secondary: '#4edea3'
  on-secondary: '#003824'
  secondary-container: '#00a572'
  on-secondary-container: '#00311f'
  tertiary: '#ffb95f'
  on-tertiary: '#472a00'
  tertiary-container: '#e79400'
  on-tertiary-container: '#563400'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#acedff'
  primary-fixed-dim: '#4cd7f6'
  on-primary-fixed: '#001f26'
  on-primary-fixed-variant: '#004e5c'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffddb8'
  tertiary-fixed-dim: '#ffb95f'
  on-tertiary-fixed: '#2a1700'
  on-tertiary-fixed-variant: '#653e00'
  background: '#0c1324'
  on-background: '#dce1fb'
  surface-variant: '#2e3447'
typography:
  headline-xl:
    fontFamily: Barlow Condensed
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: 0.05em
  headline-xl-mobile:
    fontFamily: Barlow Condensed
    fontSize: 26px
    fontWeight: '700'
    lineHeight: 30px
    letterSpacing: 0.04em
  headline-lg:
    fontFamily: Barlow Condensed
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: 0.04em
  headline-md:
    fontFamily: Barlow Condensed
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 22px
    letterSpacing: 0.03em
  headline-sm:
    fontFamily: Barlow Condensed
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 18px
    letterSpacing: 0.02em
  body-lg:
    fontFamily: Barlow Condensed
    fontSize: 16px
    fontWeight: '500'
    lineHeight: 20px
  body-md:
    fontFamily: Barlow Condensed
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 18px
  body-sm:
    fontFamily: Barlow Condensed
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 15px
  data-display:
    fontFamily: JetBrains Mono
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.02em
  data-metric:
    fontFamily: JetBrains Mono
    fontSize: 18px
    fontWeight: '500'
    lineHeight: 22px
    letterSpacing: -0.01em
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
    letterSpacing: 0.02em
  code-xs:
    fontFamily: JetBrains Mono
    fontSize: 10px
    fontWeight: '400'
    lineHeight: 13px
    letterSpacing: 0.03em
  label-caps:
    fontFamily: JetBrains Mono
    fontSize: 10px
    fontWeight: '600'
    lineHeight: 12px
    letterSpacing: 0.1em
spacing:
  gutter: 0.5rem
  gutter-mobile: 0.25rem
  margin: 0.75rem
  margin-mobile: 0.5rem
  space-xs: 0.25rem
  space-sm: 0.375rem
  space-md: 0.5rem
  space-lg: 0.75rem
  space-xl: 1rem
---

## Brand & Style

This design system delivers an ultra-dense, mission-critical engineering terminal for server room thermal telemetry and industrial automation. The interface mirrors hardwired industrial SCADA consoles and rackmount network appliances: cold, precise, authoritative, and utterly utilitarian. 

The aesthetic is built on high-density data matrices, stark contrast, and technical precision. Every pixel serves a functional purpose, eschewing soft consumer flourishes for sharp divisions, technical micro-labeling, raw hexadecimal address references, and immediate visual status triage. The emotional response is absolute situational awareness, rapid incident diagnosis, and unwavering operational control under high-stress server room anomaly events.

## Colors

The palette establishes an uncompromising dark-field command center designed to eliminate eye fatigue over 12-hour operator shifts while maximizing status readability across wide-aspect wall displays and rugged field terminals.

### Core Architecture
- **Primary Telemetry (`#06b6d4` / Cyan 500):** System buses, active telemetry feeds, clock synchronization, connection vectors, and interactive focus states.
- **Nominal Signal (`#10b981` / Emerald 500):** Regulated airflow, stable cold/hot aisle deltas, operational HVAC compressors, and healthy loop responses.
- **Advisory / Warning (`#f59e0b` / Amber 500):** Exceeded thermal thresholds (e.g., intake > 24°C), intermittent sensor packet dropouts, filter particulate warnings, and redundancy failover triggers.
- **Terminal Critical (`#ef4444` / Red 500):** Thermal runaway, coolant loop pressure failure, localized hot spots exceeding SLA thresholds, and emergency shutoff procedures.
- **Base Canvas (`#020617` / Slate 950) & Surface Tiers:** Background levels scale through `#090d16` (slate-925), `#0f172a` (slate-900), and `#1e293b` (slate-800) for structural grid borders.
- **Readout White (`#f8fafc` / Slate 50) & Hardware Neutral (`#64748b` / Slate 500):** High-contrast alphanumeric telemetry and dimmed structural hardware metadata respectively.

## Typography

The typographical pairing splits function decisively into structural hierarchy and raw telemetry output:

1. **Barlow Condensed:** Used for all panel headlines, rack labels, system sections, and equipment group identities. Its mechanical, high-density vertical cadence packs maximum linguistic information across horizontal rows without horizontal visual waste.
2. **JetBrains Mono:** Used for all real-time readings, hexadecimal register values (`0x44`), thermal readouts (`+21.4°C`), bus descriptors, latency counters, and log streaming. Monospaced character consistency guarantees tabular alignment without column jitter during rapid state changes.

All labels and status callouts must be transformed to uppercase (`UPPERCASE`) with tracking applied (`0.05em` to `0.1em`) to simulate machined physical chassis plates and technical display matrix screens.

## Layout & Spacing

The layout is an ultra-dense, full-bleed fluid modular grid designed to maximize screen real estate for continuous multi-rack supervision.

- **Grid Architecture:** 12-column or 24-column nested subgrid configurations on 1080p and 4K industrial multi-heads. Negative space is compressed; margins and gutters never exceed `0.75rem` on desktop displays to retain full vertical telemetry visibility above the fold.
- **Rhythm & Packing:** Spacing tokens scale from `0.25rem` (`space-xs`) to `1rem` (`space-xl`). Micro-padding inside cells keeps rows strictly between `24px` and `32px` in height, providing high data-to-pixel efficiency.
- **Breakpoints & Adaptation:**
  - **Desktop / Control Deck (>= 1280px):** Simultaneous multi-rack matrix, real-time CAN bus telemetry side-inspector, persistent top header status bar, and streaming event logs.
  - **Tablet Field Terminal (768px - 1279px):** 2-column rack module blocks; inspector docks horizontally to the bottom.
  - **Mobile Pocket Deck (< 768px):** Single-column stacked cards; secondary hardware identifiers drop to truncated monospace pills; critical temperature indicators retain `28px` display sizing.

## Elevation & Depth

This system avoids decorative drop shadows. Depth is achieved strictly through structural layering, border illumination, and dark-field luminescence:

- **Tonal Planes:** Surface layers step upward through pure slate black `#020617` (canvas), `#0b0f19` (card bay), and `#131b2e` (active row/cell highlight).
- **Hard Borders:** 1px solid low-reflectivity lines (`#1e293b` / `slate-800`) frame every telemetry module, data cell, and rack segment. 
- **Active Technical Illumination:** Rather than ambient drop shadows, focus states, critical alerts, and active buses use inner or edge stroke highlights (`border-cyan-500/50` or `border-red-500`).
- **Scanline & Crosshair Grid Overlays:** Sensor visualization windows and temperature gradient charts incorporate subtle SVG background grids (`rgba(255, 255, 255, 0.03)`) and vertical hairline alignment rules to simulate calibrated phosphor CRT scopes and testbench monitors.

## Shapes

The roundedness level is strictly set to `0` (Sharp / 0px radius).

Every interface element—from rack modules, buttons, and alert strips to table cells and modal overlays—features sharp, right-angle geometry. Chamfered or cut corners (via CSS polygon masks) at 45 degrees (`4px` to `6px`) are permitted exclusively for terminal corner accents, hardware status tags, and diagnostic mode pills to enforce the rugged rackmount chassis aesthetic.

## Components

### Telemetry Modules & Sensor Cards
- Structural container with a `1px solid #1e293b` boundary and a dark `#0b0f19` ground.
- Top bar contains a truncated technical ID (`RACK-04 // AISLE-C [COLD]`), an active protocol chip (`CAN 0x44`), and a nominal ping pulse indicator.
- Metric values use `data-display` JetBrains Mono font, accompanied by miniature sparkline traces or thermal segment bars (20 segments indicating safe to critical zones).

### Live Status Badges & Pulse Indicators
- Sharp-edged pill containers with uppercase `label-caps` text and high-contrast color coding:
  - Nominal: `#10b981` text with `rgba(16, 185, 129, 0.1)` background and a pulsating `4px` dot.
  - Critical: `#ef4444` background, `#ffffff` bold text, with an active flashing heartbeat (`animation: blink 1s step-end infinite`).
- Monospace hardware address badges (`SHT31-D`, `ADDR: 0x44`, `192.168.10.142:9200`) use muted neutral fills `#0f172a` with `#64748b` text and subtle cyan hover states.

### Dense Compact Data Tables
- Header row uses `0.5rem` vertical padding, `#020617` background, uppercase `Barlow Condensed` with `0.05em` letter spacing, and a `1px` bottom border in `#334155`.
- Data rows feature alternating stripe states (`rgba(255,255,255,0.01)`) with fixed heights of `28px`. Numeric entries align right using `JetBrains Mono` tabular figures. Hover state applies an edge-to-edge `#06b6d4` 1px left accent bar and `#1e293b` fill.

### Hardware Command Buttons & Toggles
- **Primary Action (Diagnostics/Cycle):** Sharp `#06b6d4` border, deep `#083344` background, `#22d3ee` text. On press, inverts instantaneously without transition animations.
- **Critical Action (E-Stop/Vent Runaway):** Sharp `#ef4444` border, `#450a0a` background, `#fca5a5` text, pre-pended with a hazard glyph icon `[!]`.
- **Toggle Switches:** Segmented rectangular rocker switches with dual mechanical states (`ON` in emerald green; `STBY` in muted slate).

### Diagnostic Inputs & Numeric Adjusters
- Inset visual style with a `1px solid #334155` border, black canvas fill `#020617`, and crisp cyan cursor.
- Integrated numeric steppers use monospaced increments with bracketed click targets `[-]` and `[+]`. Focus triggers a high-visibility `#06b6d4` full border stroke.