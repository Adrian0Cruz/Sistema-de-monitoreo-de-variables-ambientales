<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Intalnet SCADA') }} - Security Gateway</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary-container selection:text-on-primary-container min-h-screen relative overflow-x-hidden flex flex-col justify-between">
    <!-- Ambient Grid Background -->
    <div class="fixed inset-0 pointer-events-none bg-[radial-gradient(#191f31_1px,transparent_1px)] [background-size:16px_16px] opacity-25 z-0"></div>

    <!-- Header Gateway -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
        <div class="h-12 w-full px-margin flex items-center justify-between">
            <a class="flex items-center gap-space-sm" href="{{ url('/') }}">
                <div class="w-6 h-6 bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[16px]">terminal</span>
                </div>
                <span class="font-headline-sm text-headline-sm text-on-surface">INTALNET<span class="text-primary ml-1">SECURITY GATEWAY</span></span>
            </a>
            <div class="flex items-center gap-space-sm">
                <span class="font-code-xs text-code-xs text-outline">MODO SEGURO</span>
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                <span class="font-code-xs text-code-xs text-on-surface-variant font-medium">TLS 1.3 / FIPS-140-3</span>
            </div>
        </div>
    </header>

    <!-- Slot for Form Content -->
    <main class="relative z-10 w-full min-h-screen pt-12 flex flex-col justify-between">
        {{ $slot }}

        <!-- Footer Gateway -->
        <div class="w-full py-space-sm px-margin bg-surface-container-lowest/80 backdrop-blur-sm flex flex-wrap items-center justify-between gap-space-sm shadow-[0_-1px_6px_rgba(0,0,0,0.3)] border-t border-outline-variant/30">
            <div class="flex items-center gap-space-md">
                <span class="font-code-xs text-code-xs text-outline">NORMATIVA DE ACCESO: OPERADORES SIL-2/SIL-3</span>
                <span class="font-code-xs text-code-xs text-outline-variant">|</span>
                <span class="font-code-xs text-code-xs text-on-surface-variant">NODO AUTH: 10.240.4.12:8443</span>
            </div>
            <div class="font-code-xs text-code-xs text-outline">INTALNET TELEMETRY OS • AUTENTICACIÓN CRIPTOGRÁFICA INDUSTRIAL</div>
        </div>
    </main>

    @livewireScripts
</body>
</html>
