<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        {{-- Auth Card --}}
        <div class="w-full max-w-[460px] bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col">

            {{-- Card Header --}}
            <div class="flex flex-col items-center text-center mb-space-lg">
                <div class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center mb-space-md shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[26px]">sensors</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">AetherSens</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-space-xs">Crear cuenta de acceso interno</p>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-space-md p-space-sm rounded-lg bg-error-container/30 border border-error/30 text-error font-body-sm text-body-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Registration Form --}}
            <form class="flex flex-col gap-space-md" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="name">Nombre completo</label>
                    <input
                        class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md px-space-md py-space-sm rounded-lg outline-none focus:bg-surface-container-low transition-colors border border-transparent focus:border-primary/40"
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        placeholder="Juan Pérez"
                        required
                        autofocus
                        autocomplete="name" />
                    @error('name')
                        <span class="font-body-sm text-body-sm text-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="email">Correo electrónico</label>
                    <input
                        class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md px-space-md py-space-sm rounded-lg outline-none focus:bg-surface-container-low transition-colors border border-transparent focus:border-primary/40"
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="usuario@empresa.com"
                        required
                        autocomplete="email" />
                    @error('email')
                        <span class="font-body-sm text-body-sm text-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="password">Contraseña</label>
                    <div class="relative flex items-center">
                        <input
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md px-space-md py-space-sm pr-11 rounded-lg outline-none focus:bg-surface-container-low transition-colors border border-transparent focus:border-primary/40"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••••••"
                            required
                            autocomplete="new-password" />
                        <button
                            aria-label="Alternar visibilidad de contraseña"
                            class="absolute right-space-sm text-outline hover:text-on-surface transition-colors p-1 flex items-center justify-center focus:outline-none"
                            onclick="togglePasswordVisibility('password', this)"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="font-body-sm text-body-sm text-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="flex flex-col gap-space-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="password_confirmation">Confirmar contraseña</label>
                    <div class="relative flex items-center">
                        <input
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md px-space-md py-space-sm pr-11 rounded-lg outline-none focus:bg-surface-container-low transition-colors border border-transparent focus:border-primary/40"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="••••••••••••"
                            required
                            autocomplete="new-password" />
                        <button
                            aria-label="Alternar visibilidad de confirmación de contraseña"
                            class="absolute right-space-sm text-outline hover:text-on-surface transition-colors p-1 flex items-center justify-center focus:outline-none"
                            onclick="togglePasswordVisibility('password_confirmation', this)"
                            type="button">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="font-body-sm text-body-sm text-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Terms checkbox (Jetstream) --}}
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="flex items-center gap-space-sm mt-space-xs">
                        <input
                            class="w-4 h-4 rounded bg-surface-container-lowest focus:ring-0 focus:outline-none transition-colors cursor-pointer accent-primary-container border border-outline-variant"
                            id="terms"
                            name="terms"
                            type="checkbox"
                            required />
                        <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="terms">
                            Acepto los
                            <a href="{{ route('terms.show') }}" class="text-primary hover:underline" target="_blank">términos de servicio</a>
                            y la
                            <a href="{{ route('policy.show') }}" class="text-primary hover:underline" target="_blank">política de privacidad</a>
                        </label>
                    </div>
                @else
                    {{-- Fallback terms checkbox without Jetstream feature --}}
                    <div class="flex items-center gap-space-sm mt-space-xs">
                        <input
                            class="w-4 h-4 rounded bg-surface-container-lowest cursor-pointer accent-primary-container border border-outline-variant"
                            id="terms"
                            type="checkbox"
                            required />
                        <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="terms">
                            Acepto los términos de uso interno
                        </label>
                    </div>
                @endif

                {{-- Submit Button --}}
                <button
                    class="w-full mt-space-sm py-space-sm px-space-md bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-headline-sm text-headline-sm rounded-lg shadow-sm transition-colors flex items-center justify-center gap-space-xs"
                    type="submit">
                    <span>Registrarse</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>

                {{-- Login link --}}
                <div class="text-center mt-space-sm">
                    <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors"
                       href="{{ route('login') }}">
                        ¿Ya tiene una cuenta? <span class="text-primary font-medium">Iniciar sesión</span>
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Password toggle script --}}
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon  = btn.querySelector('.material-symbols-outlined');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</x-guest-layout>
