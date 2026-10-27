<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        {{-- Auth Card --}}
        <div class="w-full max-w-[460px] bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col border border-outline-variant/30">

            {{-- Brand / Header --}}
            <div class="flex flex-col items-center text-center mb-space-lg">
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-[28px]">lock_reset</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg font-semibold text-on-surface tracking-tight">
                    AetherSens
                </h1>
                <p class="font-body-md text-body-md text-outline mt-1">
                    Actualizar credenciales de acceso
                </p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-space-md">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="email">
                        Correo Corporativo
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/60 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="password">
                        Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••••••"
                        class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/60 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="password_confirmation">
                        Confirmar Contraseña
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••••••"
                        class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/60 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="mt-2">
                    <button
                        type="submit"
                        class="w-full bg-primary-container hover:bg-primary text-on-primary-container font-headline-sm text-body-md font-semibold py-3 px-4 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group cursor-pointer"
                    >
                        <span>Restablecer Contraseña</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                    </button>
                </div>

                <div class="mt-4 pt-4 border-t border-outline-variant/30 text-center">
                    <a href="{{ route('login') }}" class="font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        <span>Volver al inicio de sesión</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
