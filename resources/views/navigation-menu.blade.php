<nav x-data="{ open: false }" class="bg-surface-container border-b border-outline-variant/30 select-none">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-on-surface">
                        <div class="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center text-on-primary-container">
                            <span class="material-symbols-outlined text-[20px]">sensors</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm font-semibold tracking-tight">AetherSens</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        Panel de Control
                    </x-nav-link>
                    <x-nav-link href="{{ route('historical-data') }}" :active="request()->routeIs('historical-data')">
                        Historial
                    </x-nav-link>
                    <x-nav-link href="{{ route('alert-logs') }}" :active="request()->routeIs('alert-logs')">
                        Alertas
                    </x-nav-link>
                    <x-nav-link href="{{ route('settings') }}" :active="request()->routeIs('settings')">
                        Configuración
                    </x-nav-link>
                    <x-nav-link href="{{ route('docs') }}" :active="request()->routeIs('docs')">
                        Ayuda / Docs
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()?->profile_photo_url)
                                <button class="flex text-sm border-2 border-outline-variant/40 rounded-full focus:outline-none focus:border-primary transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()?->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent font-body-md text-sm rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high focus:outline-none transition ease-in-out duration-150">
                                        <span class="material-symbols-outlined text-[18px] mr-1.5 text-primary">person</span>
                                        {{ Auth::user()?->name ?? 'Usuario' }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 font-label-sm text-label-sm text-outline uppercase tracking-wider">
                                Cuenta de Usuario
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                Perfil
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('settings') }}">
                                Configuración
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('docs') }}">
                                Manuales &amp; Soporte
                            </x-dropdown-link>

                            <div class="border-t border-outline-variant/30 my-1"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-outline hover:text-on-surface hover:bg-surface-container-high focus:outline-none transition">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-outline-variant/30">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Panel de Control
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('historical-data') }}" :active="request()->routeIs('historical-data')">
                Historial de Datos
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('alert-logs') }}" :active="request()->routeIs('alert-logs')">
                Registro de Alertas
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('settings') }}" :active="request()->routeIs('settings')">
                Configuración
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('docs') }}" :active="request()->routeIs('docs')">
                Ayuda / Docs
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-outline-variant/30">
            <div class="flex items-center px-4">
                <div>
                    <div class="font-headline-sm text-headline-sm font-medium text-on-surface">{{ Auth::user()?->name ?? 'Usuario' }}</div>
                    <div class="font-body-sm text-body-sm text-outline">{{ Auth::user()?->email ?? '' }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
