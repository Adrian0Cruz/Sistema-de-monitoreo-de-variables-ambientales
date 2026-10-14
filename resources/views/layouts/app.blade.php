<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Intalnet SCADA') }} - Control Deck</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary-container selection:text-on-primary-container min-h-screen">
    <!-- FIXED LEFT SIDEBAR: SCADA NAVIGATION & DIAGNOSTICS DECK -->
    <aside class="w-72 fixed top-0 bottom-0 left-0 bg-surface-container-lowest border-r border-outline-variant/40 flex flex-col justify-between z-50 select-none">
        <div class="flex flex-col">
            <!-- Sidebar Brand Header -->
            <div class="h-14 px-space-md border-b border-outline-variant/40 flex items-center justify-between bg-surface-container-low">
                <a class="flex items-center gap-space-xs" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-primary text-[20px]">developer_board</span>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-headline-sm uppercase tracking-wider text-on-surface leading-none">INTALNET</span>
                        <span class="font-code-xs text-code-xs text-primary uppercase tracking-widest leading-none">TELEMETRY OS</span>
                    </div>
                </a>
                <div class="flex items-center gap-space-xs px-space-xs py-0.5 rounded bg-surface-container border border-outline-variant/40">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                    <span class="font-code-xs text-code-xs text-secondary uppercase">NODE_01</span>
                </div>
            </div>

            <!-- Sub-Header Status Chip -->
            <div class="px-space-md py-space-xs border-b border-outline-variant/30 bg-surface-container-low/50 flex items-center justify-between">
                <span class="font-label-caps text-label-caps text-outline uppercase">SALA TÉRMICA SCADA</span>
                <span class="font-code-xs text-code-xs text-secondary font-mono">CAN: ONLINE</span>
            </div>

            <!-- Technical Navigation Links -->
            <nav class="flex flex-col py-space-xs">
                <a class="flex items-center gap-space-md px-space-md py-space-xs transition-colors {{ request()->routeIs('dashboard') ? 'bg-surface-container-high text-primary border-l-2 border-primary font-body-lg font-semibold' : 'text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface border-l-2 border-transparent' }}"
                    href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-[18px]">grid_view</span>
                    <span>Vista General</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#sala-a">
                    <span class="material-symbols-outlined text-[18px]">ac_unit</span>
                    <span>Sala Térmica A</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#sala-b">
                    <span class="material-symbols-outlined text-[18px]">hvac</span>
                    <span>Sala Térmica B</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#calibracion">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    <span>Calibración Sensores</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#esp32">
                    <span class="material-symbols-outlined text-[18px]">memory</span>
                    <span>Dispositivos ESP32</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#alertas">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <span>Registros &amp; Alertas</span>
                </a>

                <a class="flex items-center gap-space-md px-space-md py-space-xs text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface transition-colors border-l-2 border-transparent"
                    href="#can-bus">
                    <span class="material-symbols-outlined text-[18px]">lan</span>
                    <span>Diagnóstico CAN-Bus</span>
                </a>

                @if (Route::has('profile.show'))
                    <a class="flex items-center gap-space-md px-space-md py-space-xs transition-colors {{ request()->routeIs('profile.show') ? 'bg-surface-container-high text-primary border-l-2 border-primary font-body-lg font-semibold' : 'text-on-surface-variant font-body-md hover:bg-surface-container hover:text-on-surface border-l-2 border-transparent' }}"
                        href="{{ route('profile.show') }}">
                        <span class="material-symbols-outlined text-[18px]">settings</span>
                        <span>Configuración de Usuario</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- System Diagnostics Bottom Bay -->
        <div class="p-space-md border-t border-outline-variant/40 bg-surface-container-low flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
                <span class="font-label-caps text-label-caps text-outline uppercase">DIAGNÓSTICO NODO</span>
                <span class="font-code-xs text-code-xs text-secondary font-mono">ACTIVO</span>
            </div>
            <div class="flex flex-col gap-1 p-space-xs rounded bg-surface-container-lowest border border-outline-variant/30 font-code-xs text-code-xs font-mono">
                <div class="flex items-center justify-between text-on-surface-variant">
                    <span>FW VER</span>
                    <span class="text-primary font-semibold">v2.4.12-rc3</span>
                </div>
                <div class="flex items-center justify-between text-on-surface-variant">
                    <span>I²C / CAN</span>
                    <span class="text-secondary font-semibold">250kbps [OK]</span>
                </div>
                <div class="flex items-center justify-between text-on-surface-variant">
                    <span>RAM DMA</span>
                    <span class="text-tertiary font-semibold">82% [1.3GB]</span>
                </div>
            </div>
            <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                <div class="bg-tertiary h-full w-[82%]"></div>
            </div>
        </div>
    </aside>

    <!-- MAIN RIGHT VIEWPORT -->
    <div class="pl-72 min-h-screen flex flex-col">
        <!-- FIXED TOP TELEMETRY RIBBON & USER PROFILE HEADER -->
        <header class="fixed top-0 left-72 right-0 h-14 bg-surface-container-low/95 backdrop-blur-md border-b border-outline-variant/40 z-40 flex items-center justify-between px-space-lg">
            <!-- Left Live Status Telemetry Items -->
            <div class="flex items-center gap-space-lg overflow-x-auto py-1">
                <div class="flex items-center gap-space-xs">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="font-label-caps text-label-caps text-on-surface uppercase">GATEWAY ESP32:</span>
                    <span class="font-code-xs text-code-xs text-secondary font-bold font-mono">ONLINE</span>
                </div>
                <div class="h-3 w-px bg-outline-variant/40"></div>
                <div class="flex items-center gap-space-xs">
                    <span class="font-label-caps text-label-caps text-outline uppercase">IP:</span>
                    <span class="font-code-xs text-code-xs text-on-surface font-medium font-mono">192.168.10.45:8080</span>
                </div>
                <div class="h-3 w-px bg-outline-variant/40"></div>
                <div class="flex items-center gap-space-xs">
                    <span class="font-label-caps text-label-caps text-outline uppercase">LATENCIA:</span>
                    <span class="font-code-xs text-code-xs text-primary font-bold font-mono">14ms</span>
                </div>
                <div class="h-3 w-px bg-outline-variant/40"></div>
                <div class="flex items-center gap-space-xs">
                    <span class="font-label-caps text-label-caps text-outline uppercase">MODO:</span>
                    <span class="font-code-xs text-code-xs text-on-tertiary-fixed bg-tertiary-fixed px-1 rounded uppercase font-bold">ALTA PRECISIÓN</span>
                </div>
                <div class="h-3 w-px bg-outline-variant/40"></div>
                <div class="flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-outline text-[14px]">schedule</span>
                    <span class="font-code-xs text-code-xs text-outline font-mono">NTP UTC-5 [SYNC]</span>
                </div>
            </div>

            <!-- Right Authenticated Operator & Jetstream Actions Dropdown -->
            <div class="flex items-center gap-space-md shrink-0" x-data="{ open: false }">
                <div class="flex flex-col items-end">
                    <span class="font-code-xs text-code-xs text-on-surface font-semibold uppercase tracking-wider">
                        {{ Auth::user()->name ?? 'OPERADOR SCADA' }}
                    </span>
                    <span class="font-label-caps text-label-caps text-primary font-mono">
                        [{{ Auth::user()->access_level_label ?? 'NIVEL 2 TÉCNICO' }}]
                    </span>
                </div>

                <!-- User Dropdown Trigger -->
                <div class="relative">
                    <button @click="open = !open" class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary hover:bg-primary-fixed-dim transition-colors shadow-sm focus:outline-none" type="button">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_url)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        @else
                            <span class="material-symbols-outlined text-[18px]">person</span>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded bg-surface-container-high border border-outline-variant/60 shadow-2xl py-1 z-50 select-none"
                        style="display: none;">
                        <div class="px-space-md py-space-xs border-b border-outline-variant/40">
                            <span class="font-code-xs text-code-xs text-outline block">OPERADOR CONECTADO</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface truncate block">{{ Auth::user()->email ?? '' }}</span>
                        </div>

                        @if (Route::has('profile.show'))
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-space-sm px-space-md py-space-xs text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors font-body-md text-body-md">
                                <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                                <span>Perfil de Seguridad</span>
                            </a>
                        @endif

                        <div class="border-t border-outline-variant/40 my-1"></div>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-space-sm px-space-md py-space-xs text-error hover:bg-error-container/20 transition-colors font-headline-sm text-headline-sm uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                <span>Cerrar Sesión SCADA</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Slot -->
        <main class="w-full pt-14 px-space-lg pb-space-lg bg-surface flex-1">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>
