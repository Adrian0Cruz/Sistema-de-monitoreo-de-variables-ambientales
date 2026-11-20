<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} – Monitoreo Térmico de Sala Técnica | Intalnet</title>
    <meta name="description" content="Plataforma de monitoreo ambiental en tiempo real para temperatura, humedad relativa y detección de humo en la sala técnica de servidores y fibra óptica de Intalnet (Montería, Colombia).">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=geist@1,2,3,4,5,6,7&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col selection:bg-primary/20 selection:text-primary"
      x-data="{ mobileNav: false }">

    {{-- ======================================================== --}}
    {{-- TOP NAVIGATION                                            --}}
    {{-- ======================================================== --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-b border-outline-variant">
        <nav class="max-w-[1600px] mx-auto px-4 sm:px-6 md:px-margin-lg h-14 flex items-center justify-between">

            {{-- Brand --}}
            <a class="flex items-center gap-space-sm" href="{{ url('/') }}">
                <div class="w-7 h-7 rounded-lg bg-primary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary-container text-[18px]">sensors</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-headline-sm text-headline-sm text-on-surface">AetherSens</span>
                    <span class="hidden sm:inline text-outline text-xs">&bull;</span>
                    <span class="hidden sm:inline font-label-sm text-label-sm text-outline">Intalnet</span>
                </div>
            </a>

            {{-- Center links ordenados estrictamente según el flujo de la página --}}
            <div class="hidden md:flex items-center gap-space-xl text-on-surface-variant font-body-md text-body-md">
                <a href="#plataforma" class="hover:text-on-surface transition-colors">Plataforma</a>
                <a href="#telemetria" class="hover:text-on-surface transition-colors">Telemetría</a>
                <a href="#zonas-racks" class="hover:text-on-surface transition-colors">Infraestructura</a>
                <a href="#equipo" class="hover:text-on-surface transition-colors">Equipo</a>
                <a href="{{ route('docs') }}" class="hover:text-on-surface transition-colors">Manuales / Docs</a>
            </div>

            {{-- Right actions: Inicio de sesión + Botón Menú Móvil --}}
            <div class="flex items-center gap-2 sm:gap-space-md">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-md text-xs sm:text-label-md transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">dashboard</span>
                            <span>Panel</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="flex items-center gap-1.5 font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-on-surface transition-colors px-2 py-1">
                            <span class="material-symbols-outlined text-[18px]">login</span>
                            <span>Iniciar Sesión</span>
                        </a>
                    @endauth
                @endif

                {{-- Botón Hamburguesa para Móvil (< 768px) --}}
                <button type="button"
                        @click="mobileNav = !mobileNav"
                        class="md:hidden p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container-high transition-colors"
                        aria-label="Menú principal">
                    <span class="material-symbols-outlined text-[24px]" x-text="mobileNav ? 'close' : 'menu'">menu</span>
                </button>
            </div>

        </nav>

        {{-- Menú Desplegable Móvil --}}
        <div x-show="mobileNav"
             x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-surface-container border-b border-outline-variant px-4 py-3 flex flex-col gap-2 font-body-md text-body-md text-on-surface-variant">
            <a href="#plataforma" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-surface-container-high hover:text-on-surface transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">view_quilt</span>
                <span>Plataforma</span>
            </a>
            <a href="#telemetria" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-surface-container-high hover:text-on-surface transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">equalizer</span>
                <span>Telemetría</span>
            </a>
            <a href="#zonas-racks" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-surface-container-high hover:text-on-surface transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">dns</span>
                <span>Infraestructura</span>
            </a>
            <a href="#equipo" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-surface-container-high hover:text-on-surface transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">group</span>
                <span>Equipo</span>
            </a>
            <a href="{{ route('docs') }}" @click="mobileNav = false" class="py-2 px-3 rounded-lg hover:bg-surface-container-high hover:text-on-surface transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">menu_book</span>
                <span>Manuales / Docs</span>
            </a>
        </div>
    </header>

    <main class="flex-1 pt-14">

        {{-- ======================================================== --}}
        {{-- STATUS STRIP (DATOS REALES DEL HARDWARE INTALNET)          --}}
        {{-- ======================================================== --}}
        <section class="w-full bg-surface-container-low/70 py-space-sm px-margin-md md:px-margin-lg border-b border-outline-variant/30">
            <div class="max-w-[1600px] mx-auto flex flex-wrap items-center justify-between gap-space-sm">
                <div class="flex items-center gap-space-md">
                    <span class="inline-flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant">
                        <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                        SALA TÉCNICA 01 &bull; INTALNET
                    </span>
                    <span class="text-outline-variant font-body-sm text-body-sm hidden sm:inline">&bull;</span>
                    <span class="font-code-sm text-code-sm text-on-surface-variant">MONTERÍA, CÓRDOBA</span>
                </div>
                <div class="flex items-center gap-space-lg text-on-surface-variant font-code-sm text-code-sm flex-wrap">
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Controlador</span>
                        <span class="text-primary font-medium">ESP32 DevKit USB-C</span>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Sensores</span>
                        <span class="text-on-surface font-medium">SHT40 (I2C) + MQ-2</span>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Broker</span>
                        <span class="text-tertiary font-medium">MQTT (broker.hivemq.com)</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- HERO SECTION                                               --}}
        {{-- ======================================================== --}}
        <section class="w-full px-margin-md md:px-margin-lg py-16 md:py-24 flex flex-col items-center justify-center text-center relative overflow-hidden">

            {{-- Ambient radial glow --}}
            <div class="absolute w-[640px] h-[320px] bg-primary/5 rounded-full blur-3xl pointer-events-none -top-12"></div>

            <div class="max-w-4xl mx-auto flex flex-col items-center relative z-10">

                {{-- Intalnet Partner Pill --}}
                <div class="inline-flex items-center gap-space-xs px-3.5 py-1 rounded-full bg-surface-container-high shadow-sm mb-space-lg border border-outline-variant/30">
                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                    <span class="font-label-md text-label-md text-on-surface font-medium">Intalnet &bull; Fibra Óptica &amp; Seguridad</span>
                    <span class="text-outline font-label-sm text-label-sm">&bull;</span>
                    <span class="font-label-md text-label-md text-primary">Sala de Servidores 01</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="font-display-lg text-2xl sm:text-3xl md:text-[44px] md:leading-[52px] text-on-surface tracking-tight font-semibold mb-space-md max-w-3xl">
                    Monitoreo Ambiental de Servidores
                </h1>

                {{-- Subtitle realista --}}
                <p class="font-body-lg text-body-md sm:text-body-lg text-on-surface-variant max-w-2xl mx-auto mb-space-xl leading-relaxed">
                    Plataforma de telemetría térmica, humedad relativa y detección temprana de humo para la infraestructura de fibra óptica y telecomunicaciones de Intalnet en Montería, Colombia.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-space-md mb-space-lg w-full max-w-md sm:max-w-none">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-space-sm px-6 sm:px-8 py-3.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container font-headline-sm text-headline-sm transition-all shadow-card group">
                            <span>Acceder al Panel de Control</span>
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-space-sm px-6 sm:px-8 py-3.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container font-headline-sm text-headline-sm transition-all shadow-card group">
                            <span>Iniciar Sesión de Operador</span>
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </a>
                    @endauth

                    <a href="#telemetria"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-6 py-3.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors border border-outline-variant/30">
                        <span class="material-symbols-outlined text-outline text-[18px]">equalizer</span>
                        <span>Ver Umbrales ASHRAE</span>
                    </a>
                </div>

                {{-- Quick Status Footnote con datos reales --}}
                <div class="flex items-center gap-space-xs text-on-surface-variant font-code-sm text-code-sm flex-wrap justify-center">
                    <span class="material-symbols-outlined text-secondary text-[16px]">sensors</span>
                    <span>Microcontrolador ESP32 en Sala 01</span>
                    <span class="text-outline font-body-sm text-body-sm">&bull;</span>
                    <span class="material-symbols-outlined text-tertiary text-[16px]">lock</span>
                    <span>Acceso exclusivo para personal autorizado de la empresa</span>
                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- SECCIÓN 1: PLATAFORMA (Capacidades del Sistema Central)    --}}
        {{-- ======================================================== --}}
        <section id="plataforma" class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20 scroll-mt-20">
            <div class="mb-space-lg text-left">
                <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest block font-medium">Arquitectura de Operación</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Capacidades del Sistema Central</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Supervisión ininterrumpida y protección de hardware para cabeceras y OLTs de fibra óptica.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">

                {{-- Tarjeta 1: Telemetría en Tiempo Real --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors border border-outline-variant/30">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-primary mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">cycle</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Telemetría en Tiempo Real</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Sincronización continua de variables térmicas con protocolo MQTT sobre HiveMQ. Transmisión cada 3 segundos hacia el panel de control vía WebSocket.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between border-t border-outline-variant/20">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-secondary font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            Telemetría activa 24/7
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">QoS-1 MQTT</span>
                    </div>
                </div>

                {{-- Tarjeta 2: Gestión Activa de Alertas y Bocina --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors border border-outline-variant/30">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">notifications_active</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Alarmas y Actuadores Físicos</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Semáforo LED de 3 estados (Normal, Advertencia, Crítico), disparo automático de bocina acústica (buzzer) en caso de sobrecalentamiento o humo, y pulsador industrial de silenciamiento manual.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between border-t border-outline-variant/20">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-tertiary font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                            Buzzer + Pulsador NO
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">Alarma Sonora</span>
                    </div>
                </div>

                {{-- Tarjeta 3: Seguridad y Calibración Remota --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors border border-outline-variant/30">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">tune</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Calibración Remota Edge-First</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Ajuste de umbrales críticos desde la página web sin reprogramar el microcontrolador. Los límites se transmiten con retención MQTT y se respaldan en la memoria Flash permanente del ESP32.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between border-t border-outline-variant/20">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-primary font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            Flash NVS Persistente
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">Autónomo</span>
                    </div>
                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- SECCIÓN 2: TELEMETRÍA (Línea Base Operativa & ASHRAE)      --}}
        {{-- ======================================================== --}}
        <section id="telemetria" class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20 scroll-mt-20">
            <div class="bg-surface-container rounded-xl p-space-lg shadow-sm border border-outline-variant/30">

                {{-- Panel Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-space-md mb-space-lg gap-space-sm bg-surface-container border-b border-outline-variant/20">
                    <div>
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-primary text-[20px]">thermostat</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface">Línea Base Operativa &amp; Umbrales ASHRAE TC 9.9</h2>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Rangos recomendados para salas de telecomunicaciones en clima tropical (Montería)</p>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="px-space-sm py-0.5 rounded bg-tertiary-container/30 text-tertiary font-label-sm text-label-sm font-medium">SHT40 &bull; MQ-2</span>
                        <span class="text-outline-variant font-body-sm text-body-sm">&bull;</span>
                        <span class="font-code-sm text-code-sm text-on-surface-variant">SALA 01</span>
                    </div>
                </div>

                {{-- 3-column metrics --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">

                    {{-- Temperatura --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between border border-outline-variant/20">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Temperatura Sala de Racks</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">18°C – 27°C</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">thermostat</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Rango Óptimo A1</span>
                                <span class="text-error font-medium">Crítico: &gt; 38.0°C</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-surface-container-lowest overflow-hidden flex">
                                <div class="h-full bg-outline-variant" style="width: 25%"></div>
                                <div class="h-full bg-tertiary" style="width: 50%"></div>
                                <div class="h-full bg-error" style="width: 25%"></div>
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>15°C</span><span>18°C</span><span>27°C</span><span>38°C</span>
                            </div>
                        </div>
                    </div>

                    {{-- Humedad --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between border border-outline-variant/20">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Humedad Relativa Óptima</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">40% – 60% RH</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-[18px]">humidity_mid</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Prevenir ESD / Condensación</span>
                                <span class="text-secondary font-medium">Límite: &lt; 75% RH</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-surface-container-lowest overflow-hidden flex">
                                <div class="h-full bg-outline-variant" style="width: 20%"></div>
                                <div class="h-full bg-tertiary" style="width: 50%"></div>
                                <div class="h-full bg-error" style="width: 30%"></div>
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>20%</span><span>40%</span><span>60%</span><span>75%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Gas & Humo --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between border border-outline-variant/20">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Detección de Humo &amp; Gases</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">&lt; 1800 ADC</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-tertiary">
                                <span class="material-symbols-outlined text-[18px]">detector_smoke</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Sensor MQ-2 (GPIO 34)</span>
                                <span class="text-tertiary font-medium">Escala: 0 – 4095 ADC</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-surface-container-lowest overflow-hidden flex">
                                <div class="h-full bg-tertiary" style="width: 44%"></div>
                                <div class="h-full bg-secondary" style="width: 16%"></div>
                                <div class="h-full bg-error" style="width: 40%"></div>
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>0</span><span>800</span><span>1800</span><span>4095</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- SECCIÓN 3: INFRAESTRUCTURA (Sala Técnica & Fibra Óptica)  --}}
        {{-- ======================================================== --}}
        <section id="zonas-racks" class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20 scroll-mt-20">
            <div class="bg-surface-container-low rounded-xl p-space-lg md:p-12 flex flex-col lg:flex-row items-center justify-between gap-space-xl border border-outline-variant/30">
                <div class="text-left max-w-4xl">
                    <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest block font-medium mb-space-xs">Infraestructura Crítica</span>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface font-semibold mb-space-md">Sala Técnica y Cabeceras FTTH — Intalnet</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-space-lg leading-relaxed">
                        Los gabinetes albergan las OLTs de fibra óptica, switches de distribución core y sistemas de energía ininterrumpida (UPS). El monitoreo de temperatura con AetherSens previene la degradación de láseres en transceptores ópticos SFP y evita cortes imprevistos en el servicio de internet y seguridad para Montería y la región.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md font-code-sm text-code-sm">
                        <div class="bg-surface-container p-space-sm rounded border border-outline-variant/20">
                            <span class="text-outline block font-label-sm text-label-sm">UBICACIÓN</span>
                            <span class="text-on-surface font-medium text-body-md">Montería, Córdoba</span>
                        </div>
                        <div class="bg-surface-container p-space-sm rounded border border-outline-variant/20">
                            <span class="text-outline block font-label-sm text-label-sm">RED SERVIDA</span>
                            <span class="text-primary font-medium text-body-md">FTTH &amp; Seguridad IP</span>
                        </div>
                        <div class="bg-surface-container p-space-sm rounded border border-outline-variant/20">
                            <span class="text-outline block font-label-sm text-label-sm">PROTECCIÓN</span>
                            <span class="text-tertiary font-medium text-body-md">Vigilancia Térmica 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- SECCIÓN 4: EQUIPO DE DESARROLLO (UNIVERSIDAD DE CÓRDOBA)   --}}
        {{-- ======================================================== --}}
        <section id="equipo" class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20 scroll-mt-20">
            <div class="flex flex-col gap-8">

                {{-- Header de la sección --}}
                <div class="text-left">
                    <div class="inline-flex items-center gap-space-xs px-3.5 py-1 rounded-full bg-surface-container-high border border-outline-variant/30 mb-3">
                        <span class="material-symbols-outlined text-[16px] text-primary">school</span>
                        <span class="font-label-md text-label-md text-on-surface font-medium">Universidad de Córdoba</span>
                        <span class="text-outline text-xs">&bull;</span>
                        <span class="font-code-sm text-[11px] text-primary">Montería, Colombia</span>
                    </div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface">Equipo de Desarrollo</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-1">
                        Estudiantes de la Universidad de Córdoba responsables de la conceptualización, desarrollo de software, hardware IoT y arquitectura de telemetría de AetherSens.
                    </p>
                </div>

                {{-- Grid de los 4 Integrantes del Equipo (Sin fotos personales) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- 1. Cristian González --}}
                    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant/30 hover:border-primary/50 hover:bg-surface-container-high transition-all flex flex-col justify-between shadow-sm group">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-lg mb-5 group-hover:scale-105 transition-transform">
                                CG
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold group-hover:text-primary transition-colors">
                                Cristian González
                            </h3>
                            <span class="font-code-sm text-[12px] text-primary block mt-1">Estudiante de Ingeniería</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2 leading-relaxed">
                                Universidad de Córdoba
                            </p>
                        </div>
                        <div class="pt-4 mt-6 border-t border-outline-variant/20 flex items-center justify-between text-xs text-on-surface-variant font-code-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-primary"></span>
                                <span>Desarrollador</span>
                            </span>
                            <span class="text-outline">Unicórdoba</span>
                        </div>
                    </div>

                    {{-- 2. Jesús Hernández --}}
                    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant/30 hover:border-secondary/50 hover:bg-surface-container-high transition-all flex flex-col justify-between shadow-sm group">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary font-bold text-lg mb-5 group-hover:scale-105 transition-transform">
                                JH
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold group-hover:text-secondary transition-colors">
                                Jesús Hernández
                            </h3>
                            <span class="font-code-sm text-[12px] text-secondary block mt-1">Estudiante de Ingeniería</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2 leading-relaxed">
                                Universidad de Córdoba
                            </p>
                        </div>
                        <div class="pt-4 mt-6 border-t border-outline-variant/20 flex items-center justify-between text-xs text-on-surface-variant font-code-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                <span>Desarrollador</span>
                            </span>
                            <span class="text-outline">Unicórdoba</span>
                        </div>
                    </div>

                    {{-- 3. Julio Nova --}}
                    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant/30 hover:border-tertiary/50 hover:bg-surface-container-high transition-all flex flex-col justify-between shadow-sm group">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-tertiary/10 border border-tertiary/20 flex items-center justify-center text-tertiary font-bold text-lg mb-5 group-hover:scale-105 transition-transform">
                                JN
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold group-hover:text-tertiary transition-colors">
                                Julio Nova
                            </h3>
                            <span class="font-code-sm text-[12px] text-tertiary block mt-1">Estudiante de Ingeniería</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2 leading-relaxed">
                                Universidad de Córdoba
                            </p>
                        </div>
                        <div class="pt-4 mt-6 border-t border-outline-variant/20 flex items-center justify-between text-xs text-on-surface-variant font-code-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                                <span>Desarrollador</span>
                            </span>
                            <span class="text-outline">Unicórdoba</span>
                        </div>
                    </div>

                    {{-- 4. Jorge Oviedo --}}
                    <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant/30 hover:border-primary/50 hover:bg-surface-container-high transition-all flex flex-col justify-between shadow-sm group">
                        <div>
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-lg mb-5 group-hover:scale-105 transition-transform">
                                JO
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold group-hover:text-primary transition-colors">
                                Jorge Oviedo
                            </h3>
                            <span class="font-code-sm text-[12px] text-primary block mt-1">Estudiante de Ingeniería</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2 leading-relaxed">
                                Universidad de Córdoba
                            </p>
                        </div>
                        <div class="pt-4 mt-6 border-t border-outline-variant/20 flex items-center justify-between text-xs text-on-surface-variant font-code-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-primary"></span>
                                <span>Desarrollador</span>
                            </span>
                            <span class="text-outline">Unicórdoba</span>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- CONFIDENTIALITY NOTICE                                    --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg pb-16">
            <div class="bg-surface-container-lowest rounded-lg p-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm text-center sm:text-left border border-outline-variant/20">
                <div class="flex items-center gap-space-sm">
                    <span class="material-symbols-outlined text-outline text-[20px]">verified_user</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        <strong class="text-on-surface font-medium">Aviso de Confidencialidad:</strong>
                        Sistema de telemetría interno. El registro de cuentas está restringido al personal autorizado.
                    </p>
                </div>
                <div class="shrink-0 font-code-sm text-code-sm text-outline">
                    UNIVERSIDAD DE CÓRDOBA &bull; MONTERÍA
                </div>
            </div>
        </section>

    </main>

    {{-- ======================================================== --}}
    {{-- FOOTER                                                    --}}
    {{-- ======================================================== --}}
    <footer class="w-full bg-surface-container-lowest py-space-lg border-t border-outline-variant/20">
        <div class="max-w-[1600px] mx-auto px-margin-md md:px-margin-lg flex flex-col sm:flex-row items-center justify-between gap-space-xs text-center sm:text-left">
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                &copy; {{ date('Y') }} AetherSens &bull; Estudiantes de la Universidad de Córdoba (Montería, Colombia)
            </p>
            <div class="flex items-center gap-space-md font-body-sm text-body-sm text-on-surface-variant">
                <a href="#equipo" class="hover:text-primary transition-colors">Equipo de Desarrollo</a>
                <span>&bull;</span>
                <a href="{{ route('docs') }}" class="hover:text-primary transition-colors">Documentación Técnica</a>
            </div>
        </div>
    </footer>

</body>
</html>
