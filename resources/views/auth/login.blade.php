<x-guest-layout>
    <div class="w-full flex flex-col items-center justify-center py-space-xl">

        {{-- Ambient glows --}}
        <div class="relative w-full max-w-[440px]">
            <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Auth Card --}}
            <div class="relative w-full bg-surface-container rounded-2xl shadow-card-lg p-10 flex flex-col">

                {{-- Logo + Brand --}}
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-14 h-14 rounded-xl bg-surface-container-low flex items-center justify-center mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-primary text-[28px]">sensors</span>
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-semibold">
                        AetherSens
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                        Acceso al Sistema de Monitoreo
                    </p>
                </div>

                {{-- Session Status --}}
                @session('status')
                    <div class="mb-4 p-space-sm rounded-lg bg-tertiary-container/20 border border-tertiary/30 text-tertiary flex items-center gap-2 font-body-sm text-body-sm">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>{{ $value }}</span>
                    </div>
                @endsession

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-4 p-space-sm rounded-lg bg-error-container/30 border border-error/30 text-error font-body-sm text-body-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form class="flex flex-col gap-5" method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="flex flex-col">
                        <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium"
                               for="email">
                            Correo electrónico
                        </label>
                        <input
                            class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-3 placeholder:text-outline/60 outline-none transition-all shadow-inner focus:bg-surface-container-low border border-transparent focus:border-primary/40"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="usuario@empresa.com"
                            required
                            autofocus
                            autocomplete="username" />
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col">
                        <label class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium"
                               for="password">
                            Contraseña
                        </label>
                        <div class="relative flex items-center">
                            <input
                                class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg pl-4 pr-11 py-3 placeholder:text-outline/60 outline-none transition-all shadow-inner focus:bg-surface-container-low border border-transparent focus:border-primary/40 tracking-widest font-code-sm"
                                id="password"
                                name="password"
                                type="password"
                                placeholder="••••••••••••"
                                required
                                autocomplete="current-password" />
                            <button
                                aria-label="Alternar visibilidad de contraseña"
                                class="absolute right-3 p-1 text-on-surface-variant hover:text-on-surface transition-colors flex items-center justify-center focus:outline-none"
                                id="togglePassword"
                                type="button">
                                <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input class="sr-only peer" id="remember_me" name="remember" type="checkbox" />
                            <div class="w-4 h-4 rounded bg-surface-container-lowest peer-checked:bg-primary-container flex items-center justify-center transition-all border border-outline-variant peer-checked:border-primary-container">
                                <span class="material-symbols-outlined text-on-primary-container text-[14px] opacity-0 peer-checked:opacity-100 transition-opacity font-bold">check</span>
                            </div>
                            <span class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors">
                                Recordar sesión
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="font-body-sm text-body-sm text-primary hover:underline transition-colors"
                               href="{{ route('password.request') }}">
                                ¿Olvidó su contraseña?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-3">
                        <button
                            class="w-full bg-primary-container hover:bg-primary-container/90 text-on-primary-container font-headline-sm text-headline-sm py-3 px-4 rounded-lg shadow-sm transition-all duration-150 flex items-center justify-center gap-2 font-medium active:scale-[0.99]"
                            type="submit">
                            <span>Iniciar Sesión</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>

                </form>

                {{-- Register link --}}
                @if (Route::has('register'))
                    <div class="text-center mt-6">
                        <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors"
                           href="{{ route('register') }}">
                            ¿No tiene cuenta? <span class="text-primary font-medium">Registrarse</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Password Toggle Script --}}
    <script>
        const passwordInput = document.getElementById('password');
        const toggleBtn     = document.getElementById('togglePassword');
        const eyeIcon       = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
                isPassword
                    ? passwordInput.classList.remove('tracking-widest')
                    : passwordInput.classList.add('tracking-widest');
            });
        }
    </script>
</x-guest-layout>
