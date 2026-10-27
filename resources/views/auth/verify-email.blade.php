<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        <div class="w-full max-w-[480px] bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col border border-outline-variant/30">

            <div class="flex flex-col items-center text-center mb-space-lg">
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-[28px]">mark_email_read</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg font-semibold text-on-surface tracking-tight">
                    Verificación de Correo
                </h1>
                <p class="font-body-md text-body-md text-outline mt-2 text-center">
                    {{ __('Antes de continuar, por favor verifica tu dirección de correo haciendo clic en el enlace que te enviamos. Si no lo has recibido, te podemos enviar uno nuevo.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-space-md rounded-lg bg-tertiary-container/20 border border-tertiary/30 text-tertiary font-body-sm text-sm mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    <span>{{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}</span>
                </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button
                        type="submit"
                        class="w-full sm:w-auto bg-primary-container hover:bg-primary text-on-primary-container font-headline-sm text-body-md font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Reenviar Enlace</span>
                        <span class="material-symbols-outlined text-base">send</span>
                    </button>
                </form>

                <div class="flex items-center gap-4 text-sm">
                    <a
                        href="{{ route('profile.show') }}"
                        class="text-outline hover:text-on-surface transition-colors"
                    >
                        {{ __('Editar Perfil') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-error hover:underline transition-colors cursor-pointer">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
