---
name: AetherSens Enterprise Telemetry
colors:
  surface: '#0f131c'
  surface-dim: '#0f131c'
  surface-bright: '#353943'
  surface-container-lowest: '#0a0e17'
  surface-container-low: '#181c24'
  surface-container: '#1c2029'
  surface-container-high: '#262a33'
  surface-container-highest: '#31353e'
  on-surface: '#dfe2ef'
  on-surface-variant: '#c1c7d3'
  inverse-surface: '#dfe2ef'
  inverse-on-surface: '#2c303a'
  outline: '#8b919d'
  outline-variant: '#414751'
  surface-tint: '#a4c9ff'
  primary: '#a4c9ff'
  on-primary: '#00315d'
  primary-container: '#4d93e5'
  on-primary-container: '#002a51'
  inverse-primary: '#0060ac'
  secondary: '#77d6d3'
  on-secondary: '#003736'
  secondary-container: '#007d7b'
  on-secondary-container: '#c6fffd'
  tertiary: '#74db9d'
  on-tertiary: '#00391f'
  tertiary-container: '#3aa36b'
  on-tertiary-container: '#00311a'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#d4e3ff'
  primary-fixed-dim: '#a4c9ff'
  on-primary-fixed: '#001c39'
  on-primary-fixed-variant: '#004883'
  secondary-fixed: '#94f2f0'
  secondary-fixed-dim: '#77d6d3'
  on-secondary-fixed: '#00201f'
  on-secondary-fixed-variant: '#00504e'
  tertiary-fixed: '#91f8b8'
  tertiary-fixed-dim: '#74db9d'
  on-tertiary-fixed: '#002110'
  on-tertiary-fixed-variant: '#00522f'
  background: '#0f131c'
  on-background: '#dfe2ef'
  surface-variant: '#31353e'
typography:
  display-lg:
    fontFamily: Geist
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Geist
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Geist
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: -0.005em
  telemetry-metric:
    fontFamily: Geist
    fontSize: 28px
    fontWeight: '500'
    lineHeight: 32px
    letterSpacing: -0.02em
  body-lg:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Geist
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
  label-md:
    fontFamily: Geist
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 18px
  label-sm:
    fontFamily: Geist
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.02em
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-lg: 1.5rem
  margin: 1rem
  margin-md: 1.5rem
  margin-lg: 2rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

## Brand & Style

This design system establishes a high-precision, mission-critical operational interface for mission-critical IT infrastructure, data centers, and server room environmental telemetry. Designed within a modern, disciplined Enterprise Laravel Jetstream and Livewire architectural aesthetic, it rejects the decorative excess, eye-straining dark mode tropes, and saturated "cyberpunk" neons typical of modern monitoring tools in favor of calm, utilitarian, and dependable clarity.

The visual tone communicates stability, precision, and operational focus. Systems operators, site reliability engineers, and facility technicians require immediate legibility during high-stress threshold breaches without visual exhaustion during 12-hour shifts. Visual hierarchy is achieved through precise tonal layers, subtle border containment, and measured functional color rather than visual volume.

## Colors

The palette is engineered strictly around muted, non-fatiguing, desaturated values to maintain 24/7 readability across expansive telemetry boards. Pure black (`#000000`) is strictly forbidden to prevent aggressive contrast banding and eye strain.

### Surface Tiers
- **Canvas Base (`#1E222B`)**: Deep desaturated slate gray providing a soft, non-reflective backdrop.
- **Card / Surface Default (`#272C36`)**: Soft dark charcoal providing structural elevation against the canvas.
- **Surface Muted / Hover (`#2D333F`)**: Elevated surface state for table headers, active rows, and control backgrounds.
- **Surface Highlight (`#38404E`)**: Active states, segmented control tracks, and input field backdrops.
- **Subtle Border (`#343B48`)**: Structural border framing surfaces, metrics containers, and data cells.
- **Dividers (`#2A303C`)**: Low-contrast internal delimiters.

### Typography Colors
- **Text Primary (`#E2E8F0`)**: Warm, balanced light gray for all primary headings, metric values, and active interface items.
- **Text Secondary / Muted (`#94A3B8`)**: Mid-tone slate for field labels, metadata, timestamps, and secondary navigation.
- **Text Disabled (`#64748B`)**: Inactive states, placeholder text, and collapsed node indicators.

### Functional Status & Accents
All telemetry status indicators use desaturated, professional tones with absolute prohibition of ambient blur glows or blinding fluorescent saturations:
- **Normal / Nominal (`#38A169`)**: Muted emerald indicating balanced thermal, humidity, and airflow levels.
- **Warning / Degraded (`#D69E2E`)**: Warm amber signaling threshold drift, battery fallback, or elevated thermal curves.
- **Critical / Danger (`#E53E3E`)**: Muted coral red reserved exclusively for sensor failure, liquid leak detection, or thermal limits exceeded.
- **Primary Accent (`#4A90E2`)**: Muted steel blue for focused controls, primary action links, and timeline selection.
- **Telemetry Secondary (`#319795`)**: Muted teal for cross-metric comparisons (e.g., humidity vs. temperature overlays).

## Typography

The type system is powered by Geist, providing structured, clean mechanical glyphs that excel across dense tabular layouts and numerical telemetry readouts. JetBrains Mono is assigned for rack hardware identifiers, MAC addresses, sensor register maps, and JSON configuration payloads.

All telemetry numbers and sensor readings must render with tabular figures (`font-variant-numeric: tabular-nums`) to prevent layout shifts during live Livewire polling updates. Letter spacing is subtly tightened across larger sizes to maintain cohesion and expanded on micro-labels for scanning efficiency.

## Layout & Spacing

Layouts follow a fluid, responsive 12-column grid bound within a standardized operational max-width container (`1600px` for wide displays, scaling down seamlessly). The spatial strategy organizes sensor racks, room zoning blueprints, and live streaming charts without horizontal bloat.

### Breakpoints & Adaptations
- **Mobile (< 768px)**: 4-column flow. Dashboard panels stack sequentially. Outer canvas margin is pinned to `space-md` (`1rem`). Metrics clusters convert to 2-column key-value tiles.
- **Tablet (768px - 1024px)**: 8-column layout. Sidebar navigation collapses into an icon-rail. Grid items shift to 2-across layouts for rack environmental pods.
- **Desktop (1024px - 1440px)**: 12-column layout. Standard Laravel Jetstream app-shell with a static `260px` sidebar, a persistent room-status sub-header, and a multi-card telemetry grid.
- **Ultra-wide (> 1440px)**: 12-column layout. Maximized real-time charts with multi-axis sensor comparisons and side-by-side active alert panels. Outer margins scale to `margin-lg` (`2rem`).

## Elevation & Depth

This system avoids floating cloud shadows, harsh drop-offs, and light leaks. Instead, depth is established through **tonal layering combined with subtle surface containment**:

1. **Flat Base Canvas**: The deepest layer sits at `#1E222B`.
2. **Structural Container (Level 1)**: Cards, telemetry panels, and data tables sit at `#272C36`, bound by a crisp 1px border of `#343B48`. Card depth is reinforced by an ultra-diffused, ambient shadow: `box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.25), 0 1px 2px -1px rgba(0, 0, 0, 0.2)`.
3. **Elevated Component (Level 2)**: Dropdown menus, modal dialogs, flyout command palettes, and slide-over rack inspectors sit at `#2D333F` with an intensified structural border (`#3E4756`) and an expanded ambient shadow: `box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.35), 0 2px 4px -2px rgba(0, 0, 0, 0.3)`.
4. **Interactive States**: No element lifts upward on hover (no `translateY` offset). Interactivity is conveyed strictly through background luminance shifts (`#272C36` to `#2D333F`) and subtle border illumination (`#343B48` to `#4A90E2`).

## Shapes

The interface implements a strict **12px (`0.75rem`) corner radius** across all primary cards, modal dialogs, and large structural panels. This softens the visual density of server management tools without sliding into casual or consumer pill-shaped territory.

- **Primary Cards & Modals**: `12px` (`rounded-xl` equivalent).
- **Form Controls & Buttons**: `8px` (`0.5rem`).
- **Telemetry Badges & Status Chips**: `6px` (`0.375rem`) to maintain architectural compactness.
- **Avatar Nodes & Status Indicator Pips**: Strictly circular (`9999px`) for quick biological and hardware state differentiation.

## Components

### Buttons
- **Primary Button**: Solid muted steel blue background (`#4A90E2`), text in `#FFFFFF`, corner radius `8px`, subtle shadow. Hover state brightens to `#5B8DEF`. Active state darkens to `#3A7BC8`. No glow effects.
- **Secondary Button**: Soft dark charcoal background (`#272C36`), structural border (`1px solid #343B48`), text `#E2E8F0`. Hover state transitions background to `#2D333F` and border to `#4A90E2`.
- **Destructive Button**: Background `#272C36`, border `1px solid #E53E3E`, text `#E53E3E`. Hover transitions to background `rgba(229, 62, 62, 0.12)`.

### Cards & Telemetry Containers
- Built on `#272C36` with a `1px` border of `#343B48` and `12px` border radius.
- Inner padding strictly uses `space-lg` (`1.5rem`) for metrics tiles and `space-md` (`1rem`) for dense rack modules.
- Card headers feature an upper border divider (`#2A303C`) separating the title/sensor rack identifier from the metric stream.

### Status Badges & Sensor Chips
- Composed with a solid, low-opacity background and matching solid text.
- **Normal**: Background `rgba(56, 161, 105, 0.14)`, text `#38A169`, border `1px solid rgba(56, 161, 105, 0.3)`.
- **Warning**: Background `rgba(214, 158, 46, 0.14)`, text `#D69E2E`, border `1px solid rgba(214, 158, 46, 0.3)`.
- **Critical**: Background `rgba(229, 62, 62, 0.14)`, text `#E53E3E`, border `1px solid rgba(229, 62, 62, 0.3)`.
- Badges embed a static 6px circular pip before the text. Pulses or animations are restricted strictly to unacknowledged Critical events.

### Input Fields & Controls
- Input backgrounds use `#1E222B` with a `1px` border of `#343B48` and text in `#E2E8F0`. Placeholder in `#64748B`.
- Focus state: Border shifts cleanly to `#4A90E2` with a `0 0 0 1px #4A90E2` focus ring. No heavy outer glow halos.
- Checkboxes & Radios: Box canvas `#1E222B`, border `#343B48`. Selected state fills with `#4A90E2` featuring a crisp `#FFFFFF` check icon.

### Telemetry Tables & Live Lists
- Laravel Livewire row updates apply a seamless background flash using `rgba(74, 144, 226, 0.08)`.
- Table header: Background `#222630`, text `#94A3B8`, uppercase `11px`, tracking `0.05em`.
- Table row divider: `1px solid #2A303C`. Row hover: `#2D333F`.
- Sensor metrics inside cells must use `code-sm` or tabular figures with trailing measurement units (e.g., `°C`, `% RH`, `Pa`) in `#94A3B8`.

### Charts & Sparklines
- Chart canvas matches card surface (`#272C36`).
- Gridlines: Minimal, dotted lines using `#343B48`.
- Primary line stroke: `#4A90E2` (`2px` width). Secondary metric stroke: `#319795`.
- Area fill beneath line curves utilizes a flat linear gradient terminating at `rgba(39, 44, 54, 0.0)` at the baseline.