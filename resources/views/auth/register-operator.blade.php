<x-app-layout>
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-y-8 pb-12">

        {{-- ============================================================ --}}
        {{-- ENCABEZADO DE PÁGINA                                         --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    AetherSens Enterprise &bull; Control de Acceso Interno &bull; Montería, CO
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Registrar Nuevo Operador
                </h1>
            </div>

            {{-- Badge de seguridad --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-surface-container-high border border-outline-variant/30 text-on-surface">
                    <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
                    <span class="font-body-md text-body-md">Aprovisionamiento Restringido</span>
                    <span class="text-outline text-xs">&bull;</span>
                    <span class="font-code-sm text-code-sm text-tertiary">Sesión Autorizada</span>
                </div>
            </div>
        </div>

        {{-- Alerta de éxito --}}
        @if (session('status'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-tertiary/15 border border-tertiary/30 text-on-surface">
                <span class="material-symbols-outlined text-tertiary text-[22px]">check_circle</span>
                <div class="flex-1 font-body-md text-body-md">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        {{-- Alerta de errores de validación --}}
        @if ($errors->any())
            <div class="flex flex-col gap-1 p-4 rounded-xl bg-error/10 border border-error/30 text-error">
                <div class="flex items-center gap-2 font-headline-sm text-headline-sm font-medium">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>Corrige los siguientes errores antes de continuar:</span>
                </div>
                <ul class="list-disc list-inside mt-2 font-body-sm text-body-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- CONTENIDO PRINCIPAL: FORMULARIO + RESUMEN DE OPERADORES      --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- COLUMNA IZQUIERDA: Formulario de Registro --}}
            <div class="lg:col-span-2 bg-surface-container rounded-2xl border border-outline-variant/30 p-6 sm:p-8 flex flex-col gap-6 shadow-card">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">person_add</span>
                        <span>Credenciales del Nuevo Operador</span>
                    </h2>
                    <p class="font-body-sm text-body-sm text-outline mt-1">
                        Crea una cuenta para personal interno de Intalnet. La persona podrá acceder con su correo y contraseña.
                    </p>
                </div>

                <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
                    @csrf

                    {{-- Nombre Completo --}}
                    <div class="space-y-1.5">
                        <label for="name" class="block font-label-md text-label-md text-on-surface">
                            Nombre Completo <span class="text-error">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ej. Carlos Mendoza"
                            required
                            autofocus
                            class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3.5 py-2.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>

                    {{-- Correo Electrónico --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block font-label-md text-label-md text-on-surface">
                            Correo Electrónico Institucional <span class="text-error">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="carlos.mendoza@intalnet.com"
                            required
                            class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3.5 py-2.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>

                    {{-- Nivel de Acceso --}}
                    <div class="space-y-1.5">
                        <label for="access_level" class="block font-label-md text-label-md text-on-surface">
                            Nivel de Acceso / Rol <span class="text-error">*</span>
                        </label>
                        <select
                            id="access_level"
                            name="access_level"
                            class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3.5 py-2.5 text-on-surface font-body-md text-body-md focus:ring-1 focus:ring-primary focus:border-primary">
                            <option value="N2" {{ old('access_level') == 'N2' ? 'selected' : '' }}>NIVEL 2 — Técnico de Operación (Gestión y silenciamiento de alarmas)</option>
                            <option value="N3" {{ old('access_level') == 'N3' ? 'selected' : '' }}>NIVEL 3 — Supervisor (Control total de umbrales y hardware)</option>
                            <option value="N1" {{ old('access_level') == 'N1' ? 'selected' : '' }}>NIVEL 1 — Solo Lectura (Inspección y monitoreo de gráficos)</option>
                        </select>
                    </div>

                    {{-- Fila: Contraseña y Confirmación --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Contraseña --}}
                        <div class="space-y-1.5">
                            <label for="password" class="block font-label-md text-label-md text-on-surface">
                                Contraseña Inicial <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••••••"
                                    required
                                    class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3.5 py-2.5 pr-10 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:ring-1 focus:ring-primary focus:border-primary">
                                <button
                                    type="button"
                                    onclick="togglePass('password', this)"
                                    class="absolute right-2.5 text-outline hover:text-on-surface p-1 focus:outline-none">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block font-label-md text-label-md text-on-surface">
                                Confirmar Contraseña <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="••••••••••••"
                                    required
                                    class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3.5 py-2.5 pr-10 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:ring-1 focus:ring-primary focus:border-primary">
                                <button
                                    type="button"
                                    onclick="togglePass('password_confirmation', this)"
                                    class="absolute right-2.5 text-outline hover:text-on-surface p-1 focus:outline-none">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Nota de Seguridad --}}
                    <div class="p-3.5 rounded-lg bg-surface-container-high/60 border border-outline-variant/30 text-outline text-xs flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">info</span>
                        <span>
                            La creación de cuentas se audita internamente. El operador creado podrá cambiar su contraseña y activar verificación en dos pasos (2FA) desde su propio perfil tras iniciar sesión.
                        </span>
                    </div>

                    {{-- Botón de envío --}}
                    <div class="pt-2 flex justify-end">
                        <button
                            type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container transition-colors font-headline-sm text-headline-sm font-medium shadow-md cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                            <span>Registrar Operador</span>
                        </button>
                    </div>

                </form>
            </div>

            {{-- COLUMNA DERECHA: Operadores Registrados Recientemente --}}
            <div class="bg-surface-container rounded-2xl border border-outline-variant/30 p-6 flex flex-col gap-4 shadow-card">
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">badge</span>
                        <span>Operadores en el Sistema</span>
                    </h3>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">
                        Últimos usuarios autorizados para supervisión.
                    </p>
                </div>

                <div class="divide-y divide-outline-variant/20 overflow-hidden">
                    @forelse ($operadores ?? [] as $op)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-primary text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($op->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="font-body-md text-body-md text-on-surface font-medium truncate block">{{ $op->name }}</span>
                                    <span class="font-code-sm text-[11px] text-outline truncate block">{{ $op->email }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-code-sm uppercase tracking-wider shrink-0
                                {{ $op->access_level === 'N3' ? 'bg-primary/10 text-primary border border-primary/20' : ($op->access_level === 'N2' ? 'bg-secondary/10 text-secondary border border-secondary/20' : 'bg-surface-container-highest text-outline') }}">
                                {{ $op->access_level ?? 'N2' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-outline text-xs py-4 text-center">No hay otros operadores registrados.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- Script para alternar visibilidad de contraseña --}}
    <script>
        function togglePass(id, btn) {
            const el = document.getElementById(id);
            const icon = btn.querySelector('.material-symbols-outlined');
            if (!el || !icon) return;
            if (el.type === 'password') {
                el.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                el.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</x-app-layout>

