<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        {{-- Session Status (link sent confirmation) --}}
        @session('status')
            <div class="w-full max-w-[460px] mb-space-md p-space-md rounded-xl bg-tertiary-container/20 border border-tertiary/30 text-tertiary flex items-center gap-space-sm font-label-md text-label-md">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <span>{{ $value }}</span>
            </div>
        @endsession

        {{-- Auth Card --}}
        <div class="w-full max-w-[460px] rounded-xl bg-surface-container-high p-8 sm:p-10 shadow-card-lg relative overflow-hidden">

            {{-- Ambient glows --}}
            <div class="absolute -top-24 -left-24 w-48 h-48 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

            {{-- Header --}}
            <div class="relative flex flex-col items-center text-center">

                {{-- Icon --}}
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[24px]">lock_reset</span>
                </div>

                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-space-md tracking-tight font-bold">
                    AetherSens
                </h1>
                <p class="font-label-md text-label-md text-on-surface-variant mt-space-xs font-medium">
                    Restablecer contraseña
                </p>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-space-md px-space-xs">
                    ¿Olvidó su contraseña? Indíquenos su correo electrónico y le
                    enviaremos un enlace para que pueda elegir una nueva.
                </p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mt-space-md p-space-sm rounded-lg bg-error-container/30 border border-error/30 text-error font-body-sm text-body-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form class="mt-8 relative" method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Email --}}
                <div>
                    <label
                        class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant mb-1.5 font-medium"
                        for="email">
                        Correo electrónico
                    </label>
                    <input
                        class="w-full rounded-lg bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md py-2.5 px-3.5 outline-none focus:bg-surface-container transition-colors shadow-inner border border-transparent focus:border-primary/40"
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="usuario@empresa.com"
                        required
                        autofocus
                        autocomplete="email" />
                    @error('email')
                        <p class="mt-1.5 font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button
                    class="w-full mt-6 py-3 px-4 rounded-lg bg-primary-container hover:bg-primary-container/90 active:bg-inverse-primary text-on-surface font-label-md text-label-md font-semibold flex items-center justify-center gap-space-xs transition-colors shadow-sm cursor-pointer"
                    type="submit">
                    <span>Enviar enlace de restablecimiento</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </button>

                {{-- Back to login --}}
                <div class="mt-6 text-center">
                    <a class="inline-flex items-center gap-1 font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors duration-150"
                       href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                        <span>Volver al inicio de sesión</span>
                    </a>
                </div>

            </form>

            {{-- Telemetry node footnote --}}
            <div class="mt-8 flex items-center gap-space-xs text-on-surface-variant/70 font-code-sm text-code-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                <span class="tracking-wide">TELEMETRY ACCESS GATEWAY</span>
            </div>
        </div>
    </div>
</x-guest-layout>
