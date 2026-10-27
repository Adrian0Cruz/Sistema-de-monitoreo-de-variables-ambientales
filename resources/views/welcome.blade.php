<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} – Monitoreo Ambiental de Servidores</title>
    <meta name="description" content="Plataforma interna de control en tiempo real para temperatura, humedad y seguridad física de infraestructura crítica de telecomunicaciones y racks perimetrales.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=geist@1,2,3,4,5,6,7&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col">

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

            {{-- Center links (hidden on mobile) --}}
            <div class="hidden md:flex items-center gap-space-xl text-on-surface-variant font-body-md text-body-md">
                <a href="#" class="hover:text-on-surface transition-colors">Plataforma</a>
                <a href="#" class="hover:text-on-surface transition-colors">Zonas & Racks</a>
                <a href="#" class="hover:text-on-surface transition-colors">Telemetría Activa</a>
                <a href="#" class="hover:text-on-surface transition-colors">Ayuda / Docs</a>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-space-md">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-1.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-md text-label-md transition-colors">
                            Panel de Control
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

    <main class="flex-1 pt-14">

        {{-- ======================================================== --}}
        {{-- STATUS STRIP                                               --}}
        {{-- ======================================================== --}}
        <section class="w-full bg-surface-container-low/70 py-space-sm px-margin-md md:px-margin-lg">
            <div class="max-w-[1600px] mx-auto flex flex-wrap items-center justify-between gap-space-sm">
                <div class="flex items-center gap-space-md">
                    <span class="inline-flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant">
                        <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                        INFRAESTRUCTURA OPERATIVA
                    </span>
                    <span class="text-outline-variant font-body-sm text-body-sm hidden sm:inline">&bull;</span>
                    <span class="font-code-sm text-code-sm text-on-surface-variant">HOST: srv-mon-edge01.intranet.corp</span>
                </div>
                <div class="flex items-center gap-space-lg text-on-surface-variant font-code-sm text-code-sm">
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Temp Media</span>
                        <span class="text-on-surface font-medium">21.4°C</span>
                        <span class="text-tertiary font-label-sm text-label-sm">±0.2</span>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Humedad</span>
                        <span class="text-on-surface font-medium">47.8% RH</span>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="text-outline font-label-sm text-label-sm uppercase tracking-wider">Nodos</span>
                        <span class="text-secondary font-medium">16 / 16 Activos</span>
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

                {{-- Internal Portal Pill --}}
                <div class="inline-flex items-center gap-space-xs px-space-md py-1 rounded-full bg-surface-container-high shadow-sm mb-space-lg">
                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                    <span class="font-label-md text-label-md text-on-surface font-medium">Portal Interno</span>
                    <span class="text-outline font-label-sm text-label-sm">&bull;</span>
                    <span class="font-label-md text-label-md text-on-surface-variant">Red Privada TI</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="font-display-lg text-display-lg md:text-[44px] md:leading-[52px] text-on-surface tracking-tight font-semibold mb-space-md max-w-3xl">
                    Monitoreo Ambiental de Servidores
                </h1>

                {{-- Subtitle --}}
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto mb-space-xl">
                    Plataforma interna de control en tiempo real para temperatura, humedad y seguridad
                    física de infraestructura crítica de telecomunicaciones y racks perimetrales.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row items-center gap-space-md mb-space-lg">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center gap-space-sm px-8 py-3.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-headline-sm text-headline-sm transition-all shadow-card group">
                            <span>Acceder al Panel de Control</span>
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-space-sm px-8 py-3.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-headline-sm text-headline-sm transition-all shadow-card group">
                            <span>Acceder al Panel de Control</span>
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </a>
                    @endauth

                    <a href="#"
                       class="inline-flex items-center justify-center gap-space-xs px-6 py-3.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors">
                        <span class="material-symbols-outlined text-outline text-[18px]">equalizer</span>
                        <span>Inspeccionar Nodos</span>
                    </a>
                </div>

                {{-- Quick Status Footnote --}}
                <div class="flex items-center gap-space-xs text-on-surface-variant font-code-sm text-code-sm">
                    <span class="material-symbols-outlined text-secondary text-[16px]">sensors</span>
                    <span>Nodo central ESP32 sincronizado</span>
                    <span class="text-outline font-body-sm text-body-sm">&bull;</span>
                    <span class="material-symbols-outlined text-tertiary text-[16px]">lock</span>
                    <span>Red corporativa segura (VLAN 402 - TLSv1.3)</span>
                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- ASHRAE BASELINES PANEL                                    --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-16">
            <div class="bg-surface-container rounded-xl p-space-lg shadow-sm">

                {{-- Panel Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-space-md mb-space-lg gap-space-sm bg-surface-container">
                    <div>
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-primary text-[20px]">monitor_heart</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface">Línea Base Operativa &amp; Umbrales ASHRAE</h2>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Rangos recomendados de sala fría y contención térmica</p>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="px-space-sm py-0.5 rounded bg-tertiary-container/30 text-tertiary font-label-sm text-label-sm font-medium">NORMAL: 16 NODOS OK</span>
                        <span class="text-outline-variant font-body-sm text-body-sm">&bull;</span>
                        <span class="font-code-sm text-code-sm text-on-surface-variant">LATENCIA: 12ms</span>
                    </div>
                </div>

                {{-- 3-column metrics --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">

                    {{-- Temperatura --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Temperatura Estándar</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">18°C – 24°C</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">thermostat</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Rango Seguro</span>
                                <span class="text-tertiary font-medium">Actual: 21.4°C</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-surface-container-lowest overflow-hidden flex">
                                <div class="h-full bg-outline-variant" style="width: 25%"></div>
                                <div class="h-full bg-tertiary" style="width: 50%"></div>
                                <div class="h-full bg-error" style="width: 25%"></div>
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>15°C</span><span>18°C</span><span>24°C</span><span>32°C</span>
                            </div>
                        </div>
                    </div>

                    {{-- Humedad --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Humedad Relativa Óptima</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">40% – 55% RH</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-[18px]">humidity_mid</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Condensación / ESD Segura</span>
                                <span class="text-tertiary font-medium">Actual: 47%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-surface-container-lowest overflow-hidden flex">
                                <div class="h-full bg-outline-variant" style="width: 20%"></div>
                                <div class="h-full bg-tertiary" style="width: 50%"></div>
                                <div class="h-full bg-error" style="width: 30%"></div>
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>20%</span><span>40%</span><span>55%</span><span>80%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Sensores Activos --}}
                    <div class="bg-surface-container-high rounded-lg p-space-md flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Sensores Activos en Malla</span>
                                <span class="font-telemetry-metric text-telemetry-metric text-on-surface font-medium">16 Nodos</span>
                            </div>
                            <span class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-tertiary">
                                <span class="material-symbols-outlined text-[18px]">hub</span>
                            </span>
                        </div>
                        <div class="mt-space-md">
                            <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
                                <span>Cobertura de Racks (Zona A, B, C)</span>
                                <span class="text-tertiary font-medium">100% Online</span>
                            </div>
                            <div class="flex gap-0.5 h-6">
                                @for($i = 0; $i < 14; $i++)
                                    <div class="flex-1 bg-tertiary rounded-t {{ rand(0,1) ? '' : 'opacity-80' }}"></div>
                                @endfor
                            </div>
                            <div class="flex justify-between font-code-sm text-[10px] text-outline mt-1">
                                <span>RACK-01</span><span>RACK-08</span><span>RACK-16</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- FEATURE CARDS: System Capabilities                        --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20">
            <div class="mb-space-lg text-left">
                <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest block font-medium">Arquitectura de Operación</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Capacidades del Sistema Central</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">

                {{-- Tiempo Real --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-primary mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">cycle</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Tiempo Real</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Sincronización continua de variables térmicas vía IoT con protocolo MQTT de ultra-baja latencia
                            y muestreo continuo por segundo en cada bastidor.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-secondary font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            Telemetría activa 24/7
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">QoS-1 MQTT</span>
                    </div>
                </div>

                {{-- Gestión de Alertas --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">notifications_active</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Gestión de Alertas</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Auditoría y registro automatizado de incidencias. Disparadores proactivos para puntos calientes
                            con escalamiento directo a guardias de operaciones.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-tertiary font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                            Umbrales preventivos
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">SLO &lt; 30s</span>
                    </div>
                </div>

                {{-- Seguridad Interna --}}
                <div class="bg-surface-container rounded-xl p-8 shadow-sm flex flex-col justify-between hover:bg-surface-container-high transition-colors">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container mb-space-lg">
                            <span class="material-symbols-outlined text-[26px]">shield_person</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-space-xs font-semibold">Seguridad Interna</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-space-lg">
                            Acceso restringido exclusivamente para personal autorizado mediante integración SAML/SSO
                            corporativo y validación biométrica de perímetro.
                        </p>
                    </div>
                    <div class="pt-space-md flex items-center justify-between">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-1 rounded bg-surface-container-highest text-primary-fixed font-label-sm text-label-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            Autenticación corporativa
                        </span>
                        <span class="font-code-sm text-code-sm text-outline">RBAC Nivel 3</span>
                    </div>
                </div>

            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- THERMAL CONTAINMENT SECTION                               --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg mb-20">
            <div class="bg-surface-container-low rounded-xl p-space-lg md:p-12 flex flex-col lg:flex-row items-center justify-between gap-space-xl">
                <div class="text-left max-w-4xl">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest block font-medium mb-space-xs">Infraestructura Física</span>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface font-semibold mb-space-md">Contención Térmica de Pasillo Frío</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-space-lg leading-relaxed">
                        Los sensores perimetrales AetherSens se ubican verticalmente en las unidades 4U, 22U y 42U de cada gabinete,
                        garantizando que el flujo laminar de aire acondicionado por precisión (CRAC) mantenga las especificaciones
                        de los fabricantes de hardware crítico.
                    </p>
                    <div class="grid grid-cols-2 gap-space-md font-code-sm text-code-sm">
                        <div class="bg-surface-container p-space-sm rounded">
                            <span class="text-outline block font-label-sm text-label-sm">DIFERENCIAL TÉRMICO (ΔT)</span>
                            <span class="text-on-surface font-medium text-body-lg">8.2°C nominal</span>
                        </div>
                        <div class="bg-surface-container p-space-sm rounded">
                            <span class="text-outline block font-label-sm text-label-sm">PUE ESTIMADO SALA</span>
                            <span class="text-tertiary font-medium text-body-lg">1.24 Eficiente</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================================================== --}}
        {{-- CONFIDENTIALITY NOTICE                                    --}}
        {{-- ======================================================== --}}
        <section class="w-full max-w-[1600px] mx-auto px-margin-md md:px-margin-lg pb-16">
            <div class="bg-surface-container-lowest rounded-lg p-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm text-center sm:text-left">
                <div class="flex items-center gap-space-sm">
                    <span class="material-symbols-outlined text-outline text-[20px]">verified_user</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        <strong class="text-on-surface font-medium">Aviso de Confidencialidad:</strong>
                        Acceso monitoreado y restringido a la red interna VPN / Intranet corporativa.
                        Todas las consultas y sesiones son auditadas conforme a la norma ISO/IEC 27001.
                    </p>
                </div>
                <div class="shrink-0 font-code-sm text-code-sm text-outline">
                    TI-SEC-ID: #8942-DCO
                </div>
            </div>
        </section>

    </main>

    {{-- ======================================================== --}}
    {{-- FOOTER                                                    --}}
    {{-- ======================================================== --}}
    <footer class="w-full bg-surface-container-lowest py-space-lg">
        <div class="max-w-[1600px] mx-auto px-margin-md md:px-margin-lg flex flex-col sm:flex-row items-center justify-between gap-space-xs text-center sm:text-left">
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Uso exclusivo interno – Empresa / Departamento de TI. Versión 2.4
            </p>
            <div class="flex items-center gap-space-md font-body-sm text-body-sm text-on-surface-variant">
                <span class="flex items-center gap-space-xs">
                    <span class="w-2 h-2 rounded-full bg-tertiary inline-block"></span>
                    Servicios Operativos
                </span>
            </div>
        </div>
    </footer>

</body>
</html>
