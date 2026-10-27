<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        <div class="w-full max-w-[460px] bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col border border-outline-variant/30">

            <div class="flex flex-col items-center text-center mb-space-lg">
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-[28px]">verified_user</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg font-semibold text-on-surface tracking-tight">
                    Confirmar Identidad
                </h1>
                <p class="font-body-md text-body-md text-outline mt-2 text-center">
                    {{ __('Esta es un área protegida del sistema. Por favor confirma tu contraseña para continuar.') }}
                </p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.confirm') }}" class="flex flex-col gap-space-md">
                @csrf

                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium" for="password">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        autofocus
                        placeholder="••••••••••••"
                        class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/60 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                    />
                </div>

                <div class="mt-2">
                    <button
                        type="submit"
                        class="w-full bg-primary-container hover:bg-primary text-on-primary-container font-headline-sm text-body-md font-semibold py-3 px-4 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group cursor-pointer"
                    >
                        <span>Confirmar y Continuar</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
