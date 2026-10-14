<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Intalnet SCADA') }} - Telemetry OS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary-container selection:text-on-primary-container min-h-screen">

    <!-- Primary Top Navigation Bar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md shadow-[0_1px_8px_rgba(0,0,0,0.4)]">
        <div class="h-14 w-full px-margin flex items-center justify-between gap-space-md">
            <div class="flex items-center gap-space-lg">
                <a class="flex items-center gap-space-sm group" href="{{ url('/') }}">
                    <div class="w-7 h-7 bg-surface-container-high flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">terminal</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-headline-md text-headline-md text-on-surface tracking-wider leading-none">INTALNET<span class="text-primary font-bold ml-1">OS</span></span>
                        <span class="font-code-xs text-code-xs text-on-surface-variant uppercase tracking-widest">SCADA CORE v4.12</span>
                    </div>
                </a>
                <div class="hidden xl:flex items-center gap-space-xs bg-surface-container-lowest px-space-sm py-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                    <span class="font-label-caps text-label-caps text-secondary uppercase">CAN BUS 0x44: OPERATIVO</span>
                    <span class="font-code-xs text-code-xs text-outline mx-1">|</span>
                    <span class="font-code-xs text-code-xs text-on-surface-variant">LATENCIA: 1.4ms</span>
                </div>
            </div>

            <nav class="hidden lg:flex items-center gap-space-xs">
                <a class="px-space-md py-1 font-headline-sm text-headline-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors" href="#arquitectura">Arquitectura</a>
                <a class="px-space-md py-1 font-headline-sm text-headline-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors" href="#sensores">Sensores &amp; Edge</a>
                <a class="px-space-md py-1 font-headline-sm text-headline-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors" href="#especificaciones">Especificaciones</a>
                <a class="px-space-md py-1 font-headline-sm text-headline-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors" href="#normativa">Normativa</a>
            </nav>

            <div class="flex items-center gap-space-sm">
                @if (Route::has('login'))
                    @auth
                        <a class="px-space-md py-1 bg-primary text-on-primary font-headline-sm text-headline-sm hover:bg-primary-fixed-dim transition-colors flex items-center gap-1 shadow-sm" href="{{ route('dashboard') }}">
                            <span class="material-symbols-outlined text-[16px]">grid_view</span>
                            <span>Consola SCADA</span>
                        </a>
                    @else
                        <a class="px-space-md py-1 font-headline-sm text-headline-sm text-on-surface hover:bg-surface-container-high transition-colors" href="{{ route('login') }}">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a class="px-space-md py-1 bg-primary text-on-primary font-headline-sm text-headline-sm hover:bg-primary-fixed-dim transition-colors flex items-center gap-1 shadow-sm" href="{{ route('register') }}">
                                <span class="material-symbols-outlined text-[16px]">key</span>
                                <span>Registrarse</span>
                            </a>
                        @endif
                    @endauth
                @endif
                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center ml-1 border border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[18px]">shield</span>
                </div>
            </div>
        </div>
    </header>

    <main class="w-full pt-14 bg-surface min-h-[calc(100vh-140px)]">
        <div class="flex flex-col w-full">
            <!-- Top Technical Status Micro-Ribbon -->
            <section class="w-full bg-surface-container-lowest px-margin py-space-xs flex flex-wrap items-center justify-between gap-space-sm">
                <div class="flex items-center gap-space-md">
                    <div class="flex items-center gap-space-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                        <span class="font-label-caps text-label-caps text-secondary uppercase tracking-widest">NODO-MAESTRO 0x01: EN LÍNEA</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-outline">|</span>
                    <span class="font-code-xs text-code-xs text-on-surface-variant font-mono">CAN 2.0B DIFERENCIAL @ 250 KBPS</span>
                    <span class="font-code-xs text-code-xs text-outline hidden sm:inline">|</span>
                    <span class="font-code-xs text-code-xs text-tertiary hidden sm:inline font-mono">DMA BUFFER: 98.4% LIBRE</span>
                </div>
                <div class="flex items-center gap-space-md">
                    <span class="font-code-xs text-code-xs text-outline">HASH FIRMWARE: <span class="text-on-surface font-mono">#b7f09-esp32s3</span></span>
                    <span class="font-code-xs text-code-xs bg-surface-container-high px-space-xs py-0.5 text-primary">SYNC RTC: ±0.2ms</span>
                </div>
            </section>

            <!-- Hero Section with Industrial Grid & Telemetry Preview -->
            <section class="relative w-full px-margin pt-space-xl pb-space-xl overflow-hidden bg-gradient-to-b from-surface-container-lowest via-surface to-surface-container-low" id="arquitectura">
                <!-- Ambient Grid & Scanlines Backdrop -->
                <div class="absolute inset-0 opacity-15 pointer-events-none" style="background-image: radial-gradient(rgba(76, 215, 246, 0.25) 1px, transparent 1px), linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px); background-size: 24px 24px, 120px 100%;"></div>

                <div class="relative w-full max-w-[1720px] mx-auto grid grid-cols-1 xl:grid-cols-12 gap-space-xl items-start">
                    <!-- Left Column: Core Value Proposition -->
                    <div class="xl:col-span-6 flex flex-col gap-space-md">
                        <!-- Industrial Standard Pulse Badge -->
                        <div class="inline-flex items-center gap-space-sm bg-surface-container-high px-space-md py-1 max-w-fit shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                            </span>
                            <span class="font-label-caps text-label-caps text-secondary uppercase font-semibold">ESTÁNDAR INDUSTRIAL // HARDWARE ESP32 + BUS CAN</span>
                            <span class="font-code-xs text-code-xs text-outline px-1 bg-surface-container-lowest">REV-4.12</span>
                        </div>

                        <!-- Technical Impact Headline -->
                        <h1 class="font-headline-xl text-headline-xl text-on-surface uppercase tracking-wide leading-none mt-space-xs">
                            Plataforma SCADA de <span class="text-primary">Telemetría en Tiempo Real</span> y Diagnóstico Térmico Autónomo
                        </h1>

                        <!-- Subheadline & System Envelope -->
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mt-1 leading-snug">
                            Supervisión de sensores de temperatura <strong class="text-on-surface font-mono">SHT31-D</strong>, detección de gases críticos <strong class="text-on-surface font-mono">VOC / MQ-2</strong>, monitoreo de bus CAN industrial y control preventivo de contingencias HVAC con latencia submilisegundo de ciclo cerrado.
                        </p>

                        <!-- Command Dual Action CTAs -->
                        <div class="flex flex-wrap items-center gap-space-md pt-space-sm">
                            @auth
                                <a class="px-space-xl py-2.5 bg-primary text-on-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-primary-fixed-dim transition-colors flex items-center gap-space-sm shadow-md" href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-[20px]">terminal</span>
                                    <span>Ingresar a Consola SCADA</span>
                                    <span class="font-code-xs text-code-xs bg-on-primary/20 px-1 py-0.5 ml-1">F12</span>
                                </a>
                            @else
                                <a class="px-space-xl py-2.5 bg-primary text-on-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-primary-fixed-dim transition-colors flex items-center gap-space-sm shadow-md" href="{{ route('login') }}">
                                    <span class="material-symbols-outlined text-[20px]">terminal</span>
                                    <span>Acceder a Telemetría OS</span>
                                    <span class="font-code-xs text-code-xs bg-on-primary/20 px-1 py-0.5 ml-1">F12</span>
                                </a>
                            @endauth
                            <a class="px-space-xl py-2.5 bg-surface-container-high text-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-surface-bright transition-colors flex items-center gap-space-sm shadow-sm" href="#sensores">
                                <span class="material-symbols-outlined text-[20px]">schema</span>
                                <span>Ver Sensores &amp; Especificaciones</span>
                            </a>
                        </div>

                        <!-- Micro Hardware Telemetry Strip -->
                        <div class="grid grid-cols-3 gap-space-sm pt-space-sm max-w-xl">
                            <div class="bg-surface-container-lowest p-space-sm shadow-sm flex flex-col border border-outline-variant/30">
                                <span class="font-code-xs text-code-xs text-outline uppercase">CICLO SCAN DMA</span>
                                <span class="font-data-metric text-data-metric text-secondary font-mono">500 <span class="text-[12px] font-normal text-on-surface-variant">ms</span></span>
                                <span class="font-code-xs text-code-xs text-outline">Polling determinista</span>
                            </div>
                            <div class="bg-surface-container-lowest p-space-sm shadow-sm flex flex-col border border-outline-variant/30">
                                <span class="font-code-xs text-code-xs text-outline uppercase">INTEGRIDAD NODAL</span>
                                <span class="font-data-metric text-data-metric text-primary font-mono">99.98%</span>
                                <span class="font-code-xs text-code-xs text-outline">48 transceptores</span>
                            </div>
                            <div class="bg-surface-container-lowest p-space-sm shadow-sm flex flex-col border border-outline-variant/30">
                                <span class="font-code-xs text-code-xs text-outline uppercase">RESPUESTA E-VENT</span>
                                <span class="font-data-metric text-data-metric text-tertiary font-mono">&lt; 1.2 <span class="text-[12px] font-normal text-on-surface-variant">ms</span></span>
                                <span class="font-code-xs text-code-xs text-outline">Corte relé en rack</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Interactive SCADA Rack Deck Preview -->
                    <div class="xl:col-span-6 flex flex-col gap-space-xs">
                        <div class="bg-surface-container-lowest p-space-md shadow-xl flex flex-col gap-space-sm border border-outline-variant/40">
                            <!-- Terminal Header Bar -->
                            <div class="flex items-center justify-between bg-surface-container-high px-space-md py-1">
                                <div class="flex items-center gap-space-sm">
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                    <span class="font-headline-sm text-headline-sm text-on-surface uppercase tracking-wide">RACK-07 // SALA TÉRMICA NORTE [FRÍA]</span>
                                    <span class="font-code-xs text-code-xs bg-surface-container-lowest px-1.5 text-primary">CAN ID: 0x44</span>
                                </div>
                                <div class="flex items-center gap-space-md">
                                    <span class="font-code-xs text-code-xs text-outline">BUFFER: 84.2K EVT</span>
                                    <span class="font-label-caps text-label-caps text-secondary bg-surface-container-lowest px-2 py-0.5">NOMINAL</span>
                                </div>
                            </div>

                            <!-- Triple Telemetry Gauge Matrix -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm">
                                <!-- Metric 1: Temp Sala SHT31-D -->
                                <div class="bg-surface-container p-space-sm flex flex-col justify-between shadow-sm border border-outline-variant/30">
                                    <div class="flex items-center justify-between">
                                        <span class="font-code-xs text-code-xs text-outline uppercase">TEMP SALA (SHT31-D)</span>
                                        <span class="font-code-xs text-code-xs text-secondary font-mono">CALIBRADO</span>
                                    </div>
                                    <div class="flex items-baseline gap-1 my-1">
                                        <span class="font-data-display text-data-display text-on-surface font-mono" id="val-temp">21.8</span>
                                        <span class="font-headline-sm text-headline-sm text-primary">°C</span>
                                    </div>
                                    <!-- Mini SVG Inline Sparkline -->
                                    <div class="w-full h-8 flex items-end">
                                        <svg class="w-full h-full text-secondary" preserveaspectratio="none" viewbox="0 0 100 25">
                                            <polyline fill="none" points="0,18 15,19 30,15 45,16 60,11 75,12 90,9 100,8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] text-outline font-mono mt-1">
                                        <span>RANGO: 18.0 - 24.0°C</span>
                                        <span class="text-secondary font-bold">Δ 0.02</span>
                                    </div>
                                </div>

                                <!-- Metric 2: Flujo Extractor HVAC -->
                                <div class="bg-surface-container p-space-sm flex flex-col justify-between shadow-sm border border-outline-variant/30">
                                    <div class="flex items-center justify-between">
                                        <span class="font-code-xs text-code-xs text-outline uppercase">FLUJO EXTRACTOR N2</span>
                                        <span class="font-code-xs text-code-xs text-primary font-mono">PWM 82%</span>
                                    </div>
                                    <div class="flex items-baseline gap-1 my-1">
                                        <span class="font-data-display text-data-display text-on-surface font-mono" id="val-flow">1,420</span>
                                        <span class="font-headline-sm text-headline-sm text-primary">CFM</span>
                                    </div>
                                    <!-- Mini Bar Segment Array -->
                                    <div class="grid grid-cols-12 gap-0.5 h-8 items-end">
                                        <div class="bg-primary h-2"></div>
                                        <div class="bg-primary h-3"></div>
                                        <div class="bg-primary h-4"></div>
                                        <div class="bg-primary h-5"></div>
                                        <div class="bg-primary h-5"></div>
                                        <div class="bg-primary h-6"></div>
                                        <div class="bg-primary h-6"></div>
                                        <div class="bg-primary h-7"></div>
                                        <div class="bg-primary h-7"></div>
                                        <div class="bg-primary h-8"></div>
                                        <div class="bg-surface-container-high h-8"></div>
                                        <div class="bg-surface-container-high h-8"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] text-outline font-mono mt-1">
                                        <span>TACÓMETRO: 2,840 RPM</span>
                                        <span class="text-primary font-bold">ÓPTIMO</span>
                                    </div>
                                </div>

                                <!-- Metric 3: Voltaje Bus UPS -->
                                <div class="bg-surface-container p-space-sm flex flex-col justify-between shadow-sm border border-outline-variant/30">
                                    <div class="flex items-center justify-between">
                                        <span class="font-code-xs text-code-xs text-outline uppercase">BUS DC / UPS COLD</span>
                                        <span class="font-code-xs text-code-xs text-secondary font-mono">LINEA ACT</span>
                                    </div>
                                    <div class="flex items-baseline gap-1 my-1">
                                        <span class="font-data-display text-data-display text-on-surface font-mono" id="val-volt">230.4</span>
                                        <span class="font-headline-sm text-headline-sm text-secondary">VAC</span>
                                    </div>
                                    <!-- Mini SVG Inline Sparkline -->
                                    <div class="w-full h-8 flex items-end">
                                        <svg class="w-full h-full text-primary" preserveaspectratio="none" viewbox="0 0 100 25">
                                            <polyline fill="none" points="0,12 14,11 28,13 42,12 56,12 70,11 84,12 100,12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] text-outline font-mono mt-1">
                                        <span>FRECUENCIA: 59.98 Hz</span>
                                        <span class="text-secondary font-bold">THD 1.1%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Real-time Packet Streaming Terminal Row -->
                            <div class="bg-surface-container-high p-space-sm font-mono text-code-xs text-on-surface-variant flex flex-col gap-1">
                                <div class="flex items-center justify-between text-[10px] text-outline border-b-0 pb-1">
                                    <span class="uppercase">STREAM DE PAQUETES RAW [ESP32-DMA -> CAN 2.0B]</span>
                                    <span class="text-secondary flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>CAPTURANDO</span>
                                </div>
                                <div class="flex items-center justify-between bg-surface-container-lowest px-space-xs py-0.5">
                                    <span class="text-primary">[14:02:19.412]</span>
                                    <span class="text-on-surface">CAN_TX 0x44 [08] 15 A2 03 8E 41 02 FF 00</span>
                                    <span class="text-secondary">CRC OK</span>
                                </div>
                                <div class="flex items-center justify-between bg-surface-container-lowest px-space-xs py-0.5">
                                    <span class="text-primary">[14:02:18.910]</span>
                                    <span class="text-on-surface">VOC_SAMPLE MQ2 [04] 00 2B 00 12 (184 PPM)</span>
                                    <span class="text-secondary">NORMAL</span>
                                </div>
                                <div class="flex items-center justify-between bg-surface-container-lowest px-space-xs py-0.5">
                                    <span class="text-primary">[14:02:18.408]</span>
                                    <span class="text-on-surface">HVAC_INTERLOCK STATUS [02] 01 00 (LOOP-OK)</span>
                                    <span class="text-secondary">ENGAGED</span>
                                </div>
                            </div>

                            <!-- Quick Action Deck Footer -->
                            <div class="flex flex-wrap items-center justify-between pt-space-xs gap-space-sm">
                                <div class="flex items-center gap-space-sm">
                                    <button class="px-space-md py-1 bg-surface-container text-on-surface font-label-caps text-label-caps uppercase hover:bg-surface-bright transition-colors flex items-center gap-1 border border-outline-variant/40" onclick="alert('Comando de calibración enviado al bus CAN.');">
                                        <span class="material-symbols-outlined text-[14px]">tune</span>
                                        Recalibrar Cero
                                    </button>
                                    <button class="px-space-md py-1 bg-surface-container text-on-surface font-label-caps text-label-caps uppercase hover:bg-surface-bright transition-colors flex items-center gap-1 border border-outline-variant/40" onclick="alert('Descargando búfer circular WAL.');">
                                        <span class="material-symbols-outlined text-[14px]">save_alt</span>
                                        Descargar Log WAL
                                    </button>
                                </div>
                                <span class="font-code-xs text-code-xs text-outline">TELEMETRÍA ACTUALIZADA HACE: <span class="text-primary font-mono" id="time-ago">412ms</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Live Performance & Network Health Ribbon -->
            <section class="w-full bg-surface-container-high py-space-md px-margin shadow-sm border-y border-outline-variant/30">
                <div class="w-full max-w-[1720px] mx-auto grid grid-cols-2 md:grid-cols-4 gap-space-md items-center">
                    <div class="bg-surface-container-lowest p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                        <div class="flex flex-col">
                            <span class="font-code-xs text-code-xs text-outline uppercase tracking-wider">LATENCIA PROMEDIO</span>
                            <span class="font-data-display text-data-display text-primary font-mono leading-none mt-1">14 <span class="text-headline-md font-body-md text-on-surface-variant">ms</span></span>
                        </div>
                        <div class="w-8 h-8 bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[20px]">speed</span>
                        </div>
                    </div>

                    <div class="bg-surface-container-lowest p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                        <div class="flex flex-col">
                            <span class="font-code-xs text-code-xs text-outline uppercase tracking-wider">PAQUETES PERDIDOS</span>
                            <span class="font-data-display text-data-display text-secondary font-mono leading-none mt-1">0 <span class="text-headline-md font-body-md text-on-surface-variant">PERD</span></span>
                        </div>
                        <div class="w-8 h-8 bg-surface-container flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        </div>
                    </div>

                    <div class="bg-surface-container-lowest p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                        <div class="flex flex-col">
                            <span class="font-code-xs text-code-xs text-outline uppercase tracking-wider">TASA CAN BUS</span>
                            <span class="font-data-display text-data-display text-on-surface font-mono leading-none mt-1">250 <span class="text-headline-md font-body-md text-on-surface-variant">kbps</span></span>
                        </div>
                        <div class="w-8 h-8 bg-surface-container flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined text-[20px]">settings_ethernet</span>
                        </div>
                    </div>

                    <div class="bg-surface-container-lowest p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                        <div class="flex flex-col">
                            <span class="font-code-xs text-code-xs text-outline uppercase tracking-wider">COMPATIBILIDAD CORE</span>
                            <span class="font-data-display text-data-display text-secondary font-mono leading-none mt-1">100% <span class="text-body-sm font-body-sm text-outline">L3</span></span>
                        </div>
                        <div class="w-8 h-8 bg-surface-container flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[20px]">layers</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Technical Industrial Features Grid -->
            <section class="w-full px-margin py-space-xl bg-surface" id="sensores">
                <div class="w-full max-w-[1720px] mx-auto flex flex-col gap-space-lg">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-space-xs text-primary mb-1">
                                <span class="material-symbols-outlined text-[16px]">memory</span>
                                <span class="font-label-caps text-label-caps uppercase tracking-widest">INGENIERÍA DE CAMPO // NÚCLEO DETERMINISTA</span>
                            </div>
                            <h2 class="font-headline-lg text-headline-lg text-on-surface uppercase tracking-wide">
                                Capacidades Críticas de Supervisión e Integración Física
                            </h2>
                        </div>
                        <span class="font-code-xs text-code-xs text-outline uppercase">
                            ESPECIFICACIÓN HARDWARE: REV-SCADA-4 // BUS ISO-11898-2
                        </span>
                    </div>

                    <!-- Bento-style 3-Column Technical Modules -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">
                        <!-- Card 1: DMA Edge Acquisition -->
                        <div class="bg-surface-container p-space-lg shadow-md flex flex-col justify-between hover:bg-surface-container-high transition-colors group border border-outline-variant/30">
                            <div class="flex flex-col gap-space-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-caps text-label-caps text-primary uppercase bg-surface-container-lowest px-2 py-0.5">MÓDULO ADQ-01</span>
                                    <span class="w-7 h-7 bg-surface-container-lowest flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">developer_board</span>
                                    </span>
                                </div>
                                <h3 class="font-headline-md text-headline-md text-on-surface uppercase tracking-wide">
                                    Adquisición Edge DMA
                                </h3>
                                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                    Muestreo de 500ms directo en microcontroladores <strong class="text-on-surface">ESP32-S3</strong> mediante acceso directo a memoria (DMA) y transceptores de bus CAN diferencial de grado industrial. Libre de contención de CPU y sin latencias de polling.
                                </p>
                            </div>
                            <div class="mt-space-md pt-space-sm bg-surface-container-lowest p-space-sm flex flex-col gap-1 border border-outline-variant/30">
                                <div class="flex justify-between items-center text-code-xs text-outline">
                                    <span>CAN RX FIFO DEPTH</span>
                                    <span class="text-secondary font-mono">64 REGISTROS</span>
                                </div>
                                <div class="w-full bg-surface-container h-1.5 overflow-hidden">
                                    <div class="bg-primary h-full w-[35%]"></div>
                                </div>
                                <span class="font-code-xs text-code-xs text-on-surface-variant font-mono">Tasa de colisión: 0.000% [Aislado galvánico]</span>
                            </div>
                        </div>

                        <!-- Card 2: Automated Incident Matrix -->
                        <div class="bg-surface-container p-space-lg shadow-md flex flex-col justify-between hover:bg-surface-container-high transition-colors group border border-outline-variant/30">
                            <div class="flex flex-col gap-space-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-caps text-label-caps text-tertiary uppercase bg-surface-container-lowest px-2 py-0.5">MÓDULO INTERLOCK-02</span>
                                    <span class="w-7 h-7 bg-surface-container-lowest flex items-center justify-center text-tertiary group-hover:bg-tertiary group-hover:text-on-tertiary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">crisis_alert</span>
                                    </span>
                                </div>
                                <h3 class="font-headline-md text-headline-md text-on-surface uppercase tracking-wide">
                                    Matriz de Incidentes Automatizada
                                </h3>
                                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                    Despacho automático sin intervención humana de <strong class="text-on-surface">protocolos HVAC Nivel 2</strong>, encendido de sirena local, apagado selectivo de racks y purgas de seguridad ante fugas de gas o sobretemperatura.
                                </p>
                            </div>
                            <div class="mt-space-md pt-space-sm bg-surface-container-lowest p-space-sm flex flex-col gap-1 border border-outline-variant/30">
                                <div class="flex justify-between items-center text-code-xs text-outline">
                                    <span>ESTADO DE ENCLAVAMIENTO</span>
                                    <span class="text-secondary font-mono">ARMADO (SIL-3)</span>
                                </div>
                                <div class="grid grid-cols-3 gap-1 text-center font-code-xs text-[10px] mt-1">
                                    <span class="bg-surface-container py-1 text-secondary">RACK EXT: NOM</span>
                                    <span class="bg-surface-container py-1 text-secondary">HVAC-L1: OK</span>
                                    <span class="bg-surface-container py-1 text-tertiary">PURGA: STBY</span>
                                </div>
                                <span class="font-code-xs text-code-xs text-on-surface-variant font-mono">Relevadores estado sólido: 16 canales</span>
                            </div>
                        </div>

                        <!-- Card 3: WAL SQLite Resilient Buffer -->
                        <div class="bg-surface-container p-space-lg shadow-md flex flex-col justify-between hover:bg-surface-container-high transition-colors group border border-outline-variant/30">
                            <div class="flex flex-col gap-space-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-caps text-label-caps text-secondary uppercase bg-surface-container-lowest px-2 py-0.5">MÓDULO PERSISTENCIA-03</span>
                                    <span class="w-7 h-7 bg-surface-container-lowest flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-on-secondary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">database</span>
                                    </span>
                                </div>
                                <h3 class="font-headline-md text-headline-md text-on-surface uppercase tracking-wide">
                                    Auditoría &amp; Buffer WAL
                                </h3>
                                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                    Búfer circular de <strong class="text-on-surface">84,200 eventos</strong> con persistencia Write-Ahead Logging (WAL) en base de datos. Cero pérdida de telemetría durante caídas de enlace ascendente con sincronización diferencial automática.
                                </p>
                            </div>
                            <div class="mt-space-md pt-space-sm bg-surface-container-lowest p-space-sm flex flex-col gap-1 border border-outline-variant/30">
                                <div class="flex justify-between items-center text-code-xs text-outline">
                                    <span>STORAGE LOCAL / WAL</span>
                                    <span class="text-primary font-mono">8 MB FLASH / WAL</span>
                                </div>
                                <div class="w-full bg-surface-container h-1.5 overflow-hidden">
                                    <div class="bg-secondary h-full w-[62%]"></div>
                                </div>
                                <span class="font-code-xs text-code-xs text-on-surface-variant font-mono">Transacciones sincronizadas: 100.0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sensor Hardware Specs Section -->
            <section class="w-full px-margin py-space-xl bg-surface-container-low" id="especificaciones">
                <div class="w-full max-w-[1720px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
                    <div class="lg:col-span-6 flex flex-col gap-space-md">
                        <div class="flex items-center gap-space-xs text-secondary">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            <span class="font-label-caps text-label-caps uppercase tracking-widest">SENSORES HOMOLOGADOS</span>
                        </div>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface uppercase tracking-wide">
                            Matriz de Medición Térmica de Precisión Industrial
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Cada nodo de adquisición periférica incluye entradas digitales I2C blindadas de alta inmunidad EMI y transductores ambientales analógicos calibrados para la sala térmica de Intalnet.
                        </p>
                        <div class="flex flex-col gap-space-xs">
                            <div class="bg-surface-container-high p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                                <div class="flex items-center gap-space-sm">
                                    <span class="w-6 h-6 bg-surface-container-lowest flex items-center justify-center text-primary text-[12px] font-mono">01</span>
                                    <div>
                                        <div class="font-headline-sm text-headline-sm text-on-surface">Sensirion SHT31-D</div>
                                        <div class="font-code-xs text-code-xs text-outline">Sensor Digital de Temperatura &amp; Humedad Relativa (I²C 0x44 / 0x45)</div>
                                    </div>
                                </div>
                                <span class="font-data-metric text-[14px] text-secondary font-mono">±0.2°C / ±2% RH</span>
                            </div>

                            <div class="bg-surface-container-high p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                                <div class="flex items-center gap-space-sm">
                                    <span class="w-6 h-6 bg-surface-container-lowest flex items-center justify-center text-tertiary text-[12px] font-mono">02</span>
                                    <div>
                                        <div class="font-headline-sm text-headline-sm text-on-surface">Transductor MQ-2 / VOC Array</div>
                                        <div class="font-code-xs text-code-xs text-outline">Detección de Humo Iónico, Gases &amp; Solventes Volátiles</div>
                                    </div>
                                </div>
                                <span class="font-data-metric text-[14px] text-tertiary font-mono">10 - 1000 PPM</span>
                            </div>

                            <div class="bg-surface-container-high p-space-sm flex items-center justify-between shadow-sm border border-outline-variant/30">
                                <div class="flex items-center gap-space-sm">
                                    <span class="w-6 h-6 bg-surface-container-lowest flex items-center justify-center text-primary text-[12px] font-mono">03</span>
                                    <div>
                                        <div class="font-headline-sm text-headline-sm text-on-surface">Etapa de Potencia &amp; Sirena</div>
                                        <div class="font-code-xs text-code-xs text-outline">Accionamiento de Alarma Sonora y LEDs con Silenciador Físico</div>
                                    </div>
                                </div>
                                <span class="font-data-metric text-[14px] text-primary font-mono">12V / 105 dB</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6 bg-surface-container-lowest p-space-lg shadow-xl border border-outline-variant/40 flex flex-col gap-space-md">
                        <div class="flex items-center justify-between border-b border-outline-variant/40 pb-space-xs">
                            <span class="font-label-caps text-label-caps text-primary uppercase">MAPA DE PINES DEL HARDWARE // ESP32-WROOM-32</span>
                            <span class="font-code-xs text-code-xs text-secondary font-mono">REV MONTERÍA-2026</span>
                        </div>
                        <div class="grid grid-cols-2 gap-space-sm font-mono text-code-xs">
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 21 (SDA)</span>
                                <span class="text-on-surface">SHT31-D I²C DATA</span>
                            </div>
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 22 (SCL)</span>
                                <span class="text-on-surface">SHT31-D I²C CLOCK</span>
                            </div>
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 34 (ADC)</span>
                                <span class="text-on-surface">MQ-2 ANALOG IN</span>
                            </div>
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 25 (OUT)</span>
                                <span class="text-on-surface">SIRENA LOCAL (RELÉ)</span>
                            </div>
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 26 (OUT)</span>
                                <span class="text-on-surface">LED STATUS ALARMA</span>
                            </div>
                            <div class="bg-surface-container p-space-sm flex justify-between">
                                <span class="text-outline">GPIO 27 (IN)</span>
                                <span class="text-on-surface">BOTÓN SILENCIADOR</span>
                            </div>
                        </div>
                        <div class="bg-surface-container-high p-space-sm text-code-xs text-on-surface-variant flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-secondary text-[16px]">info</span>
                            <span>La lógica crítica de corte y sirena opera de forma autónoma en el ESP32, sin depender de la conectividad web.</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Enterprise Compliance Section -->
            <section class="w-full px-margin py-space-xl bg-surface-container" id="normativa">
                <div class="w-full max-w-[1720px] mx-auto flex flex-col gap-space-lg">
                    <div class="flex flex-col items-center text-center max-w-3xl mx-auto">
                        <span class="font-label-caps text-label-caps text-primary uppercase tracking-widest mb-1">CUMPLIMIENTO NORMATIVO Y RESILIENCIA CRÍTICA</span>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface uppercase tracking-wide">
                            Certificaciones de Arquitectura y Tolerancia a Fallas
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                            Diseñado bajo especificaciones de salas blancas y centros de datos de misión crítica que no admiten degradación térmica.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-md">
                        <div class="bg-surface-container-lowest p-space-md flex flex-col items-center text-center gap-space-xs shadow-sm border border-outline-variant/30">
                            <span class="material-symbols-outlined text-primary text-[32px]">shield</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface">IEC 61508 SIL-3</span>
                            <span class="font-code-xs text-code-xs text-outline">Seguridad Funcional de Sistemas Electrónicos Programables</span>
                        </div>
                        <div class="bg-surface-container-lowest p-space-md flex flex-col items-center text-center gap-space-xs shadow-sm border border-outline-variant/30">
                            <span class="material-symbols-outlined text-secondary text-[32px]">air</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface">ISO 14644-1 CLASE 4</span>
                            <span class="font-code-xs text-code-xs text-outline">Monitoreo de Partículas y Climatización en Salas Limpias</span>
                        </div>
                        <div class="bg-surface-container-lowest p-space-md flex flex-col items-center text-center gap-space-xs shadow-sm border border-outline-variant/30">
                            <span class="material-symbols-outlined text-tertiary text-[32px]">security</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface">EN 50600-2-2</span>
                            <span class="font-code-xs text-code-xs text-outline">Distribución de Energía &amp; Enfriamiento de Centro de Cómputo</span>
                        </div>
                        <div class="bg-surface-container-lowest p-space-md flex flex-col items-center text-center gap-space-xs shadow-sm border border-outline-variant/30">
                            <span class="material-symbols-outlined text-primary text-[32px]">cable</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface">TIA-942 RATED 4</span>
                            <span class="font-code-xs text-code-xs text-outline">Infraestructura de Telecomunicaciones Tolerante a Fallas</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bottom CTA Industrial Command Banner -->
            <section class="w-full px-margin py-space-xl bg-surface-container-lowest relative overflow-hidden">
                <div class="w-full max-w-[1720px] mx-auto bg-gradient-to-r from-surface-container via-surface-container-high to-surface-container p-space-xl flex flex-col lg:flex-row items-center justify-between gap-space-lg shadow-2xl relative border border-outline-variant/40">
                    <div class="flex flex-col gap-space-xs max-w-2xl">
                        <div class="flex items-center gap-space-xs">
                            <span class="w-2 h-2 rounded-full bg-secondary"></span>
                            <span class="font-label-caps text-label-caps text-secondary uppercase font-semibold">DESPLIEGUE INMEDIATO EN CAMPO</span>
                        </div>
                        <h2 class="font-headline-xl text-headline-xl text-on-surface uppercase tracking-wide leading-none">
                            Proteja la sala térmica de Intalnet con monitoreo SCADA en tiempo real
                        </h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Integre nodos ESP32 con sensores SHT31 y MQ-2 en minutos. Conectividad web determinista y trazabilidad completa de incidentes.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-space-md w-full lg:w-auto">
                        @auth
                            <a class="w-full sm:w-auto px-space-xl py-3 bg-primary text-on-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-space-sm shadow-md" href="{{ route('dashboard') }}">
                                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                                <span>Abrir Consola de Control</span>
                            </a>
                        @else
                            <a class="w-full sm:w-auto px-space-xl py-3 bg-primary text-on-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-space-sm shadow-md" href="{{ route('login') }}">
                                <span class="material-symbols-outlined text-[20px]">login</span>
                                <span>Iniciar Sesión en SCADA</span>
                            </a>
                            @if (Route::has('register'))
                                <a class="w-full sm:w-auto px-space-xl py-3 bg-surface-container-lowest text-primary font-headline-md text-headline-md tracking-wider uppercase hover:bg-surface-bright transition-colors flex items-center justify-center gap-space-sm shadow-sm border border-primary/40" href="{{ route('register') }}">
                                    <span class="material-symbols-outlined text-[20px]">vpn_key</span>
                                    <span>Registrar Operador</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-surface-container-lowest shadow-[0_-1px_12px_rgba(0,0,0,0.5)] border-t border-outline-variant/30">
        <div class="w-full px-margin py-space-xl flex flex-col md:flex-row items-center justify-between gap-space-md">
            <div class="flex flex-col gap-0.5">
                <div class="flex items-center gap-space-sm">
                    <span class="font-headline-sm text-headline-sm text-on-surface tracking-wider">INTALNET TELEMETRY OS</span>
                    <span class="px-1.5 py-0.5 bg-surface-container font-code-xs text-code-xs text-on-surface-variant">ISO-14644-1 CLASE 4</span>
                    <span class="px-1.5 py-0.5 bg-surface-container font-code-xs text-code-xs text-secondary">IEC-61508 SIL-3</span>
                </div>
                <p class="font-code-xs text-code-xs text-outline">Plataforma SCADA de grado industrial para control térmico de salas de servidores y automatización de racks.</p>
            </div>
            <div class="flex items-center gap-space-lg">
                <div class="flex items-center gap-space-xs">
                    <span class="font-code-xs text-code-xs text-outline">ESTADO TELEMETRÍA:</span>
                    <span class="font-code-xs text-code-xs text-secondary font-medium">EN LÍNEA (SHT31-D + MQ2)</span>
                </div>
                <div class="flex items-center gap-space-xs">
                    <span class="font-code-xs text-code-xs text-outline">SYNC:</span>
                    <span class="font-code-xs text-code-xs text-primary">NTP UTC-5</span>
                </div>
            </div>
            <div class="font-code-xs text-code-xs text-outline text-right">© {{ date('Y') }} INTALNET TELEMETRY OS • MONTERÍA, CÓRDOBA.</div>
        </div>
    </footer>

    <!-- Live Telemetry Simulation Script -->
    <script>
        (function () {
            const tempEl = document.getElementById('val-temp');
            const flowEl = document.getElementById('val-flow');
            const voltEl = document.getElementById('val-volt');
            const timeAgoEl = document.getElementById('time-ago');

            let lastTime = 412;

            setInterval(() => {
                if (tempEl) {
                    const baseTemp = 21.8;
                    const delta = (Math.random() * 0.4 - 0.2).toFixed(1);
                    tempEl.textContent = (baseTemp + parseFloat(delta)).toFixed(1);
                }

                if (flowEl) {
                    const baseFlow = 1420;
                    const deltaFlow = Math.floor(Math.random() * 30 - 15);
                    flowEl.textContent = (baseFlow + deltaFlow).toLocaleString('es-ES');
                }

                if (voltEl) {
                    const baseVolt = 230.4;
                    const deltaVolt = (Math.random() * 0.8 - 0.4).toFixed(1);
                    voltEl.textContent = (baseVolt + parseFloat(deltaVolt)).toFixed(1);
                }

                if (timeAgoEl) {
                    lastTime = Math.floor(Math.random() * 300 + 200);
                    timeAgoEl.textContent = lastTime + 'ms';
                }
            }, 1500);
        })();
    </script>
</body>
</html>
