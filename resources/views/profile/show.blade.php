<x-app-layout>
    <div x-data="{
        seccionActiva: 'perfil',

        irA(seccion) {
            this.seccionActiva = seccion;
            if (history.pushState) {
                history.pushState(null, null, '#' + seccion);
            } else {
                window.location.hash = seccion;
            }
        },

        init() {
            const hash = window.location.hash.replace('#', '');
            const validas = ['perfil', 'password', 'two-factor', 'sesiones', 'eliminar'];
            if (validas.includes(hash)) {
                this.seccionActiva = hash;
            }
        }
    }" class="max-w-7xl mx-auto w-full flex flex-col gap-y-6 pb-12">

        {{-- ============================================================ --}}
        {{-- ENCABEZADO DE PÁGINA                                         --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-[11px] sm:text-label-sm uppercase tracking-wider text-outline block">
                    AetherSens Enterprise &bull; Seguridad y Cuenta &bull; Montería, CO
                </span>
                <h1 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface mt-1">
                    Gestión de Perfil y Credenciales
                </h1>
            </div>

            {{-- Identidad de usuario autenticado --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-high border border-outline-variant/30 text-on-surface max-w-full">
                    <span class="w-2.5 h-2.5 rounded-full bg-tertiary shrink-0"></span>
                    <span class="font-body-md text-xs sm:text-body-md truncate">{{ Auth::user()->name }}</span>
                    <span class="text-outline text-xs hidden sm:inline">&bull;</span>
                    <span class="font-code-sm text-xs sm:text-code-sm text-primary truncate hidden sm:inline">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PESTAÑAS DE OPCIONES (STICKY)                                --}}
        {{-- ============================================================ --}}
        <div class="sticky top-16 z-20 -mx-3 sm:-mx-6 lg:-mx-8 px-3 sm:px-6 lg:px-8 py-2 bg-surface/95 backdrop-blur-md border-b border-outline-variant/30 flex items-center shadow-md">

            {{-- Pestañas directas: solo las opciones del perfil --}}
            <nav class="flex items-center gap-2 overflow-x-auto scrollbar-none py-1 w-full" aria-label="Pestañas de opciones de perfil">

                {{-- Opción 1: Información del Perfil --}}
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <button type="button"
                       @click="irA('perfil')"
                       :class="seccionActiva === 'perfil'
                           ? 'bg-primary text-on-primary font-medium shadow-sm'
                           : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest border border-outline-variant/30'"
                       class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg font-label-md text-xs sm:text-label-md transition-all shrink-0 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span>Información del Perfil</span>
                    </button>
                @endif

                {{-- Opción 2: Actualizar Contraseña --}}
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <button type="button"
                       @click="irA('password')"
                       :class="seccionActiva === 'password'
                           ? 'bg-primary text-on-primary font-medium shadow-sm'
                           : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest border border-outline-variant/30'"
                       class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg font-label-md text-xs sm:text-label-md transition-all shrink-0 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">key</span>
                        <span>Actualizar Contraseña</span>
                    </button>
                @endif

                {{-- Opción 3: Autenticación de Dos Factores (2FA) --}}
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <button type="button"
                       @click="irA('two-factor')"
                       :class="seccionActiva === 'two-factor'
                           ? 'bg-primary text-on-primary font-medium shadow-sm'
                           : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest border border-outline-variant/30'"
                       class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg font-label-md text-xs sm:text-label-md transition-all shrink-0 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">security</span>
                        <span>Autenticación 2FA</span>
                    </button>
                @endif

                {{-- Opción 4: Sesiones Activas de Navegador --}}
                <button type="button"
                   @click="irA('sesiones')"
                   :class="seccionActiva === 'sesiones'
                       ? 'bg-primary text-on-primary font-medium shadow-sm'
                       : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest border border-outline-variant/30'"
                   class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg font-label-md text-xs sm:text-label-md transition-all shrink-0 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">devices</span>
                    <span>Sesiones Activas</span>
                </button>

                {{-- Opción 5: Eliminar Cuenta de Usuario --}}
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <button type="button"
                       @click="irA('eliminar')"
                       :class="seccionActiva === 'eliminar'
                           ? 'bg-error text-on-error font-medium shadow-sm'
                           : 'bg-surface-container-high text-error hover:bg-error/20 border border-error/30'"
                       class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg font-label-md text-xs sm:text-label-md transition-all shrink-0 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                        <span>Eliminar Cuenta</span>
                    </button>
                @endif
            </nav>
        </div>

        {{-- ============================================================ --}}
        {{-- CONTENIDO DE LA OPCIÓN SELECCIONADA (SOLO UNA A LA VEZ)      --}}
        {{-- ============================================================ --}}
        <div class="pt-2">

            {{-- 1. INFORMACIÓN DEL PERFIL --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div id="perfil"
                     x-show="seccionActiva === 'perfil'"
                     x-cloak>
                    @livewire('profile.update-profile-information-form')
                </div>
            @endif

            {{-- 2. ACTUALIZAR CONTRASEÑA --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div id="password"
                     x-show="seccionActiva === 'password'"
                     x-cloak>
                    @livewire('profile.update-password-form')
                </div>
            @endif

            {{-- 3. AUTENTICACIÓN DE DOS FACTORES (2FA) --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div id="two-factor"
                     x-show="seccionActiva === 'two-factor'"
                     x-cloak>
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            {{-- 4. SESIONES ACTIVAS DE NAVEGADOR --}}
            <div id="sesiones"
                 x-show="seccionActiva === 'sesiones'"
                 x-cloak>
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- 5. ELIMINAR CUENTA DE USUARIO --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div id="eliminar"
                     x-show="seccionActiva === 'eliminar'"
                     x-cloak>
                    @livewire('profile.delete-user-form')
                </div>
            @endif

        </div>

    </div>
</x-app-layout>
