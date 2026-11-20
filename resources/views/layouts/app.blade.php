<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AetherSens') }} - {{ $title ?? 'Panel de Control' }}</title>
    <meta name="description" content="AetherSens – Monitoreo ambiental en tiempo real para salas de servidores y racks de telecomunicaciones.">

    {{-- Favicon para todas las vistas --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

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
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-primary-container/30 selection:text-on-surface min-h-screen"
      x-data="{ mobileMenuOpen: false }">

    {{-- Backdrop Overlay para dispositivos móviles y tablets (< 1024px) --}}
    <div x-show="mobileMenuOpen"
         x-cloak
         @click="mobileMenuOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden">
    </div>

    {{-- ===================== SIDEBAR (DRAWER EN MÓVIL / FIJO EN DESKTOP) ===================== --}}
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="w-64 fixed top-0 bottom-0 left-0 bg-surface-container-low border-r border-outline-variant z-50 flex flex-col justify-between select-none transition-transform duration-200 ease-in-out">

        <div class="flex flex-col overflow-y-auto scrollbar-none">

            {{-- Brand Header + Botón Cerrar en Móvil --}}
            <div class="h-16 px-space-md border-b border-outline-variant flex items-center justify-between shrink-0">
                <a class="flex items-center gap-space-sm" href="{{ route('dashboard') }}" @click="mobileMenuOpen = false">
                    <div class="w-7 h-7 rounded-lg bg-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-primary-container text-[18px]">dns</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight">AetherSens</span>
                </a>
                <div class="flex items-center gap-1.5">
                    <span class="px-space-xs py-0.5 rounded text-[10px] font-code-sm uppercase bg-surface-container-highest text-on-surface-variant">v2.4</span>
                    <button type="button"
                            @click="mobileMenuOpen = false"
                            class="lg:hidden p-1 rounded-md text-outline hover:text-on-surface hover:bg-surface-container-high transition-colors ml-1"
                            aria-label="Cerrar menú">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
            </div>

            {{-- Nav Section Label --}}
            <div class="px-space-md pt-space-lg pb-space-xs">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Menú Operacional</span>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex flex-col px-space-sm gap-space-xs">

                {{-- Dashboard (active route) --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('dashboard')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('dashboard') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    <span>Panel de Control</span>
                </a>

                {{-- Historical Data --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('historical-data')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('historical-data') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">show_chart</span>
                    <span>Historial de Datos</span>
                </a>

                {{-- Alert Logs --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('alert-logs')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('alert-logs') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <span>Registro de Alertas</span>
                </a>

                {{-- Settings --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('settings')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('settings') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    <span>Configuración</span>
                </a>

                {{-- Registrar Operador (Solo usuarios autenticados) --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('register*')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('register') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Registrar Operador</span>
                </a>

                {{-- Help & Docs --}}
                <a class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors font-body-md text-body-md
                    {{ request()->routeIs('docs')
                        ? 'bg-surface-container-high text-primary font-medium'
                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}"
                    href="{{ route('docs') }}"
                    @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                    <span>Ayuda y Manuales</span>
                </a>

            </nav>
        </div>

        {{-- ============================================================ --}}
        {{-- SECCIÓN INFERIOR CONSOLIDADA (TELEMETRÍA + RELOJ AL FINAL)   --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col shrink-0 mt-auto">

            {{-- Telemetry Core Diagnostics --}}
            <div class="p-space-md border-t border-outline-variant bg-surface-container-lowest/60">
                <div class="flex items-center justify-between mb-space-xs">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Núcleo de Telemetría</span>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary"></span>
                    </span>
                </div>
                <div class="space-y-1 font-code-sm text-code-sm text-on-surface-variant">
                    <div class="flex justify-between">
                        <span class="text-outline">Firmware:</span>
                        <span>ESP32 / SHT40 + MQ-2</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-outline">Broker:</span>
                        <span class="text-tertiary">MQTT por WebSocket</span>
                    </div>
                </div>
            </div>

            {{-- Reloj en Tiempo Real y Fecha Actual (Montería UTC-5) --}}
            <div x-data="{
                hora: '--:--:--',
                fecha: '---, -- --- ----',
                actualizar() {
                    const ahora = new Date();
                    try {
                        this.hora = new Intl.DateTimeFormat('es-CO', {
                            timeZone: 'America/Bogota',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        }).format(ahora);
                        this.fecha = new Intl.DateTimeFormat('es-CO', {
                            timeZone: 'America/Bogota',
                            weekday: 'short',
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        }).format(ahora);
                    } catch (e) {
                        this.hora = ahora.toTimeString().slice(0, 8);
                        this.fecha = ahora.toISOString().slice(0, 10);
                    }
                },
                init() {
                    this.actualizar();
                    setInterval(() => this.actualizar(), 1000);
                }
            }" class="px-space-md py-3 border-t border-outline-variant bg-surface-container-lowest">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-1.5 text-primary">
                        <span class="material-symbols-outlined text-[15px]">schedule</span>
                        <span class="font-label-sm text-[11px] uppercase tracking-wider text-outline font-medium">Hora Local NOC</span>
                    </div>
                    <span class="font-code-sm text-[10px] text-tertiary px-1.5 py-0.2 rounded bg-tertiary/10 border border-tertiary/20 font-medium">UTC-5</span>
                </div>
                <div class="flex items-baseline justify-between font-code-sm mt-0.5">
                    <span class="text-[17px] font-bold text-on-surface tracking-tight" x-text="hora">--:--:--</span>
                    <span class="text-[11px] text-outline capitalize font-medium" x-text="fecha">---, -- --- ----</span>
                </div>
            </div>

        </div>

    </aside>

    {{-- ===================== MAIN PANEL (RESPONSIVE DESDE 320px HASTA 2560px) ===================== --}}
    <div class="pl-0 lg:pl-64 min-h-screen flex flex-col transition-all">

        {{-- Top Header Responsive --}}
        <header class="fixed top-0 left-0 lg:left-64 right-0 h-16 bg-surface-container-low/95 backdrop-blur-md border-b border-outline-variant z-40 flex items-center justify-between px-3 sm:px-6 lg:px-8">

            {{-- Left: Botón Hamburger + Selector de Sala --}}
            <div class="flex items-center gap-2 sm:gap-4 min-w-0" x-data="{
                openRooms: false,
                modalNuevaSala: false,
                nuevaSalaNombre: '',
                nuevaSalaCodigo: '',
                errorNuevaSala: '',
                currentRoom: 'sala01',
                rooms: [],

                init() {
                    const defaultRooms = [
                        { id: 'sala01', name: 'Sala 01 - Sala Técnica Principal', code: 'ESP32_SALA01' }
                    ];
                    try {
                        const raw = localStorage.getItem('intalnet_rooms');
                        if (raw) {
                            const parsed = JSON.parse(raw);
                            if (Array.isArray(parsed) && parsed.length > 0) {
                                // Preservar sala01 oficial y salas personalizadas agregadas legítimamente
                                const clean = parsed.filter(r => r.id === 'sala01' || r.custom === true);
                                this.rooms = clean.length > 0 ? clean : defaultRooms;
                            } else {
                                this.rooms = defaultRooms;
                            }
                        } else {
                            this.rooms = defaultRooms;
                        }
                    } catch (e) {
                        this.rooms = defaultRooms;
                    }
                    localStorage.setItem('intalnet_rooms', JSON.stringify(this.rooms));

                    const savedRoom = localStorage.getItem('intalnet_selected_room');
                    if (savedRoom && this.rooms.some(r => r.id === savedRoom)) {
                        this.currentRoom = savedRoom;
                    } else if (savedRoom === 'all' && this.rooms.length > 1) {
                        this.currentRoom = 'all';
                    } else {
                        this.currentRoom = 'sala01';
                        localStorage.setItem('intalnet_selected_room', 'sala01');
                    }
                },

                get currentRoomName() {
                    if (this.currentRoom === 'all') return 'Todas las Salas (NOC)';
                    const r = this.rooms.find(x => x.id === this.currentRoom);
                    return r ? r.name.split(' - ')[0] : 'Sala 01';
                },

                get currentRoomFullName() {
                    if (this.currentRoom === 'all') return 'Monitoreo Global NOC';
                    const r = this.rooms.find(x => x.id === this.currentRoom);
                    return r ? `Monitoreo de ${r.name}` : 'Monitoreo de Sala 01';
                },

                selectRoom(id) {
                    this.currentRoom = id;
                    localStorage.setItem('intalnet_selected_room', id);
                    this.openRooms = false;
                    window.dispatchEvent(new CustomEvent('intalnet:room-changed', { detail: { roomId: id } }));
                    const headerStatus = document.getElementById('esp32-header-status');
                    if (headerStatus) {
                        headerStatus.textContent = id === 'all' ? 'ESP32: Global' : `ESP32: ${this.currentRoomName}`;
                    }
                },

                abrirModalNuevaSala() {
                    this.openRooms = false;
                    const nextNum = this.rooms.length + 1;
                    const numStr = nextNum < 10 ? '0' + nextNum : nextNum;
                    this.nuevaSalaNombre = `Sala ${numStr} - `;
                    this.nuevaSalaCodigo = `ESP32_SALA${numStr}`;
                    this.errorNuevaSala = '';
                    this.modalNuevaSala = true;
                },

                guardarNuevaSala() {
                    const nombre = this.nuevaSalaNombre.trim();
                    if (!nombre || nombre === 'Sala ' || nombre.endsWith(' - ')) {
                        this.errorNuevaSala = 'Ingrese un nombre descriptivo para la sala (ej. Sala 02 - Pasillo Frío).';
                        return;
                    }
                    const nextNum = this.rooms.length + 1;
                    const cleanId = 'sala' + (nextNum < 10 ? '0' + nextNum : nextNum);
                    const newRoom = {
                        id: cleanId,
                        name: nombre,
                        code: (this.nuevaSalaCodigo || ('ESP32_' + cleanId.toUpperCase())).trim(),
                        custom: true
                    };
                    this.rooms.push(newRoom);
                    try {
                        localStorage.setItem('intalnet_rooms', JSON.stringify(this.rooms));
                    } catch (e) {}
                    this.modalNuevaSala = false;
                    this.selectRoom(cleanId);
                },

                eliminarSala(id) {
                    if (id === 'sala01') return;
                    if (!confirm('¿Desea retirar esta sala técnica del monitoreo?')) return;
                    this.rooms = this.rooms.filter(r => r.id !== id);
                    try {
                        localStorage.setItem('intalnet_rooms', JSON.stringify(this.rooms));
                    } catch (e) {}
                    if (this.currentRoom === id || (this.currentRoom === 'all' && this.rooms.length <= 1)) {
                        this.selectRoom('sala01');
                    }
                }
            }">
                {{-- Hamburger Button para Móviles y Tablets (< 1024px) --}}
                <button type="button"
                        @click="mobileMenuOpen = true"
                        class="lg:hidden p-1.5 sm:p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors shrink-0"
                        aria-label="Abrir menú de navegación">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                <div class="flex items-center gap-1.5 sm:gap-2 truncate">
                    <span class="font-headline-sm text-sm sm:text-base text-on-surface font-semibold shrink-0">AetherSens</span>
                    <span class="text-outline text-xs hidden sm:inline">/</span>
                    <span class="font-body-md text-xs sm:text-sm text-on-surface-variant truncate hidden md:inline" x-text="currentRoomFullName">Monitoreo de Sala 01</span>
                </div>

                {{-- Zone Selector Dropdown --}}
                <div class="relative shrink-0" @click.outside="openRooms = false">
                    <button type="button"
                            @click="openRooms = !openRooms"
                            class="flex items-center gap-1 sm:gap-1.5 bg-surface-container-high border border-outline-variant rounded-lg px-2 sm:px-3 py-1 sm:py-1.5 hover:bg-surface-container-highest transition-colors cursor-pointer text-left text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-[15px] sm:text-[16px] text-primary">location_on</span>
                        <span class="font-label-md text-label-sm sm:text-label-md text-on-surface" x-text="currentRoomName">Sala 01</span>
                        <span class="material-symbols-outlined text-[15px] sm:text-[16px] text-outline transition-transform" :class="openRooms ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>

                    <div x-show="openRooms"
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-72 bg-surface-container rounded-xl shadow-2xl border border-outline-variant/40 py-2 z-50">
                        <div class="px-3.5 py-1.5 text-[11px] text-outline uppercase font-code-sm border-b border-outline-variant/20 mb-1 flex items-center justify-between">
                            <span>Salas Técnicas &bull; Intalnet</span>
                            <span class="text-[10px] text-primary font-medium" x-text="`${rooms.length} activa${rooms.length > 1 ? 's' : ''}`"></span>
                        </div>

                        {{-- Lista de Salas Técnicas Activas --}}
                        <div class="max-h-60 overflow-y-auto divide-y divide-outline-variant/10">
                            <template x-for="r in rooms" :key="r.id">
                                <div class="flex items-center justify-between px-3 py-2 hover:bg-surface-container-high transition-colors group cursor-pointer"
                                     :class="currentRoom === r.id ? 'bg-primary/10' : ''"
                                     @click="selectRoom(r.id)">
                                    <div class="flex-1 min-w-0 pr-2">
                                        <div class="font-label-md text-xs sm:text-label-md font-medium truncate" :class="currentRoom === r.id ? 'text-primary' : 'text-on-surface'" x-text="r.name"></div>
                                        <div class="text-[10px] text-outline font-code-sm mt-0.5" x-text="r.code"></div>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <span x-show="currentRoom === r.id" class="material-symbols-outlined text-[18px] text-primary">check</span>
                                        <button x-show="r.id !== 'sala01'"
                                                type="button"
                                                @click.stop="eliminarSala(r.id)"
                                                class="opacity-0 group-hover:opacity-100 p-1 rounded text-outline hover:text-error hover:bg-error/10 transition-all"
                                                title="Eliminar sala">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            {{-- Opción Global NOC (solo si hay más de 1 sala técnica) --}}
                            <div x-show="rooms.length > 1"
                                 class="flex items-center justify-between px-3 py-2 hover:bg-surface-container-high transition-colors cursor-pointer border-t border-outline-variant/20"
                                 :class="currentRoom === 'all' ? 'bg-primary/10 text-primary' : 'text-on-surface'"
                                 @click="selectRoom('all')">
                                <div>
                                    <div class="font-label-md text-xs sm:text-label-md font-medium">Todas las Salas (NOC)</div>
                                    <div class="text-[10px] text-outline font-code-sm mt-0.5">Monitoreo unificado global</div>
                                </div>
                                <span x-show="currentRoom === 'all'" class="material-symbols-outlined text-[18px] text-primary">check</span>
                            </div>
                        </div>

                        {{-- Botón para Agregar Nueva Sala Técnica --}}
                        <div class="pt-2 px-3 border-t border-outline-variant/20 mt-1">
                            <button type="button"
                                    @click="abrirModalNuevaSala()"
                                    class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-surface-container-high hover:bg-primary hover:text-on-primary text-primary border border-primary/30 transition-all font-label-md text-xs">
                                <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                <span>Agregar Sala Técnica</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal para Agregar Nueva Sala Técnica --}}
                <div x-show="modalNuevaSala"
                     x-cloak
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-scrim/70 backdrop-blur-sm"
                     @click.self="modalNuevaSala = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="bg-surface-container rounded-2xl border border-outline-variant shadow-2xl max-w-md w-full p-6 text-on-surface"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/30 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[18px]">add_business</span>
                                </div>
                                <h3 class="font-headline-sm text-base sm:text-lg font-semibold text-on-surface">Nueva Sala Técnica</h3>
                            </div>
                            <button type="button" @click="modalNuevaSala = false" class="text-outline hover:text-on-surface p-1 rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>

                        <div class="space-y-4 py-4">
                            <p class="font-body-sm text-xs text-outline leading-relaxed">
                                Registre una nueva sala técnica o rack en la red de monitoreo de Intalnet. La sala quedará disponible inmediatamente para vincularse al ESP32.
                            </p>

                            <div>
                                <label class="block font-label-md text-xs text-outline mb-1">Nombre de la Sala / Sector *</label>
                                <input type="text"
                                       x-model="nuevaSalaNombre"
                                       placeholder="Ej: Sala 02 - Pasillo Frío"
                                       class="w-full bg-surface-container-high border border-outline-variant rounded-lg px-3 py-2 font-body-md text-sm text-on-surface placeholder:text-outline/50 focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                            </div>

                            <div>
                                <label class="block font-label-md text-xs text-outline mb-1">Identificador de Dispositivo ESP32</label>
                                <input type="text"
                                       x-model="nuevaSalaCodigo"
                                       placeholder="Ej: ESP32_SALA02"
                                       class="w-full bg-surface-container-high border border-outline-variant rounded-lg px-3 py-2 font-code-sm text-sm text-on-surface placeholder:text-outline/50 focus:ring-1 focus:ring-primary focus:border-primary outline-none uppercase">
                                <p class="text-[11px] text-outline mt-1 font-body-sm">Debe coincidir con la constante <code class="text-primary font-code-sm">DISPOSITIVO_ID</code> configurada en el firmware del ESP32.</p>
                            </div>

                            <div x-show="errorNuevaSala" class="p-2.5 rounded-lg bg-error/10 border border-error/30 text-error text-xs font-body-sm" x-text="errorNuevaSala"></div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/30">
                            <button type="button"
                                    @click="modalNuevaSala = false"
                                    class="px-4 py-2 rounded-lg bg-surface-container-high hover:bg-surface-container-highest text-outline hover:text-on-surface font-label-md text-xs sm:text-sm transition-colors">
                                Cancelar
                            </button>
                            <button type="button"
                                    @click="guardarNuevaSala()"
                                    class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/90 text-on-primary font-label-md text-xs sm:text-sm transition-colors flex items-center gap-1.5 shadow-md">
                                <span class="material-symbols-outlined text-[16px]">save</span>
                                <span>Guardar Sala</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: ESP32 Status + User Profile --}}
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">

                {{-- ESP32 Connection Pill (Adaptativo: compacto en móvil, completo en tablet/desktop) --}}
                <div class="flex items-center gap-1.5 sm:gap-2 bg-surface-container px-2 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-tertiary/30">
                    <div id="esp32-header-dot" class="w-2 h-2 rounded-full bg-tertiary shrink-0"></div>
                    <span id="esp32-header-status" class="font-code-sm text-[11px] sm:text-xs text-tertiary hidden sm:inline whitespace-nowrap">ESP32: Sala 01</span>
                </div>

                <div class="h-4 w-px bg-outline-variant hidden sm:block"></div>

                {{-- Notification Center (Alpine.js) --}}
                <div class="relative" x-data="{
                    openNotif: false,
                    alerts: [],
                    unreadCount: 0,
                    loading: false,

                    init() {
                        this.cargarNotificaciones();
                        window.addEventListener('intalnet:room-changed', () => this.cargarNotificaciones());
                        // Actualizar periódicamente estado de alertas
                        setInterval(() => this.cargarNotificaciones(), 30000);
                    },

                    async cargarNotificaciones() {
                        try {
                            const res = await fetch('/api/telemetry/alerts?per_page=4', {
                                headers: { 'Accept': 'application/json' }
                            });
                            if (res.ok) {
                                const json = await res.json();
                                if (json && json.data) {
                                    this.alerts = json.data;
                                    const criticas = json.counters?.criticas || 0;
                                    const advertencias = json.counters?.advertencias || 0;
                                    this.unreadCount = criticas + advertencias;
                                }
                            }
                        } catch (e) {}
                    }
                }">
                    <button type="button"
                            @click="openNotif = !openNotif"
                            aria-label="Notificaciones"
                            class="p-1 sm:p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors relative cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">notifications</span>
                        <span x-show="unreadCount > 0" x-cloak class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full animate-pulse"></span>
                    </button>

                    {{-- Dropdown de Notificaciones --}}
                    <div x-show="openNotif"
                         x-cloak
                         @click.outside="openNotif = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl bg-surface-container-high border border-outline-variant shadow-2xl py-2 z-50">

                        <div class="px-4 py-2 border-b border-outline-variant/30 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="font-headline-sm text-sm font-semibold text-on-surface">Notificaciones</span>
                                <span x-show="unreadCount > 0" class="px-1.5 py-0.5 rounded-full bg-error/20 text-error font-code-sm text-[10px]" x-text="`${unreadCount} activa${unreadCount > 1 ? 's' : ''}`"></span>
                            </div>
                            <span class="text-[11px] text-tertiary flex items-center gap-1 font-body-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span>
                                Monitoreo activo
                            </span>
                        </div>

                        {{-- Lista de Notificaciones --}}
                        <div class="max-h-64 overflow-y-auto divide-y divide-outline-variant/10">
                            <template x-for="item in alerts" :key="item.id">
                                <div class="px-4 py-2.5 hover:bg-surface-container transition-colors flex items-start gap-2.5">
                                    <div class="mt-0.5 w-6 h-6 rounded-md flex items-center justify-center shrink-0"
                                         :class="item.severidad === 'critical' ? 'bg-error/15 text-error' : (item.severidad === 'warning' ? 'bg-secondary/15 text-secondary' : 'bg-tertiary/15 text-tertiary')">
                                        <span class="material-symbols-outlined text-[15px]" x-text="item.severidad === 'critical' ? 'crisis_alert' : (item.severidad === 'warning' ? 'warning' : 'info')"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span class="font-label-md text-xs font-medium text-on-surface truncate" x-text="item.sensor"></span>
                                            <span class="text-[10px] text-outline font-code-sm" x-text="item.created_at ? item.created_at.slice(11, 16) : ''"></span>
                                        </div>
                                        <div class="text-[11px] text-outline truncate" x-text="item.zona || item.sala_id"></div>
                                        <div class="text-[11px] font-code-sm mt-0.5"
                                             :class="item.severidad === 'critical' ? 'text-error' : (item.severidad === 'warning' ? 'text-secondary' : 'text-tertiary')"
                                             x-text="item.valor"></div>
                                    </div>
                                </div>
                            </template>

                            {{-- Estado Vacío: Sin alertas pendientes --}}
                            <div x-show="alerts.length === 0" class="py-6 px-4 text-center">
                                <span class="material-symbols-outlined text-[28px] text-tertiary block mx-auto mb-1">verified</span>
                                <p class="font-label-md text-xs text-on-surface font-medium">Todo en orden</p>
                                <p class="text-[11px] text-outline mt-0.5">No hay alertas activas en las salas técnicas de Intalnet.</p>
                            </div>
                        </div>

                        {{-- Footer del Centro de Notificaciones --}}
                        <div class="pt-2 px-3 border-t border-outline-variant/20 mt-1">
                            <a href="{{ route('alert-logs') }}"
                               class="w-full flex items-center justify-center gap-1 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-highest text-primary font-label-md text-xs transition-colors">
                                <span>Ver todas las alertas</span>
                                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User Dropdown (Alpine.js) --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 pl-1 focus:outline-none cursor-pointer"
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
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="font-label-md text-label-md text-on-surface leading-none">{{ Auth::user()->name ?? 'Usuario' }}</span>
                            <span class="font-label-sm text-[11px] text-outline leading-tight mt-0.5">Administrador</span>
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

                        <a href="{{ route('register') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors font-body-md text-body-md">
                            <span class="material-symbols-outlined text-[16px]">person_add</span>
                            <span>Registrar Operador</span>
                        </a>

                        <div class="border-t border-outline-variant my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-space-sm px-space-md py-space-sm text-error hover:bg-error-container/20 transition-colors font-body-md text-body-md cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- Dynamic Page Content (Max width para 4K/2560px con padding fluido para 320px) --}}
        <main class="relative pt-20 bg-surface min-h-screen px-3 sm:px-6 lg:px-8 py-6 w-full max-w-[2560px] mx-auto">
            {{ $slot }}
        </main>

    </div>

    @stack('modals')
    @livewireScripts
    @stack('scripts')

</body>
</html>
