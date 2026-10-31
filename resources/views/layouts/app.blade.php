<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} - {{ $title ?? 'Panel de Control' }}</title>
    <meta name="description" content="AetherSens – Monitoreo ambiental en tiempo real para salas de servidores y racks de telecomunicaciones.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Geist via Fontshare --}}
    <link href="https://api.fontshare.com/v2/css?f[]=geist@1,2,3,4,5,6,7&display=swap" rel="stylesheet">
    {{-- JetBrains Mono for code/telemetry values --}}
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Telemetry Libraries: ApexCharts & MQTT.js --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary-container/30 selection:text-on-surface min-h-screen">

    {{-- ===================== FIXED LEFT SIDEBAR ===================== --}}
    <aside class="w-64 fixed top-0 bottom-0 left-0 bg-surface-container-low border-r border-outline-variant z-50 flex flex-col justify-between select-none">

        <div class="flex flex-col overflow-hidden">

            {{-- Brand Header --}}
            <div class="h-16 px-space-md border-b border-outline-variant flex items-center justify-between shrink-0">
                <a class="flex items-center gap-space-sm" href="{{ route('dashboard') }}">
                    <div class="w-7 h-7 rounded-lg bg-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-primary-container text-[18px]">dns</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight">AetherSens</span>
                </a>
                <span class="px-space-xs py-0.5 rounded text-[10px] font-code-sm uppercase bg-surface-container-highest text-on-surface-variant">v2.4</span>
            </div>

            {{-- Nav Section Label --}}
            <div class="px-space-md pt-space-lg pb-space-xs">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Operational Menu</span>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex flex-col px-space-sm gap-space-xs">

                {{-- Dashboard (active route) --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('dashboard')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    <span>Dashboard</span>
                </a>

                {{-- Historical Data --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('historical-data')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('historical-data') }}">
                    <span class="material-symbols-outlined text-[18px]">show_chart</span>
                    <span>Historical Data</span>
                </a>

                {{-- Alert Logs --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('alert-logs')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('alert-logs') }}">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <span>Alert Logs</span>
                </a>

                {{-- Settings --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('settings')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('settings') }}">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    <span>Settings</span>
                </a>

            </nav>
        </div>

        {{-- Telemetry Core Diagnostics (bottom) --}}
        <div class="p-space-md border-t border-outline-variant bg-surface-container-lowest/60 shrink-0">
            <div class="flex items-center justify-between mb-space-xs">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Telemetry Core</span>
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary"></span>
                </span>
            </div>
            <div class="space-y-1 font-code-sm text-code-sm text-on-surface-variant">
                <div class="flex justify-between">
                    <span class="text-outline">Firmware:</span>
                    <span>v2.4.1-prod</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Broker:</span>
                    <span class="text-tertiary">Node MQTT [Active]</span>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===================== MAIN RIGHT PANEL ===================== --}}
    <div class="pl-64 min-h-screen flex flex-col">

        {{-- Fixed Top Header --}}
        <header class="fixed top-0 left-64 right-0 h-16 bg-surface-container-low/90 backdrop-blur-md border-b border-outline-variant z-40 flex items-center justify-between px-space-lg">

            {{-- Left: Breadcrumb + Zone Selector --}}
            <div class="flex items-center gap-space-lg">
                <div class="flex items-center gap-space-sm">
                    <span class="font-headline-sm text-headline-sm text-on-surface">AetherSens</span>
                    <span class="text-outline">/</span>
                    <span class="font-body-md text-body-md text-on-surface-variant">Server Room Monitor</span>
                </div>

                {{-- Zone Selector Chip --}}
                <div class="relative flex items-center bg-surface-container-high border border-outline-variant rounded-lg px-space-md py-1.5 cursor-pointer hover:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[16px] text-outline mr-1.5">location_on</span>
                    <span class="font-label-md text-label-md text-on-surface pr-space-md">Zone A</span>
                    <span class="material-symbols-outlined text-[16px] text-outline">expand_more</span>
                </div>
            </div>

            {{-- Right: ESP32 Status + User --}}
            <div class="flex items-center gap-space-md">

                {{-- ESP32 Connection Pill --}}
                <div class="flex items-center gap-2 bg-surface-container px-space-md py-1.5 rounded-full border border-tertiary/30">
                    <div class="w-2 h-2 rounded-full bg-tertiary"></div>
                    <span class="font-code-sm text-code-sm text-tertiary">ESP32: Connected (MQTT / Livewire)</span>
                </div>

                <div class="h-4 w-px bg-outline-variant"></div>

                {{-- Notification Bell --}}
                <button aria-label="Notificaciones" class="p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors relative">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-primary rounded-full"></span>
                </button>

                {{-- User Dropdown (Alpine.js) --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-space-sm pl-space-xs focus:outline-none"
                        type="button">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_url)
                                <img class="h-8 w-8 rounded-full object-cover"
                                     src="{{ Auth::user()->profile_photo_url }}"
                                     alt="{{ Auth::user()->name }}" />
                            @else
                                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                            @endif
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="font-label-md text-label-md text-on-surface leading-none">{{ Auth::user()->name ?? 'Usuario' }}</span>
                            <span class="font-label-sm text-label-sm text-outline leading-tight mt-0.5">Admin</span>
                        </div>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 rounded-lg bg-surface-container-high border border-outline-variant shadow-card-lg py-1 z-50"
                        style="display: none;">

                        <div class="px-space-md py-space-sm border-b border-outline-variant">
                            <span class="font-code-sm text-code-sm text-outline block">Conectado como</span>
                            <span class="font-body-md text-body-md text-on-surface truncate block">{{ Auth::user()->email ?? '' }}</span>
                        </div>

                        @if (Route::has('profile.show'))
                            <a href="{{ route('profile.show') }}"
                               class="flex items-center gap-space-sm px-space-md py-space-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors font-body-md text-body-md">
                                <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                                <span>Perfil</span>
                            </a>
                        @endif

                        <div class="border-t border-outline-variant my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-space-sm px-space-md py-space-sm text-error hover:bg-error-container/20 transition-colors font-body-md text-body-md">
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- Dynamic Page Content --}}
        <main class="relative pt-16 bg-surface min-h-screen px-space-lg py-space-lg">
            {{ $slot }}
        </main>

    </div>

    @stack('modals')
    @livewireScripts
    @stack('scripts')

</body>
</html>
