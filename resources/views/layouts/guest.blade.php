<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} – {{ $title ?? 'Acceso' }}</title>
    <meta name="description" content="AetherSens – Sistema de monitoreo ambiental para salas de servidores.">

    {{-- Favicon para todas las vistas --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Geist via Fontshare --}}
    <link href="https://api.fontshare.com/v2/css?f[]=geist@1,2,3,4,5,6,7&display=swap" rel="stylesheet">
    {{-- JetBrains Mono --}}
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface font-body-md text-body-md text-on-surface antialiased min-h-screen flex flex-col">

    {{-- ===================== TOP HEADER ===================== --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-surface-container-low/90 backdrop-blur-md border-b border-outline-variant">
        <div class="h-14 w-full max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between">

            {{-- Brand --}}
            <a class="flex items-center gap-space-sm" href="{{ url('/') }}">
                <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[18px]">sensors</span>
                </div>
                <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight">AetherSens</span>
            </a>

            {{-- Node status pill --}}
            <div class="flex items-center gap-space-xs text-on-surface-variant font-code-sm text-code-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary shrink-0"></span>
                <span class="tracking-wide uppercase hidden sm:inline">NODO 01 NOMINAL</span>
            </div>

            {{-- User icon (decorative) --}}
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center text-on-surface-variant shrink-0">
                <span class="material-symbols-outlined text-[18px]">person</span>
            </div>

        </div>
    </header>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="flex-1 flex flex-col justify-center items-center pt-14 pb-space-xl px-4 sm:px-6">
        {{ $slot }}
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="w-full bg-surface-container-lowest py-space-md shadow-[0_-1px_8px_rgba(0,0,0,0.1)]">
        <div class="max-w-7xl mx-auto px-margin-md text-center text-on-surface-variant font-body-sm text-body-sm tracking-wide">
            AetherSens Operaciones Internas &bull; Sistema de Telemetría
        </div>
    </footer>

    @livewireScripts

</body>
</html>
