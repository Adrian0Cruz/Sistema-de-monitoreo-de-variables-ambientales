<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} – Centro de Documentación y Manuales</title>
    <meta name="description" content="Manuales de usuario, especificaciones de hardware ESP32, guías de instalación y normativas ASHRAE TC 9.9 de AetherSens.">

    {{-- Favicon para todas las vistas --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=geist@1,2,3,4,5,6,7&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col selection:bg-primary/20 selection:text-primary">

    {{-- ======================================================== --}}
    {{-- TOP NAVIGATION                                            --}}
    {{-- ======================================================== --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-b border-outline-variant">
        <nav class="max-w-[1600px] mx-auto px-margin md:px-margin-lg h-14 flex items-center justify-between">

            {{-- Brand --}}
            <a class="flex items-center gap-space-sm" href="{{ url('/') }}">
                <div class="w-7 h-7 rounded-lg bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary-container text-[18px]">sensors</span>
                </div>
                <span class="font-headline-sm text-headline-sm text-on-surface">AetherSens</span>
            </a>

            {{-- Navigation links --}}
            <div class="hidden md:flex items-center gap-space-xl text-on-surface-variant font-body-md text-body-md">
                <a href="{{ url('/#plataforma') }}" class="hover:text-on-surface transition-colors">Plataforma</a>
                <a href="{{ url('/#zonas-racks') }}" class="hover:text-on-surface transition-colors">Zonas &amp; Racks</a>
                <a href="{{ url('/#telemetria') }}" class="hover:text-on-surface transition-colors">Telemetría Activa</a>
                <a href="{{ route('docs') }}" class="text-primary font-medium border-b-2 border-primary py-3.5">Ayuda / Docs</a>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-space-md">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-2 px-4 py-1.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-md text-label-md transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">dashboard</span>
                            <span>Panel de Control</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors">
                            Iniciar Sesión
                        </a>
                        <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                        </div>
                    @endauth
                @endif
            </div>

        </nav>
    </header>

    <main class="flex-1 pt-14" x-data="{ activeCategory: 'all', searchQuery: '' }">

        {{-- ======================================================== --}}
        {{-- HERO SECTION                                               --}}
        {{-- ======================================================== --}}
        <section class="w-full px-margin-md md:px-margin-lg py-16 flex flex-col items-center justify-center text-center relative overflow-hidden bg-surface-container-low/50 border-b border-outline-variant/30">
            <div class="absolute w-[600px] h-[300px] bg-primary/5 rounded-full blur-3xl pointer-events-none -top-10"></div>

            <div class="max-w-4xl mx-auto flex flex-col items-center relative z-10">
                <div class="inline-flex items-center gap-space-xs px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant/40 mb-4">
                    <span class="material-symbols-outlined text-[16px] text-primary">menu_book</span>
                    <span class="font-label-md text-label-md text-on-surface font-medium">Biblioteca Técnica &amp; Manuales de Soporte</span>
                </div>

                <h1 class="font-display-lg text-display-lg md:text-[40px] md:leading-[48px] text-on-surface tracking-tight font-semibold mb-3">
                    Centro de Documentación y Descargas
                </h1>

                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto mb-8">
                    Consulte y descargue los manuales del sistema, procedimientos operativos para el personal del NOC, especificaciones de hardware IoT y directrices de climatización ASHRAE TC 9.9.
                </p>

                {{-- Quick Filter Pills --}}
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <button @click="activeCategory = 'all'"
                            :class="activeCategory === 'all' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant'"
                            class="px-4 py-1.5 rounded-lg font-label-md text-label-md transition-colors cursor-pointer">
                        Todos los Documentos
                    </button>
                    <button @click="activeCategory = 'usuario'"
                            :class="activeCategory === 'usuario' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant'"
                            class="px-4 py-1.5 rounded-lg font-label-md text-label-md transition-colors cursor-pointer">
                        Manuales de Usuario
                    </button>
                    <button @click="activeCategory = 'hardware'"
                            :class="activeCategory === 'hardware' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant'"
                            class="px-4 py-1.5 rounded-lg font-label-md text-label-md transition-colors cursor-pointer">
                        Hardware IoT &amp; ESP32
                    </button>
                    <button @click="activeCategory = 'ashrae'"
                            :class="activeCategory === 'ashrae' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant'"
                            class="px-4 py-1.5 rounded-lg font-label-md text-label-md transition-colors cursor-pointer">
                        Normativa Térmica &amp; Clima
                    </button>
                    <button @click="activeCategory = 'mqtt'"
                            :class="activeCategory === 'mqtt' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant'"
                            class="px-4 py-1.5 rounded-lg font-label-md text-label-md transition-colors cursor-pointer">
                        Protocolo MQTT
                    </button>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- DOWNLOADABLE MANUALS GRID                                 --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg py-16">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest block font-medium">Repositorio Oficial</span>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface">Manuales Disponibles para Descarga Inmediata</h2>
                </div>
                <div class="flex items-center gap-2 text-outline font-code-sm text-code-sm">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>Documentación verificada para AetherSens v2.4</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Document 1: Manual de Usuario --}}
                <div x-show="activeCategory === 'all' || activeCategory === 'usuario'"
                     class="bg-surface-container rounded-xl p-8 border border-outline-variant/30 hover:border-primary/50 transition-all flex flex-col justify-between shadow-card">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[28px]">terminal</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded bg-surface-container-high text-primary font-code-sm text-code-sm">v2.4 Enterprise</span>
                                <span class="px-2.5 py-1 rounded bg-surface-container-highest text-outline font-label-sm text-label-sm uppercase">TXT / DOC</span>
                            </div>
                        </div>

                        <h3 class="font-headline-md text-headline-md text-on-surface font-semibold mb-2">
                            Manual de Usuario y Operaciones del Sistema
                        </h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Guía paso a paso para operadores y supervisores. Detalla la lectura e interpretación de telemetría, gráficas de tiempo real con doble eje en UTC-5, configuración persistente de umbrales en <code class="text-primary font-code-sm">/settings</code>, y protocolos de actuación ante alertas de sobrecalentamiento.
                        </p>

                        <div class="space-y-2 mb-6 font-code-sm text-code-sm text-on-surface-variant bg-surface-container-high/60 p-3.5 rounded-lg border border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Interpretación de tarjetas KPI y estados reactivos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Línea base climática de Montería y geolocalización</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Ajustes persistentes en navegador y almacenamiento Laravel</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-outline">Actualizado: Octubre 2026</span>
                        <a href="{{ asset('manuals/Manual_Usuario_AetherSens_v2.4.txt') }}" download
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container font-label-md text-label-md font-medium transition-colors cursor-pointer shadow-md">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Descargar Manual</span>
                        </a>
                    </div>
                </div>

                {{-- Document 2: Manual de Instalación Hardware ESP32 --}}
                <div x-show="activeCategory === 'all' || activeCategory === 'hardware'"
                     class="bg-surface-container rounded-xl p-8 border border-outline-variant/30 hover:border-secondary/50 transition-all flex flex-col justify-between shadow-card">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-secondary/10 border border-secondary/20 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-[28px]">memory</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded bg-surface-container-high text-secondary font-code-sm text-code-sm">Hardware v1.2</span>
                                <span class="px-2.5 py-1 rounded bg-surface-container-highest text-outline font-label-sm text-label-sm uppercase">Esquema Técnico</span>
                            </div>
                        </div>

                        <h3 class="font-headline-md text-headline-md text-on-surface font-semibold mb-2">
                            Manual de Instalación y Conexionado IoT (ESP32)
                        </h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Especificaciones eléctricas y físicas de conexionado para técnicos en campo. Incluye asignación de pines GPIO (GPIO 4 para DHT11, pin analógico ADC para MQ-2), resistencia pull-up recomendada (10kΩ), aislamiento electromagnético en racks y montaje en pasillo frío.
                        </p>

                        <div class="space-y-2 mb-6 font-code-sm text-code-sm text-on-surface-variant bg-surface-container-high/60 p-3.5 rounded-lg border border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                                <span>Diagrama de pinout exacto: ESP32 + DHT11 + MQ-2</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                                <span>Instalación mecánica vertical en unidades 4U, 22U y 42U</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                                <span>Alimentación 5V regulada con protección contra transitorios</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-outline">Actualizado: Octubre 2026</span>
                        <a href="{{ asset('manuals/Manual_Instalacion_Hardware_ESP32_DHT11.txt') }}" download
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-secondary hover:bg-secondary/90 text-on-secondary font-label-md text-label-md font-medium transition-colors cursor-pointer shadow-md">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Descargar Ficha Hardware</span>
                        </a>
                    </div>
                </div>

                {{-- Document 3: Normativa Térmica ASHRAE & Clima Montería --}}
                <div x-show="activeCategory === 'all' || activeCategory === 'ashrae'"
                     class="bg-surface-container rounded-xl p-8 border border-outline-variant/30 hover:border-tertiary/50 transition-all flex flex-col justify-between shadow-card">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-tertiary/10 border border-tertiary/20 flex items-center justify-center text-tertiary">
                                <span class="material-symbols-outlined text-[28px]">thermostat</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded bg-surface-container-high text-tertiary font-code-sm text-code-sm">ASHRAE TC 9.9</span>
                                <span class="px-2.5 py-1 rounded bg-surface-container-highest text-outline font-label-sm text-label-sm uppercase">Normativa</span>
                            </div>
                        </div>

                        <h3 class="font-headline-md text-headline-md text-on-surface font-semibold mb-2">
                            Normativa Térmica ASHRAE y Climatización en Montería
                        </h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Estudio termodinámico riguroso del centro de datos en Montería (Córdoba). Explica la física del punto de rocío y por qué operar aires a 16°C es perjudicial para la circuitería electrónica, justificando el rango óptimo de 18.0°C a 24.0°C y límite crítico en 28.0°C.
                        </p>

                        <div class="space-y-2 mb-6 font-code-sm text-code-sm text-on-surface-variant bg-surface-container-high/60 p-3.5 rounded-lg border border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Cálculo de punto de rocío a 80% RH exterior en el Sinú</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Prevención de condensación y escarcha en evaporadores</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">check_circle</span>
                                <span>Umbrales recomendados: 18°C mín / 24°C máx / 28°C crítico</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-outline">Edición Caribe Colombiano</span>
                        <a href="{{ asset('manuals/Normativa_Termica_ASHRAE_TC9.9_Monteria.txt') }}" download
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md text-label-md font-medium transition-colors cursor-pointer border border-outline-variant">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Descargar Estudio Térmico</span>
                        </a>
                    </div>
                </div>

                {{-- Document 4: Especificaciones Protocolo MQTT --}}
                <div x-show="activeCategory === 'all' || activeCategory === 'mqtt'"
                     class="bg-surface-container rounded-xl p-8 border border-outline-variant/30 hover:border-primary/50 transition-all flex flex-col justify-between shadow-card">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary-container/30 flex items-center justify-center text-primary-fixed">
                                <span class="material-symbols-outlined text-[28px]">hub</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded bg-surface-container-high text-primary-fixed font-code-sm text-code-sm">MQTT v3.1.1</span>
                                <span class="px-2.5 py-1 rounded bg-surface-container-highest text-outline font-label-sm text-label-sm uppercase">JSON Schema</span>
                            </div>
                        </div>

                        <h3 class="font-headline-md text-headline-md text-on-surface font-semibold mb-2">
                            Especificaciones de Red y Payloads MQTT
                        </h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">
                            Esquema formal de datos para la ingesta de telemetría en tiempo real. Documenta el formato del payload JSON, estructura de campos, tipos numéricos de punto flotante, puertos WebSockets seguros (8884 WSS) y tópicos de suscripción en HiveMQ.
                        </p>

                        <div class="space-y-2 mb-6 font-code-sm text-code-sm text-on-surface-variant bg-surface-container-high/60 p-3.5 rounded-lg border border-outline-variant/20">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-primary">check_circle</span>
                                <span>Tópico principal: <code class="text-primary font-bold">pas_iot/datacenter/telemetria</code></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-primary">check_circle</span>
                                <span>Cadencia de transmisión: 3000 ms (Watchdog de 10s)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-primary">check_circle</span>
                                <span>Esquema JSON con campos validados para temperatura y humedad</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-outline">Formato: JSON estructurado</span>
                        <a href="{{ asset('manuals/Especificaciones_Protocolo_MQTT_AetherSens.json') }}" download
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md text-label-md font-medium transition-colors cursor-pointer border border-outline-variant">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Descargar Esquema JSON</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- HARDWARE WIRING & ARCHITECTURE VIEWER                     --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-16">
            <div class="bg-surface-container-low rounded-xl p-8 md:p-12 border border-outline-variant/30">
                <div class="max-w-3xl mb-8">
                    <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest block font-medium mb-1">Esquema Rápido de Conexión</span>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface font-semibold mb-2">Diagrama de Flujo de Datos IoT</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Representación simplificada de la cadena de adquisición de datos desde los sensores físicos hasta la visualización en el navegador.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <div class="bg-surface-container p-5 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                        <div>
                            <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm mb-3">01</span>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-1">Sensores Físicos</h4>
                            <p class="font-body-sm text-body-sm text-outline">DHT11 (Temp/Hum) y MQ-2 (Humo) colocados en la altura media y superior del bastidor.</p>
                        </div>
                        <span class="font-code-sm text-code-sm text-primary mt-4">GPIO 4 / ADC6</span>
                    </div>

                    <div class="bg-surface-container p-5 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                        <div>
                            <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center font-bold text-sm mb-3">02</span>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-1">Nodo Microcontrolador</h4>
                            <p class="font-body-sm text-body-sm text-outline">ESP32 procesa lecturas, calcula el índice de calor y construye el paquete JSON.</p>
                        </div>
                        <span class="font-code-sm text-code-sm text-secondary mt-4">Cadencia: 3s</span>
                    </div>

                    <div class="bg-surface-container p-5 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                        <div>
                            <span class="w-8 h-8 rounded-lg bg-tertiary/10 text-tertiary flex items-center justify-center font-bold text-sm mb-3">03</span>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-1">Broker MQTT HiveMQ</h4>
                            <p class="font-body-sm text-body-sm text-outline">Distribución segura y de baja latencia mediante túnel WebSockets TLS (WSS 8884).</p>
                        </div>
                        <span class="font-code-sm text-code-sm text-tertiary mt-4">QoS 0 / broker.hivemq.com</span>
                    </div>

                    <div class="bg-surface-container p-5 rounded-lg border border-outline-variant/20 flex flex-col justify-between">
                        <div>
                            <span class="w-8 h-8 rounded-lg bg-primary-container/20 text-primary-fixed flex items-center justify-center font-bold text-sm mb-3">04</span>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-1">Dashboard AetherSens</h4>
                            <p class="font-body-sm text-body-sm text-outline">Renderizado reactivo sin recarga de página con ApexCharts en tiempo real.</p>
                        </div>
                        <span class="font-code-sm text-code-sm text-primary-fixed mt-4">Recepción continua UTC-5</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- FREQUENTLY ASKED QUESTIONS (FAQ)                          --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20" x-data="{ openFaq: 1 }">
            <div class="mb-8">
                <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest block font-medium">Resolución de Dudas</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Preguntas Frecuentes y Guías Operativas</h2>
            </div>

            <div class="space-y-4">

                {{-- FAQ 1 --}}
                <div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/30">
                    <button @click="openFaq = (openFaq === 1 ? null : 1)"
                            class="w-full px-6 py-5 text-left flex items-center justify-between font-headline-sm text-headline-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span>¿Por qué la temperatura mínima recomendada es 18.0 °C si los aires acondicionados pueden bajar a 16.0 °C?</span>
                        <span class="material-symbols-outlined text-[20px] transition-transform text-outline" :class="openFaq === 1 ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-6 pb-6 text-on-surface-variant font-body-md text-body-md leading-relaxed border-t border-outline-variant/20 pt-4">
                        En el clima cálido y tropical de Montería, la humedad relativa exterior suele rondar entre el 70% y el 85%. Si el aire dentro de la sala de servidores se enfría a 16.0 °C, cualquier apertura de puerta o infiltración de aire húmedo exterior entrará en contacto con superficies frías a 16 °C, alcanzando el <strong>punto de rocío</strong> y provocando condensación de microgotas de agua sobre los circuitos impresos y zócalos de servidores. Además, operar los compresores continuamente a 16 °C en Montería genera congelamiento en los evaporadores. El estándar internacional <strong>ASHRAE TC 9.9 Clase A1</strong> establece de manera categórica que el piso seguro de entrada a los servidores es 18.0 °C.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/30">
                    <button @click="openFaq = (openFaq === 2 ? null : 2)"
                            class="w-full px-6 py-5 text-left flex items-center justify-between font-headline-sm text-headline-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span>¿Cómo se guardan y sincronizan los cambios en la página de Configuración (/settings)?</span>
                        <span class="material-symbols-outlined text-[20px] transition-transform text-outline" :class="openFaq === 2 ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-6 pb-6 text-on-surface-variant font-body-md text-body-md leading-relaxed border-t border-outline-variant/20 pt-4">
                        Al pulsar <strong>Guardar Cambios</strong> en el módulo de configuración, el sistema realiza una doble sincronización atómica: primero persiste los parámetros en el <code class="text-primary font-code-sm">localStorage</code> de su navegador para una reactividad inmediata en las tarjetas y gráficas, y en paralelo envía una solicitud HTTP POST protegida por token CSRF hacia la ruta de almacenamiento de Laravel, guardando el archivo de configuración <code class="text-primary font-code-sm">storage/app/settings.json</code>. De este modo, los umbrales permanecen guardados incluso si recarga la página o inicia sesión desde otro dispositivo.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/30">
                    <button @click="openFaq = (openFaq === 3 ? null : 3)"
                            class="w-full px-6 py-5 text-left flex items-center justify-between font-headline-sm text-headline-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span>¿De dónde provienen los datos que se muestran en el panel antes de encender el ESP32?</span>
                        <span class="material-symbols-outlined text-[20px] transition-transform text-outline" :class="openFaq === 3 ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-6 pb-6 text-on-surface-variant font-body-md text-body-md leading-relaxed border-t border-outline-variant/20 pt-4">
                        Para evitar que el panel muestre ceros o datos artificiales irreales, el sistema incorpora un servicio de línea base atmosférica que solicita geolocalización o consulta las coordenadas de <strong>Montería, Córdoba</strong> mediante modelos numéricos de Open-Meteo. En cuanto el microcontrolador ESP32 comienza a transmitir paquetes MQTT con datos del DHT11, la telemetría del sensor toma el control exclusivo del panel de control de forma inmediata.
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-surface-container rounded-xl overflow-hidden border border-outline-variant/30">
                    <button @click="openFaq = (openFaq === 4 ? null : 4)"
                            class="w-full px-6 py-5 text-left flex items-center justify-between font-headline-sm text-headline-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span>¿Qué hacer si el panel muestra "ESP32 Sin señal reciente (>10s)"?</span>
                        <span class="material-symbols-outlined text-[20px] transition-transform text-outline" :class="openFaq === 4 ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-6 pb-6 text-on-surface-variant font-body-md text-body-md leading-relaxed border-t border-outline-variant/20 pt-4">
                        El sistema incluye un perro guardián (Watchdog) calibrado por defecto en 10 segundos. Si aparece esta alerta:
                        1. Verifique que el LED de encendido del ESP32 esté encendido.
                        2. Compruebe que la red WiFi del rack no esté caída o saturada.
                        3. Abra el monitor serie del ESP32 a 115200 baudios para confirmar si está conectado al broker <code class="text-primary font-code-sm">broker.hivemq.com</code> y publicando en el tópico configurado.
                    </div>
                </div>

            </div>
        </section>

    </main>

    {{-- ======================================================== --}}
    {{-- FOOTER                                                    --}}
    {{-- ======================================================== --}}
    <footer class="w-full bg-surface-container-lowest py-space-lg border-t border-outline-variant/30">
        <div class="max-w-[1600px] mx-auto px-margin-md md:px-margin-lg flex flex-col sm:flex-row items-center justify-between gap-space-xs text-center sm:text-left">
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                AetherSens Enterprise &bull; Manuales y Soporte Técnico del Centro de Cómputo &bull; Versión 2.4
            </p>
            <div class="flex items-center gap-space-md font-body-sm text-body-sm text-on-surface-variant">
                <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Panel de Control</a>
                <span>&bull;</span>
                <a href="{{ route('settings') }}" class="hover:text-primary transition-colors">Configuración</a>
            </div>
        </div>
    </footer>

</body>
</html>

