<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        <div class="w-full max-w-[460px] bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col border border-outline-variant/30" x-data="{ recovery: false }">

            <div class="flex flex-col items-center text-center mb-space-lg">
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-[28px]">security</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg font-semibold text-on-surface tracking-tight">
                    Autenticación en Dos Pasos
                </h1>
                <p class="font-body-md text-body-md text-outline mt-2 text-center" x-show="! recovery">
                    {{ __('Ingresa el código de autenticación generado por tu aplicación autenticadora.') }}
                </p>
                <p class="font-body-md text-body-md text-outline mt-2 text-center" x-cloak x-show="recovery">
                    {{ __('Ingresa uno de tus códigos de recuperación de emergencia.') }}
                </p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}" class="flex flex-col gap-space-md">
                @csrf

                <div class="flex flex-col gap-space-xs" x-show="! recovery">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="code">
                        Código de Autenticación
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        inputmode="numeric"
                        autofocus
                        x-ref="code"
                        autocomplete="one-time-code"
                        placeholder="123456"
                        class="w-full bg-surface-container-lowest text-on-surface font-mono text-center tracking-[0.3em] font-body-md text-lg rounded-lg px-4 py-3 placeholder:text-outline/40 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="flex flex-col gap-space-xs" x-cloak x-show="recovery">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="recovery_code">
                        Código de Recuperación
                    </label>
                    <input
                        type="text"
                        id="recovery_code"
                        name="recovery_code"
                        x-ref="recovery_code"
                        autocomplete="one-time-code"
                        placeholder="abcde-12345"
                        class="w-full bg-surface-container-lowest text-on-surface font-mono text-center font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/40 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="mt-2">
                    <button
                        type="submit"
                        class="w-full bg-primary-container hover:bg-primary text-on-primary-container font-headline-sm text-body-md font-semibold py-3 px-4 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group cursor-pointer"
                    >
                        <span>Verificar y Acceder</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                    </button>
                </div>

                <div class="mt-4 pt-4 border-t border-outline-variant/30 text-center">
                    <button
                        type="button"
                        class="font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors cursor-pointer"
                        x-show="! recovery"
                        x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })"
                    >
                        {{ __('Usar un código de recuperación') }}
                    </button>

                    <button
                        type="button"
                        class="font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors cursor-pointer"
                        x-cloak
                        x-show="recovery"
                        x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                    >
                        {{ __('Usar código de la app autenticadora') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
