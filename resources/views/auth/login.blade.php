<x-guest-layout>
    <div class="flex flex-col w-full items-center justify-center py-space-xl px-margin flex-1">
        <div class="w-full max-w-4xl relative">
            <div class="absolute -top-12 -left-12 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -right-10 w-80 h-80 bg-secondary/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative bg-surface-container-lowest shadow-2xl rounded-lg overflow-hidden border border-outline-variant/40">
                <!-- Top Gateway Status Header -->
                <div class="bg-surface-container-high px-space-xl py-space-md flex flex-wrap items-center justify-between gap-space-sm border-b border-outline-variant/30">
                    <div class="flex items-center gap-space-md">
                        <span class="inline-flex items-center gap-space-xs px-space-sm py-0.5 rounded bg-surface-container text-secondary font-label-caps text-label-caps tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                            CAN GATEWAY: ONLINE
                        </span>
                        <span class="font-code-xs text-code-xs text-on-surface-variant font-medium">ADDR: 192.168.10.45:9200</span>
                    </div>
                    <div class="flex items-center gap-space-md">
                        <span class="font-code-xs text-code-xs text-outline font-medium">SEC_LEVEL: SIL-3</span>
                        <span class="font-code-xs text-code-xs text-primary font-medium">SESSION: EPHEMERAL</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <!-- Left Technical Telemetry Side Bay -->
                    <div class="lg:col-span-4 bg-surface-container-low p-space-xl flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-outline-variant/30">
                        <div class="space-y-space-lg">
                            <div class="flex items-center gap-space-sm">
                                <div class="w-9 h-9 rounded bg-primary/10 flex items-center justify-center text-primary border border-primary/20">
                                    <span class="material-symbols-outlined text-[20px]">precision_manufacturing</span>
                                </div>
                                <div>
                                    <span class="font-headline-sm text-headline-sm text-on-surface tracking-wide block leading-none">TELEMETRY OS</span>
                                    <span class="font-code-xs text-code-xs text-outline uppercase">Core v4.8.1-RT</span>
                                </div>
                            </div>

                            <div class="bg-surface-container p-space-md rounded space-y-space-sm border border-outline-variant/30">
                                <div class="flex justify-between items-center">
                                    <span class="font-code-xs text-code-xs text-on-surface-variant">SENSORES TÉRMICOS</span>
                                    <span class="font-code-xs text-code-xs text-secondary font-medium">SHT31 / MQ-2 OK</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                    <div class="bg-secondary h-full w-full"></div>
                                </div>
                                <div class="flex justify-between items-center pt-space-xs">
                                    <span class="font-code-xs text-code-xs text-on-surface-variant">BUS CAN_0 / CAN_1</span>
                                    <span class="font-code-xs text-code-xs text-primary font-medium">250 kbps</span>
                                </div>
                                <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                    <div class="bg-primary h-full w-[94%]"></div>
                                </div>
                            </div>

                            <div class="space-y-space-xs">
                                <span class="font-label-caps text-label-caps text-outline block">ESTADO DE HARDWARE RACK</span>
                                <div class="p-space-sm rounded bg-surface-container-lowest text-on-surface flex items-center justify-between border border-outline-variant/20">
                                    <span class="font-code-xs text-code-xs text-on-surface-variant">TEMP SALA MEDIA</span>
                                    <span class="font-data-metric text-data-metric text-primary">21.8°C</span>
                                </div>
                                <div class="p-space-sm rounded bg-surface-container-lowest text-on-surface flex items-center justify-between border border-outline-variant/20">
                                    <span class="font-code-xs text-code-xs text-on-surface-variant">FLUJO EXTRACTOR</span>
                                    <span class="font-data-metric text-data-metric text-secondary">1,420 CFM</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-space-lg">
                            <div class="flex items-center gap-space-xs text-outline">
                                <span class="material-symbols-outlined text-[14px]">verified_user</span>
                                <span class="font-code-xs text-code-xs tracking-wider">HSM FIPS-140-3 ACTIVO</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Form Column -->
                    <div class="lg:col-span-8 p-space-xl bg-surface-container-lowest flex flex-col justify-between">
                        <div>
                            <div class="mb-space-lg">
                                <span class="font-label-caps text-label-caps text-primary tracking-widest block uppercase mb-1">ACCESO RESTRINGIDO • TERMINAL INDUSTRIAL</span>
                                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Autenticación de Operador</h1>
                                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ingrese sus credenciales de operador para acceder al centro de control térmico.</p>
                            </div>

                            <!-- Session Status Notification -->
                            @session('status')
                                <div class="mb-space-md p-space-sm rounded bg-secondary-container/20 border border-secondary text-secondary font-code-sm text-code-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    <span>{{ $value }}</span>
                                </div>
                            @endsession

                            <!-- Validation Errors Banner -->
                            @if ($errors->any())
                                <div class="mb-space-md p-space-sm rounded bg-error-container/40 border border-error text-error font-code-xs text-code-xs space-y-1">
                                    <div class="flex items-center gap-1 font-semibold uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[16px]">warning</span>
                                        <span>FALLO DE AUTENTICACIÓN:</span>
                                    </div>
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="space-y-space-md" method="POST" action="{{ route('login') }}" id="industrial-login-form">
                                @csrf

                                <!-- Operator Email -->
                                <div>
                                    <label class="flex justify-between items-center mb-1" for="email">
                                        <span class="font-code-xs text-code-xs text-on-surface font-medium uppercase tracking-wider">Correo Electrónico / ID Operador</span>
                                        <span class="font-code-xs text-code-xs text-outline">SINTAXIS: OP-XXXX</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-space-md text-on-surface-variant material-symbols-outlined text-[18px]">badge</span>
                                        <input class="w-full bg-surface-container-high text-on-surface font-code-sm text-code-sm pl-9 pr-space-md py-space-sm rounded border border-outline-variant/30 outline-none focus:border-primary focus:bg-surface-container-highest transition-colors placeholder:text-outline"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="operador@intalnet.tech"
                                            required
                                            autofocus
                                            autocomplete="username"
                                            type="email" />
                                    </div>
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="font-code-xs text-code-xs text-on-surface font-medium uppercase tracking-wider" for="password">Contraseña de Seguridad</label>
                                        @if (Route::has('password.request'))
                                            <a class="font-code-xs text-code-xs text-primary hover:underline" href="{{ route('password.request') }}">¿Olvidó su clave de seguridad?</a>
                                        @endif
                                    </div>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-space-md text-on-surface-variant material-symbols-outlined text-[18px]">key</span>
                                        <input class="w-full bg-surface-container-high text-on-surface font-code-sm text-code-sm pl-9 pr-10 py-space-sm rounded border border-outline-variant/30 outline-none focus:border-primary focus:bg-surface-container-highest transition-colors placeholder:text-outline tracking-wider"
                                            id="password"
                                            name="password"
                                            placeholder="••••••••••••"
                                            required
                                            autocomplete="current-password"
                                            type="password" />
                                        <button aria-label="Alternar visibilidad de contraseña" class="absolute right-space-md text-on-surface-variant hover:text-on-surface flex items-center" id="toggle-pass" onclick="togglePasswordVisibility()" type="button">
                                            <span class="material-symbols-outlined text-[18px]" id="pass-icon">visibility</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Workstation Node Selector (Technical decoration / local context) -->
                                <div>
                                    <label class="flex justify-between items-center mb-1" for="workstation-node">
                                        <span class="font-code-xs text-code-xs text-on-surface font-medium uppercase tracking-wider">Estación de Trabajo / Nodo</span>
                                        <span class="font-code-xs text-code-xs text-secondary">ENLACE DETERMINISTA</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-space-md text-on-surface-variant material-symbols-outlined text-[18px]">hub</span>
                                        <select class="w-full bg-surface-container-high text-on-surface font-code-sm text-code-sm pl-9 pr-space-md py-space-sm rounded border border-outline-variant/30 outline-none focus:border-primary focus:bg-surface-container-highest transition-colors cursor-pointer appearance-none" id="workstation-node">
                                            <option value="node-01">Nodo 01 - Sala Térmica Principal [PRI-HOT-ISLE]</option>
                                            <option value="node-02">Nodo 02 - Rack UPS &amp; Subestación [SUB-EAST-PWR]</option>
                                            <option selected value="console-supervision">Consola Central Supervisión [SCADA-MON-01]</option>
                                        </select>
                                        <span class="absolute right-space-md text-outline pointer-events-none material-symbols-outlined text-[18px]">expand_more</span>
                                    </div>
                                </div>

                                <!-- Remember me checkbox -->
                                <div class="pt-space-xs flex items-center justify-between">
                                    <label class="flex items-center gap-space-sm cursor-pointer select-none">
                                        <input class="w-4 h-4 rounded bg-surface-container-high text-primary accent-primary focus:ring-0 cursor-pointer" id="remember_me" name="remember" type="checkbox" />
                                        <span class="font-code-xs text-code-xs text-on-surface-variant">Mantener sesión activa en terminal local (Hardware Token)</span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-space-sm">
                                    <button class="w-full relative group overflow-hidden bg-primary-container text-on-primary-container font-headline-md text-headline-md py-space-sm px-space-lg rounded flex items-center justify-center gap-space-sm shadow-md hover:bg-primary transition-colors uppercase tracking-wider font-bold" id="submit-btn" type="submit">
                                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">lock_open</span>
                                        <span>Iniciar Sesión en Telemetry OS</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Registration link -->
                        <div class="mt-space-lg pt-space-md bg-surface-container-low/50 p-space-md rounded flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm border border-outline-variant/20">
                            <span class="font-body-sm text-body-sm text-on-surface-variant">
                                ¿No cuenta con credenciales activadas?
                            </span>
                            @if (Route::has('register'))
                                <a class="font-code-xs text-code-xs text-primary font-semibold hover:underline flex items-center gap-1" href="{{ route('register') }}">
                                    <span>Solicitar registro de operador</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Strip -->
                <div class="bg-surface-container px-space-xl py-space-xs flex items-center justify-between border-t border-outline-variant/30">
                    <div class="flex items-center gap-space-xs text-outline">
                        <span class="material-symbols-outlined text-[14px] text-error">gpp_maybe</span>
                        <span class="font-code-xs text-code-xs uppercase tracking-widest text-outline">ACCESO RESTRINGIDO • PROTOCOLO INDUSTRIAL TLS 1.3 // REGISTRO AUDITADO EN BUFFER WAL</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-on-surface-variant">ID_RACK: CLU-ALPHA-09</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pass-icon');
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
