<x-guest-layout>
    <div class="flex flex-col w-full">
        <div class="w-full max-w-6xl mx-auto px-margin py-space-xl flex flex-col items-center justify-center flex-1">
            <!-- Top System Telemetry Matrix Badge -->
            <div class="w-full max-w-2xl flex items-center justify-between mb-space-md px-space-sm">
                <div class="flex items-center gap-space-xs text-outline font-code-xs text-code-xs">
                    <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-ping"></span>
                    <span>SYS_GATEWAY: ACTIVO</span>
                    <span class="text-outline-variant">/</span>
                    <span>PORT: 8443</span>
                </div>
                <div class="flex items-center gap-space-xs font-code-xs text-code-xs bg-surface-container-high px-space-sm py-0.5 rounded text-primary border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[13px]">verified_user</span>
                    <span>PROVISIÓN SEGURA SSL/CAN-BUS</span>
                </div>
            </div>

            <!-- Main Terminal Registry Chassis -->
            <div class="w-full max-w-2xl bg-surface-container-lowest rounded-lg shadow-2xl overflow-hidden relative border border-outline-variant/40">
                <!-- Glow Edge Bar -->
                <div class="w-full h-1 bg-gradient-to-r from-primary via-secondary to-primary-container"></div>

                <!-- Panel Header -->
                <div class="p-space-lg bg-surface-container-low flex flex-col gap-space-xs border-b border-outline-variant/30">
                    <div class="flex flex-wrap items-center justify-between gap-space-sm">
                        <div class="flex items-center gap-space-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-highest flex items-center justify-center text-primary border border-outline-variant/30">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <div>
                                <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider block">ID PROTOCOLO: REG-SEC-4096</span>
                                <span class="font-headline-sm text-headline-sm text-on-surface uppercase leading-none">INTALNET TELEMETRY OS</span>
                            </div>
                        </div>
                        <span class="px-space-sm py-1 bg-surface-container-highest text-secondary font-label-caps text-label-caps uppercase rounded tracking-wider flex items-center gap-1 border border-outline-variant/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            NIVEL DE ACCESO: OPERADOR TÉCNICO
                        </span>
                    </div>
                    <div class="mt-space-sm">
                        <h1 class="font-headline-xl text-headline-xl text-on-surface uppercase leading-none tracking-wide">
                            Registro de Nuevo Operador
                        </h1>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                            Complete la ficha técnica para aprovisionar credenciales en la red de supervisión SCADA.
                        </p>
                    </div>
                </div>

                <!-- Validation Errors Banner -->
                @if ($errors->any())
                    <div class="m-space-lg mb-0 p-space-sm rounded bg-error-container/40 border border-error text-error font-code-xs text-code-xs space-y-1">
                        <div class="flex items-center gap-1 font-semibold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[16px]">warning</span>
                            <span>ERRORES EN FICHA DE REGISTRO:</span>
                        </div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Registration Form Area -->
                <form class="p-space-lg flex flex-col gap-space-lg" method="POST" action="{{ route('register') }}" id="operator-reg-form">
                    @csrf

                    <!-- Field 1 & 2: Grid for Names & Mail -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        <!-- Full Name -->
                        <div class="flex flex-col gap-space-xs">
                            <label class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider flex items-center justify-between" for="op_name">
                                <span>01 // Nombre Completo / ID</span>
                                <span class="text-primary font-code-xs text-code-xs">[REQUERIDO]</span>
                            </label>
                            <div class="relative flex items-center bg-surface-container rounded border border-outline-variant/30 group focus-within:border-primary focus-within:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-outline ml-space-sm group-focus-within:text-primary transition-colors">person</span>
                                <input class="w-full bg-transparent px-space-sm py-space-sm font-code-sm text-code-sm text-on-surface placeholder:text-outline/60 focus:outline-none"
                                    id="op_name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Ing. Roberto Escalante"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    type="text" />
                            </div>
                            <span class="font-code-xs text-code-xs text-outline">Matrícula profesional o DNI de planta</span>
                        </div>

                        <!-- Corporate Email -->
                        <div class="flex flex-col gap-space-xs">
                            <label class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider flex items-center justify-between" for="op_email">
                                <span>02 // Correo Institucional</span>
                                <span class="text-primary font-code-xs text-code-xs">[DOMINIO SCADA]</span>
                            </label>
                            <div class="relative flex items-center bg-surface-container rounded border border-outline-variant/30 group focus-within:border-primary focus-within:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-outline ml-space-sm group-focus-within:text-primary transition-colors">alternate_email</span>
                                <input class="w-full bg-transparent px-space-sm py-space-sm font-code-sm text-code-sm text-on-surface placeholder:text-outline/60 focus:outline-none"
                                    id="op_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="r.escalante@intalnet.tech"
                                    required
                                    autocomplete="username"
                                    type="email" />
                            </div>
                            <span class="font-code-xs text-code-xs text-outline">Correo con sufijo habilitado en la red</span>
                        </div>
                    </div>

                    <!-- Field 3: Access Level Selector Radio Matrix -->
                    <div class="flex flex-col gap-space-xs">
                        <label class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider flex items-center justify-between">
                            <span>03 // Nivel de Acceso Solicitado</span>
                            <span class="text-secondary font-code-xs text-code-xs">PERMISOS DETERMINISTAS</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-sm pt-space-xs">
                            <!-- Nivel 1 -->
                            <label class="cursor-pointer relative flex flex-col p-space-sm rounded bg-surface-container hover:bg-surface-container-high transition-all border border-outline-variant/30">
                                <input class="peer sr-only" name="access_level" type="radio" value="N1" {{ old('access_level') == 'N1' ? 'checked' : '' }} />
                                <div class="absolute inset-0 rounded pointer-events-none peer-checked:border-2 peer-checked:border-primary peer-checked:bg-primary/5"></div>
                                <div class="flex items-center justify-between mb-space-xs">
                                    <span class="font-label-caps text-label-caps text-outline peer-checked:text-primary uppercase font-bold">NIVEL 1</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-surface-variant"></span>
                                </div>
                                <span class="font-headline-sm text-headline-sm text-on-surface uppercase">Solo Lectura</span>
                                <p class="font-code-xs text-code-xs text-outline mt-1 leading-tight">Telemetría pasiva de bastidores, sin comandos de relé o corte térmico.</p>
                            </label>

                            <!-- Nivel 2 (Default Checked) -->
                            <label class="cursor-pointer relative flex flex-col p-space-sm rounded bg-surface-container hover:bg-surface-container-high transition-all border border-outline-variant/30">
                                <input class="peer sr-only" name="access_level" type="radio" value="N2" {{ old('access_level', 'N2') == 'N2' ? 'checked' : '' }} />
                                <div class="absolute inset-0 rounded pointer-events-none peer-checked:border-2 peer-checked:border-secondary peer-checked:bg-secondary/5"></div>
                                <div class="flex items-center justify-between mb-space-xs">
                                    <span class="font-label-caps text-label-caps text-secondary uppercase font-bold">NIVEL 2</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                                </div>
                                <span class="font-headline-sm text-headline-sm text-on-surface uppercase">Técnico / Acuse</span>
                                <p class="font-code-xs text-code-xs text-outline mt-1 leading-tight">Reinicio de bus CAN, control de ventiladores y acuse de alarmas.</p>
                            </label>

                            <!-- Nivel 3 -->
                            <label class="cursor-pointer relative flex flex-col p-space-sm rounded bg-surface-container hover:bg-surface-container-high transition-all border border-outline-variant/30">
                                <input class="peer sr-only" name="access_level" type="radio" value="N3" {{ old('access_level') == 'N3' ? 'checked' : '' }} />
                                <div class="absolute inset-0 rounded pointer-events-none peer-checked:border-2 peer-checked:border-tertiary peer-checked:bg-tertiary/5"></div>
                                <div class="flex items-center justify-between mb-space-xs">
                                    <span class="font-label-caps text-label-caps text-tertiary uppercase font-bold">NIVEL 3</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-surface-variant"></span>
                                </div>
                                <span class="font-headline-sm text-headline-sm text-on-surface uppercase">Supervisor Sala</span>
                                <p class="font-code-xs text-code-xs text-outline mt-1 leading-tight">E-Stop general, control de bypass HVAC, auditoría y seguridad.</p>
                            </label>
                        </div>
                    </div>

                    <!-- Field 4 & 5: Passwords & Visual Diagnostic Meter -->
                    <div class="flex flex-col gap-space-sm p-space-md bg-surface-container-low rounded border border-outline-variant/30">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                            <!-- Password Field -->
                            <div class="flex flex-col gap-space-xs">
                                <label class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider flex items-center justify-between" for="op_pass">
                                    <span>04 // Contraseña de Seguridad</span>
                                    <span class="text-primary font-code-xs text-code-xs">SHA-256</span>
                                </label>
                                <div class="relative flex items-center bg-surface-container rounded border border-outline-variant/30 group focus-within:border-primary focus-within:bg-surface-container-high transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-outline ml-space-sm">key</span>
                                    <input class="w-full bg-transparent px-space-sm py-space-sm font-code-sm text-code-sm text-on-surface placeholder:text-outline/60 focus:outline-none"
                                        id="op_pass"
                                        name="password"
                                        oninput="analyzePassword(this.value)"
                                        placeholder="••••••••••••"
                                        required
                                        autocomplete="new-password"
                                        type="password" />
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="flex flex-col gap-space-xs">
                                <label class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-wider flex items-center justify-between" for="op_pass_confirm">
                                    <span>05 // Confirmar Contraseña</span>
                                    <span class="text-outline font-code-xs text-code-xs" id="match-indicator">PENDIENTE</span>
                                </label>
                                <div class="relative flex items-center bg-surface-container rounded border border-outline-variant/30 group focus-within:border-primary focus-within:bg-surface-container-high transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-outline ml-space-sm">lock_reset</span>
                                    <input class="w-full bg-transparent px-space-sm py-space-sm font-code-sm text-code-sm text-on-surface placeholder:text-outline/60 focus:outline-none"
                                        id="op_pass_confirm"
                                        name="password_confirmation"
                                        oninput="checkMatch()"
                                        placeholder="••••••••••••"
                                        required
                                        autocomplete="new-password"
                                        type="password" />
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Visual Security Bar & Rules Breakdown -->
                        <div class="flex flex-col gap-space-xs pt-space-xs">
                            <div class="flex items-center justify-between font-code-xs text-code-xs text-outline">
                                <span>ROBUSTEZ CRIPTOGRÁFICA DE CLAVE:</span>
                                <span class="text-error font-medium" id="strength-text">INSUFICIENTE (0/3)</span>
                            </div>

                            <!-- Segmented Meter Bar -->
                            <div class="grid grid-cols-12 gap-1 h-1.5 w-full bg-surface-container-highest rounded-full overflow-hidden p-0.5">
                                <div class="h-full rounded-sm bg-surface-variant col-span-4 transition-colors" id="seg-1"></div>
                                <div class="h-full rounded-sm bg-surface-variant col-span-4 transition-colors" id="seg-2"></div>
                                <div class="h-full rounded-sm bg-surface-variant col-span-4 transition-colors" id="seg-3"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-1">
                                <div class="flex items-center gap-1 font-code-xs text-code-xs text-outline" id="rule-len">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    <span>MÍNIMO 8 CARACTERES</span>
                                </div>
                                <div class="flex items-center gap-1 font-code-xs text-code-xs text-outline" id="rule-char">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    <span>NÚMEROS O SÍMBOLOS</span>
                                </div>
                                <div class="flex items-center gap-1 font-code-xs text-code-xs text-outline" id="rule-token">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    <span>MAYÚSCULAS/MINÚSCULAS</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Field 6: Audit Agreement Checkbox -->
                    <label class="flex items-start gap-space-sm cursor-pointer p-space-sm bg-surface-container/60 hover:bg-surface-container rounded transition-colors group border border-outline-variant/20">
                        <input class="mt-1 accent-primary rounded cursor-pointer w-4 h-4 bg-surface-container-high"
                            id="audit-check"
                            name="terms"
                            required
                            type="checkbox" />
                        <span class="flex flex-col">
                            <span class="font-body-md text-body-md text-on-surface">
                                Acepto los términos de telemetría industrial y registro de auditoría de incidentes.
                            </span>
                            <span class="font-code-xs text-code-xs text-on-surface-variant mt-0.5 leading-tight">
                                Toda operación y pulso de acuse queda suscrito bajo el estándar de trazabilidad industrial ISO/IEC 27001 y FIPS-140-3 con marca temporal inmutable.
                            </span>
                        </span>
                    </label>

                    <!-- Primary Action Button -->
                    <div class="flex flex-col gap-space-xs pt-space-xs">
                        <button class="w-full bg-primary-container text-on-primary-container hover:bg-primary font-headline-md text-headline-md uppercase py-space-md px-space-lg rounded flex items-center justify-center gap-space-sm transition-all tracking-wider shadow-lg hover:shadow-primary/20 active:translate-y-0.5 font-bold"
                            id="submit-btn"
                            type="submit">
                            <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                            <span>REGISTRAR OPERADOR &amp; SOLICITAR APROBACIÓN</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Chassis Footer Links -->
                <div class="px-space-lg py-space-md bg-surface-container-low flex flex-wrap items-center justify-between gap-space-sm border-t border-outline-variant/30">
                    <a class="inline-flex items-center gap-1 font-body-sm text-body-sm text-primary hover:text-primary-fixed underline transition-colors" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-[16px]">lock_open</span>
                        <span>¿Ya tiene credenciales de acceso? Iniciar Sesión</span>
                    </a>
                    <div class="flex items-center gap-space-xs font-code-xs text-code-xs text-outline">
                        <span class="material-symbols-outlined text-[14px]">dns</span>
                        <span>TERMINAL NODE: SCADA-ARG-02</span>
                    </div>
                </div>
            </div>

            <!-- Diagnostic Warning / Supervised Gate Note -->
            <div class="w-full max-w-2xl mt-space-md px-space-md py-space-sm bg-surface-container-lowest rounded flex items-center gap-space-sm shadow-md border border-outline-variant/30">
                <div class="w-2 h-2 rounded-full bg-tertiary-container animate-pulse shrink-0"></div>
                <p class="font-code-xs text-code-xs text-on-surface-variant uppercase tracking-wider leading-relaxed">
                    <span class="text-tertiary font-bold">AVISO DE VALIDACIÓN:</span> 
                    LAS CUENTAS DE NUEVOS OPERADORES REQUIEREN VALIDACIÓN POR PARTE DEL SUPERVISOR DE SALA (NIVEL 3) ANTES DE HABILITAR ACCESO A LA MATRIZ DE TELEMETRÍA TÉRMICA.
                </p>
            </div>

            <!-- Supplementary Real-Time Telemetry Micro-Widget -->
            <div class="w-full max-w-2xl mt-space-lg grid grid-cols-2 md:grid-cols-4 gap-space-sm">
                <div class="bg-surface-container-low p-space-sm rounded flex flex-col justify-between border border-outline-variant/30">
                    <span class="font-code-xs text-code-xs text-outline uppercase">PUERTO ENCRIPTADO</span>
                    <span class="font-data-metric text-data-metric text-on-surface mt-1">443 / WSS</span>
                </div>
                <div class="bg-surface-container-low p-space-sm rounded flex flex-col justify-between border border-outline-variant/30">
                    <span class="font-code-xs text-code-xs text-outline uppercase">LATENCIA ENLACE</span>
                    <span class="font-data-metric text-data-metric text-secondary mt-1">1.2ms</span>
                </div>
                <div class="bg-surface-container-low p-space-sm rounded flex flex-col justify-between border border-outline-variant/30">
                    <span class="font-code-xs text-code-xs text-outline uppercase">SUPERVISORES ONLINE</span>
                    <span class="font-data-metric text-data-metric text-primary mt-1">03 ACTIVOS</span>
                </div>
                <div class="bg-surface-container-low p-space-sm rounded flex flex-col justify-between border border-outline-variant/30">
                    <span class="font-code-xs text-code-xs text-outline uppercase">ESTADO DE CAN-BUS</span>
                    <span class="font-data-metric text-data-metric text-secondary mt-1">NOMINAL OK</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Analysis Script -->
    <script>
        function analyzePassword(val) {
            const seg1 = document.getElementById('seg-1');
            const seg2 = document.getElementById('seg-2');
            const seg3 = document.getElementById('seg-3');
            const txt = document.getElementById('strength-text');
            const ruleLen = document.getElementById('rule-len');
            const ruleChar = document.getElementById('rule-char');
            const ruleToken = document.getElementById('rule-token');

            const hasLen = val.length >= 8;
            const hasChar = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(val);
            const hasMix = /[A-Z]/.test(val) && /[a-z]/.test(val);

            updateRuleState(ruleLen, hasLen);
            updateRuleState(ruleChar, hasChar);
            updateRuleState(ruleToken, hasMix);

            let score = 0;
            if (hasLen) score++;
            if (hasChar) score++;
            if (hasMix) score++;

            seg1.className = 'h-full rounded-sm col-span-4 transition-colors bg-surface-variant';
            seg2.className = 'h-full rounded-sm col-span-4 transition-colors bg-surface-variant';
            seg3.className = 'h-full rounded-sm col-span-4 transition-colors bg-surface-variant';

            if (score === 0) {
                txt.textContent = 'INSUFICIENTE (0/3)';
                txt.className = 'text-error font-medium';
            } else if (score === 1) {
                seg1.className = 'h-full rounded-sm col-span-4 transition-colors bg-error';
                txt.textContent = 'BAJA SEGURIDAD (1/3)';
                txt.className = 'text-error font-medium';
            } else if (score === 2) {
                seg1.className = 'h-full rounded-sm col-span-4 transition-colors bg-tertiary';
                seg2.className = 'h-full rounded-sm col-span-4 transition-colors bg-tertiary';
                txt.textContent = 'ACEPTABLE (2/3)';
                txt.className = 'text-tertiary font-medium';
            } else if (score === 3) {
                seg1.className = 'h-full rounded-sm col-span-4 transition-colors bg-secondary';
                seg2.className = 'h-full rounded-sm col-span-4 transition-colors bg-secondary';
                seg3.className = 'h-full rounded-sm col-span-4 transition-colors bg-secondary';
                txt.textContent = 'ROBUSTEZ FIPS ÓPTIMA (3/3)';
                txt.className = 'text-secondary font-medium';
            }

            checkMatch();
        }

        function updateRuleState(el, valid) {
            const icon = el.querySelector('.material-symbols-outlined');
            if (valid) {
                el.className = 'flex items-center gap-1 font-code-xs text-code-xs text-secondary';
                icon.textContent = 'check_circle';
            } else {
                el.className = 'flex items-center gap-1 font-code-xs text-code-xs text-outline';
                icon.textContent = 'cancel';
            }
        }

        function checkMatch() {
            const p1 = document.getElementById('op_pass').value;
            const p2 = document.getElementById('op_pass_confirm').value;
            const matchBadge = document.getElementById('match-indicator');

            if (!p2) {
                matchBadge.textContent = 'PENDIENTE';
                matchBadge.className = 'text-outline font-code-xs text-code-xs';
                return;
            }

            if (p1 === p2 && p1.length > 0) {
                matchBadge.textContent = 'COINCIDENCIA OK';
                matchBadge.className = 'text-secondary font-code-xs text-code-xs font-bold';
            } else {
                matchBadge.textContent = 'NO COINCIDEN';
                matchBadge.className = 'text-error font-code-xs text-code-xs font-bold';
            }
        }
    </script>
</x-guest-layout>
