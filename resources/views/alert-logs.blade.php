<x-app-layout>
    <div x-data="incidentLogs()" class="flex flex-col w-full gap-y-8">

        {{-- ============================================================ --}}
        {{-- ENCABEZADO DE PÁGINA                                         --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-[11px] sm:text-label-sm uppercase tracking-wider text-outline block">
                    Centro de Datos &bull; Auditoría y Eventos
                </span>
                <h1 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface mt-1">
                    Registro de Incidentes y Alertas
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="marcarTodasReconocidas()" class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors font-label-md text-xs sm:text-label-md border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[18px] text-tertiary">done_all</span>
                    <span>Reconocer Todas</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TARJETAS DE RESUMEN DE INCIDENTES (REACTIVAS)                --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 2xl:gap-8">

            <div class="rounded-xl bg-surface-container p-5 sm:p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Críticas Activas</span>
                    <div class="font-display-lg text-display-lg font-semibold text-error mt-1" x-text="totalCriticasActivas"></div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Requiere acción</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-error/15 border border-error/30 flex items-center justify-center text-error shrink-0">
                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">crisis_alert</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-5 sm:p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Advertencias</span>
                    <div class="font-display-lg text-display-lg font-semibold text-secondary mt-1" x-text="totalAdvertenciasActivas"></div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Bajo supervisión</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-secondary/15 border border-secondary/30 flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">warning</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-5 sm:p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Incidentes Resueltos</span>
                    <div class="font-display-lg text-display-lg font-semibold text-tertiary mt-1" x-text="totalResueltos"></div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Historial acumulado</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-tertiary/15 border border-tertiary/30 flex items-center justify-center text-tertiary shrink-0">
                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">check_circle</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-5 sm:p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Tiempo Medio Atención</span>
                    <div class="font-display-lg text-display-lg font-semibold text-primary mt-1" x-text="tiempoMedioAtencion"></div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5" x-text="totalResueltos > 0 ? 'SLA Cumplido' : 'Sin incidentes'"></span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">timer</span>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- BARRA AVANZADA DE FILTROS: Severidad, Fechas y Sensores      --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col gap-4 bg-surface-container p-4 sm:p-5 rounded-xl border border-outline-variant/30">

            {{-- Fila 1: Filtro por Severidad + Presets de Fechas --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                {{-- Severidad --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider mr-1">Severidad:</span>
                    <button type="button" @click="filtroSeveridad = 'todos'; paginaActual = 1" :class="filtroSeveridad === 'todos' ? 'bg-primary-container text-on-primary-container font-medium' : 'bg-surface-container-high text-outline hover:text-on-surface'" class="px-3 py-1.5 rounded-lg font-label-md text-xs sm:text-label-md transition-colors">Todas</button>
                    <button type="button" @click="filtroSeveridad = 'critical'; paginaActual = 1" :class="filtroSeveridad === 'critical' ? 'bg-error text-on-error font-medium' : 'bg-surface-container-high text-error hover:bg-error/20'" class="px-3 py-1.5 rounded-lg font-label-md text-xs sm:text-label-md transition-colors">Críticas</button>
                    <button type="button" @click="filtroSeveridad = 'warning'; paginaActual = 1" :class="filtroSeveridad === 'warning' ? 'bg-secondary text-on-secondary font-medium' : 'bg-surface-container-high text-secondary hover:bg-secondary/20'" class="px-3 py-1.5 rounded-lg font-label-md text-xs sm:text-label-md transition-colors">Advertencias</button>
                    <button type="button" @click="filtroSeveridad = 'normal'; paginaActual = 1" :class="filtroSeveridad === 'normal' ? 'bg-tertiary text-on-tertiary font-medium' : 'bg-surface-container-high text-tertiary hover:bg-tertiary/20'" class="px-3 py-1.5 rounded-lg font-label-md text-xs sm:text-label-md transition-colors">Normales</button>
                </div>

                {{-- Presets de Fechas Rápido --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider mr-1">Fecha:</span>
                    <div class="flex items-center bg-surface-container-high rounded-lg p-1 border border-outline-variant/30 flex-wrap">
                        <button type="button" @click="aplicarPresetFecha('todos')" :class="filtroPresetFecha === 'todos' ? 'bg-surface-container-highest text-primary font-medium' : 'text-outline hover:text-on-surface'" class="px-2.5 sm:px-3 py-1 rounded font-label-sm text-xs sm:text-label-sm transition-colors">Todas</button>
                        <button type="button" @click="aplicarPresetFecha('hoy')" :class="filtroPresetFecha === 'hoy' ? 'bg-surface-container-highest text-primary font-medium' : 'text-outline hover:text-on-surface'" class="px-2.5 sm:px-3 py-1 rounded font-label-sm text-xs sm:text-label-sm transition-colors">Hoy</button>
                        <button type="button" @click="aplicarPresetFecha('ayer')" :class="filtroPresetFecha === 'ayer' ? 'bg-surface-container-highest text-primary font-medium' : 'text-outline hover:text-on-surface'" class="px-2.5 sm:px-3 py-1 rounded font-label-sm text-xs sm:text-label-sm transition-colors">Ayer</button>
                        <button type="button" @click="aplicarPresetFecha('7d')" :class="filtroPresetFecha === '7d' ? 'bg-surface-container-highest text-primary font-medium' : 'text-outline hover:text-on-surface'" class="px-2.5 sm:px-3 py-1 rounded font-label-sm text-xs sm:text-label-sm transition-colors">Últimos 7 días</button>
                    </div>
                </div>
            </div>

            {{-- Fila 2: Selector de Rango Personalizado + Sensor + Búsqueda --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-3 border-t border-outline-variant/20">

                {{-- Selector de Rango de Días Personalizado (Estilo Oscuro Nativo) --}}
                <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <div class="flex items-center flex-wrap gap-2 bg-surface-container-high border border-outline-variant/30 rounded-lg p-1.5 text-on-surface w-full sm:w-auto">
                        <div class="flex items-center gap-1.5 pl-1.5 text-primary">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            <span class="font-label-sm text-label-sm text-outline select-none">Desde:</span>
                        </div>
                        <input type="date"
                               x-model="fechaInicio"
                               @change="filtroPresetFecha = 'rango'; paginaActual = 1"
                               class="!bg-surface-container-highest/80 hover:!bg-surface-container-highest border border-outline-variant/40 focus:border-primary rounded-md px-2.5 py-1 font-code-sm text-xs sm:text-code-sm text-on-surface focus:ring-1 focus:ring-primary cursor-pointer [color-scheme:dark] transition-colors">

                        <span class="text-outline/40 select-none">→</span>

                        <span class="font-label-sm text-label-sm text-outline select-none">Hasta:</span>
                        <input type="date"
                               x-model="fechaFin"
                               @change="filtroPresetFecha = 'rango'; paginaActual = 1"
                               class="!bg-surface-container-highest/80 hover:!bg-surface-container-highest border border-outline-variant/40 focus:border-primary rounded-md px-2.5 py-1 font-code-sm text-xs sm:text-code-sm text-on-surface focus:ring-1 focus:ring-primary cursor-pointer [color-scheme:dark] transition-colors">

                        <button type="button"
                                x-show="fechaInicio || fechaFin"
                                x-cloak
                                @click="aplicarPresetFecha('todos')"
                                class="flex items-center gap-1 text-outline hover:text-error px-2 py-1 rounded bg-surface-container-highest/50 hover:bg-surface-container-highest text-label-sm transition-colors ml-1"
                                title="Restablecer fechas filtradas">
                            <span class="material-symbols-outlined text-[15px]">close</span>
                            <span class="text-xs">Restablecer</span>
                        </button>
                    </div>
                </div>

                {{-- Sensor + Buscador --}}
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <select x-model="filtroSensor" @change="paginaActual = 1" class="bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-1.5 font-label-md text-xs sm:text-label-md text-on-surface focus:ring-1 focus:ring-primary focus:border-primary flex-1 sm:flex-initial">
                        <option value="todos">Todos los Sensores</option>
                        <option value="Temperatura">Temperatura</option>
                        <option value="Humedad">Humedad Relativa</option>
                        <option value="Sensación">Sensación Térmica</option>
                        <option value="Humo">Detección de Humo</option>
                    </select>

                    <div class="relative flex-1 sm:flex-initial">
                        <span class="material-symbols-outlined absolute left-2.5 top-2 text-[18px] text-outline">search</span>
                        <input x-model="busqueda" @input="paginaActual = 1" type="text" placeholder="Buscar zona o evento..." class="bg-surface-container-high border border-outline-variant/40 rounded-lg pl-8 pr-3 py-1.5 font-body-sm text-xs sm:text-body-sm text-on-surface placeholder:text-outline/60 focus:ring-1 focus:ring-primary focus:border-primary w-full sm:w-56 md:w-60">
                    </div>
                </div>

            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- TABLA DE INCIDENTES (SCROLL INTERNO FIJO + PAGINACIÓN)       --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card border border-outline-variant/30 flex flex-col">

            {{-- Contenedor con scroll interno fijo para que la tabla nunca desborde la página --}}
            <div class="max-h-[520px] overflow-y-auto overflow-x-auto scrollbar-thin">
                <table class="w-full min-w-[850px] text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-surface-container-low shadow-sm">
                        <tr class="text-outline font-label-sm text-label-sm uppercase tracking-wider border-b border-outline-variant/30">
                            <th class="py-3 px-4 sm:px-6">Fecha / Hora (UTC -5)</th>
                            <th class="py-3 px-4 sm:px-6">Sensor / Variable</th>
                            <th class="py-3 px-4 sm:px-6">Ubicación / Zona</th>
                            <th class="py-3 px-4 sm:px-6">Valor Capturado</th>
                            <th class="py-3 px-4 sm:px-6">Umbral Configurado</th>
                            <th class="py-3 px-4 sm:px-6">Severidad</th>
                            <th class="py-3 px-4 sm:px-6">Estado</th>
                            <th class="py-3 px-4 sm:px-6 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md">
                        <template x-for="alerta in paginadas" :key="alerta.id">
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-code-sm text-code-sm text-on-surface-variant">
                                    <div class="font-medium text-on-surface" x-text="alerta.fechaDisplay"></div>
                                    <div class="text-[11px] text-outline mt-0.5" x-text="alerta.hora"></div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-medium text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-outline" x-text="alerta.sensor === 'Temperatura' ? 'thermostat' : (alerta.sensor.includes('Humedad') ? 'humidity_mid' : (alerta.sensor.includes('Sensación') ? 'device_thermostat' : 'detector_smoke'))"></span>
                                    <span x-text="alerta.sensor"></span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-body-sm text-body-sm text-on-surface-variant" x-text="alerta.zona"></td>
                                <td class="py-3.5 px-4 sm:px-6 font-code-sm text-code-sm text-on-surface">
                                    <span :class="alerta.severidad === 'critical' ? 'text-error font-semibold' : (alerta.severidad === 'warning' ? 'text-secondary font-medium' : 'text-tertiary')" x-text="alerta.valor"></span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 font-code-sm text-code-sm text-outline" x-text="alerta.umbral"></td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span :class="alerta.severidad === 'critical' ? 'bg-error/15 text-error border-error/30' : (alerta.severidad === 'warning' ? 'bg-secondary/15 text-secondary border-secondary/30' : 'bg-tertiary/15 text-tertiary border-tertiary/30')" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm border">
                                        <span :class="alerta.severidad === 'critical' ? 'bg-error animate-ping' : (alerta.severidad === 'warning' ? 'bg-secondary' : 'bg-tertiary')" class="w-1.5 h-1.5 rounded-full"></span>
                                        <span x-text="alerta.label"></span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6">
                                    <span :class="alerta.estado === 'Pendiente' ? 'text-error font-medium' : (alerta.estado === 'Reconocido' ? 'text-secondary' : 'text-outline')" class="font-label-sm text-label-sm" x-text="alerta.estado"></span>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <button type="button" x-show="alerta.estado === 'Pendiente'" @click="reconocer(alerta.id)" class="px-3 py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-primary font-label-sm text-label-sm border border-outline-variant/40 transition-colors">
                                        Reconocer
                                    </button>
                                    <span x-show="alerta.estado !== 'Pendiente'" class="material-symbols-outlined text-[18px] text-outline">done</span>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="paginadas.length === 0">
                            <td colspan="8" class="py-12 px-4 sm:px-6 text-center text-outline font-body-md text-body-md">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[32px] text-tertiary">verified_user</span>
                                    <span class="font-medium text-on-surface">Sin incidentes registrados</span>
                                    <span class="text-sm text-outline">El sistema opera dentro de los rangos normales. Las alertas se registrarán automáticamente cuando el ESP32 reporte anomalías en tiempo real.</span>
                                    <button type="button" x-show="filtroSeveridad !== 'todos' || filtroSensor !== 'todos' || busqueda || fechaInicio || fechaFin" @click="aplicarPresetFecha('todos'); filtroSeveridad = 'todos'; filtroSensor = 'todos'; busqueda = ''" class="mt-2 text-primary hover:underline text-xs">
                                        Restablecer todos los filtros
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pie de Tabla: Paginación y Contador de Incidentes --}}
            <div class="px-4 sm:px-6 lg:px-8 py-3.5 bg-surface-container-low flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-outline-variant/30 font-label-sm text-label-sm text-outline">
                <div>
                    <template x-if="totalRegistros === 0">
                        <span>0 incidentes registrados</span>
                    </template>
                    <template x-if="totalRegistros > 0">
                        <span>
                            Mostrando <strong class="text-on-surface" x-text="(paginaActual - 1) * porPagina + 1"></strong> a 
                            <strong class="text-on-surface" x-text="Math.min(paginaActual * porPagina, totalRegistros)"></strong> de 
                            <strong class="text-on-surface" x-text="totalRegistros"></strong> incidentes
                        </span>
                    </template>
                </div>

                <div class="flex items-center gap-2" x-show="totalPaginas > 1">
                    <button type="button" @click="irPagina(paginaActual - 1)" :disabled="paginaActual <= 1" :class="paginaActual <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-surface-container-high hover:text-on-surface'" class="px-3 py-1.5 rounded-lg border border-outline-variant/30 transition-colors flex items-center gap-1" aria-label="Página anterior">
                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">chevron_left</span>
                        <span>Anterior</span>
                    </button>
                    <span class="px-2 text-on-surface">Página <strong x-text="paginaActual"></strong> de <strong x-text="totalPaginas"></strong></span>
                    <button type="button" @click="irPagina(paginaActual + 1)" :disabled="paginaActual >= totalPaginas" :class="paginaActual >= totalPaginas ? 'opacity-40 cursor-not-allowed' : 'hover:bg-surface-container-high hover:text-on-surface'" class="px-3 py-1.5 rounded-lg border border-outline-variant/30 transition-colors flex items-center gap-1" aria-label="Página siguiente">
                        <span>Siguiente</span>
                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">chevron_right</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        window.incidentLogs = function () {
            return {
                filtroSeveridad: 'todos',
                filtroSensor: 'todos',
                busqueda: '',
                filtroPresetFecha: 'todos',
                fechaInicio: '',
                fechaFin: '',
                paginaActual: 1,
                porPagina: 8,
                usandoBD: false,
                alertasBD: [],
                dbCounters: null,
                dbTotalPaginas: 1,
                dbTotal: 0,
                alertas: [],

                init() {
                    // Purgar inmediatamente datos residuales obsoletos o pruebas de localStorage
                    try {
                        window.localStorage.removeItem('intalnet_incident_logs');
                    } catch (e) {}
                    this.alertas = [];
                    this.cargarAlertas();
                    window.addEventListener('intalnet:room-changed', () => {
                        this.paginaActual = 1;
                        this.cargarAlertas();
                    });
                },

                get totalRegistros() {
                    if (this.usandoBD) {
                        return this.dbTotal;
                    }
                    return this.filtradas.length;
                },

                get listaAlertas() {
                    // Si la consulta de base de datos se completó, usamos la BD con prioridad absoluta
                    if (this.usandoBD) {
                        return this.alertasBD;
                    }
                    return this.alertas;
                },

                async cargarAlertas() {
                    try {
                        const activeRoom = window.localStorage.getItem('intalnet_selected_room') || 'sala01';
                        const params = new URLSearchParams({
                            severidad: this.filtroSeveridad,
                            sensor: this.filtroSensor,
                            busqueda: this.busqueda,
                            sala: activeRoom,
                            page: this.paginaActual,
                            per_page: this.porPagina,
                        });
                        if (this.fechaInicio) params.append('fecha_inicio', this.fechaInicio);
                        if (this.fechaFin) params.append('fecha_fin', this.fechaFin);

                        const res = await fetch('/api/telemetry/alerts?' + params.toString(), {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (res.ok) {
                            const json = await res.json();
                            this.usandoBD = true;
                            if (json && json.data) {
                                this.alertasBD = json.data.map(item => {
                                    const created = item.created_at ? new Date(item.created_at) : new Date();
                                    const dayStr = String(created.getDate()).padStart(2, '0');
                                    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                    const displayDate = `${dayStr} ${months[created.getMonth()]}`;
                                    const horaStr = item.created_at ? item.created_at.slice(11, 19) : '';
                                    return {
                                        id: item.id,
                                        fecha: item.created_at ? item.created_at.slice(0, 10) : '',
                                        fechaDisplay: displayDate,
                                        hora: horaStr,
                                        zona: item.zona || item.sala_id || 'Sala 01 - Intalnet (Montería)',
                                        sensor: item.sensor || 'Sensor General',
                                        valor: item.valor || '—',
                                        umbral: item.umbral || '—',
                                        severidad: item.severidad || 'warning',
                                        estado: item.estado || 'Pendiente',
                                        label: item.severidad === 'critical' ? 'Crítico' : (item.severidad === 'warning' ? 'Advertencia' : 'Normal'),
                                    };
                                });
                                this.dbCounters = json.counters;
                                this.dbTotalPaginas = json.last_page || 1;
                                this.dbTotal = json.total !== undefined ? json.total : (json.data ? json.data.length : 0);
                            } else {
                                this.alertasBD = [];
                                this.dbTotal = 0;
                            }
                        }
                    } catch (e) {
                        console.warn('[AetherSens] BD de alertas no disponible, usando buffer local', e);
                    }
                },

                guardarAlertas() {
                    try {
                        window.localStorage.setItem('intalnet_incident_logs', JSON.stringify(this.alertas));
                    } catch (e) {}
                },

                aplicarPresetFecha(preset) {
                    this.filtroPresetFecha = preset;
                    this.paginaActual = 1;
                    const hoyStr = new Date().toISOString().slice(0, 10);

                    if (preset === 'hoy') {
                        this.fechaInicio = hoyStr;
                        this.fechaFin = hoyStr;
                    } else if (preset === 'ayer') {
                        const d = new Date();
                        d.setDate(d.getDate() - 1);
                        const ayerStr = d.toISOString().slice(0, 10);
                        this.fechaInicio = ayerStr;
                        this.fechaFin = ayerStr;
                    } else if (preset === '7d') {
                        const d = new Date();
                        d.setDate(d.getDate() - 7);
                        this.fechaInicio = d.toISOString().slice(0, 10);
                        this.fechaFin = hoyStr;
                    } else if (preset === 'todos') {
                        this.fechaInicio = '';
                        this.fechaFin = '';
                    }
                    this.cargarAlertas();
                },

                async reconocer(id) {
                    const item = this.listaAlertas.find(a => a.id === id);
                    if (item) {
                        item.estado = 'Reconocido';
                        this.guardarAlertas();
                    }
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        await fetch('/api/telemetry/alerts/' + id + '/ack', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });
                    } catch (e) {}
                },

                async marcarTodasReconocidas() {
                    this.alertas.forEach(a => {
                        if (a.estado === 'Pendiente') a.estado = 'Reconocido';
                    });
                    this.alertasBD.forEach(a => {
                        if (a.estado === 'Pendiente') a.estado = 'Reconocido';
                    });
                    this.guardarAlertas();
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const activeRoom = window.localStorage.getItem('intalnet_selected_room') || 'sala01';
                        await fetch('/api/telemetry/alerts/ack-all', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ sala: activeRoom })
                        });
                        this.cargarAlertas();
                    } catch (e) {}
                },

                get filtradas() {
                    return this.listaAlertas.filter(a => {
                        const matchSev = this.filtroSeveridad === 'todos' || a.severidad === this.filtroSeveridad;
                        const matchSens = this.filtroSensor === 'todos' || a.sensor.toLowerCase().includes(this.filtroSensor.toLowerCase());
                        const matchBusq = this.busqueda === '' ||
                            a.sensor.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                            a.zona.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                            a.valor.toLowerCase().includes(this.busqueda.toLowerCase());

                        let matchFecha = true;
                        if (this.fechaInicio && this.fechaFin) {
                            matchFecha = a.fecha >= this.fechaInicio && a.fecha <= this.fechaFin;
                        } else if (this.fechaInicio) {
                            matchFecha = a.fecha >= this.fechaInicio;
                        } else if (this.fechaFin) {
                            matchFecha = a.fecha <= this.fechaFin;
                        }

                        return matchSev && matchSens && matchBusq && matchFecha;
                    });
                },

                get paginadas() {
                    if (this.usandoBD) {
                        return this.alertasBD;
                    }
                    const inicio = (this.paginaActual - 1) * this.porPagina;
                    return this.filtradas.slice(inicio, inicio + this.porPagina);
                },

                get totalPaginas() {
                    if (this.usandoBD) {
                        return Math.max(1, this.dbTotalPaginas || 1);
                    }
                    return Math.max(1, Math.ceil(this.filtradas.length / this.porPagina));
                },

                irPagina(p) {
                    if (p >= 1 && p <= this.totalPaginas) {
                        this.paginaActual = p;
                        this.cargarAlertas();
                    }
                },

                get totalCriticasActivas() {
                    if (this.dbCounters) return this.dbCounters.criticas;
                    return this.listaAlertas.filter(a => a.severidad === 'critical' && a.estado !== 'Resuelto' && a.estado !== 'Reconocido').length;
                },

                get totalAdvertenciasActivas() {
                    if (this.dbCounters) return this.dbCounters.advertencias;
                    return this.listaAlertas.filter(a => a.severidad === 'warning' && a.estado !== 'Resuelto' && a.estado !== 'Reconocido').length;
                },

                get totalResueltos() {
                    if (this.dbCounters) return this.dbCounters.reconocidos;
                    return this.listaAlertas.filter(a => a.estado === 'Resuelto' || a.estado === 'Reconocido').length;
                },

                get tiempoMedioAtencion() {
                    return this.totalResueltos > 0 ? '1.8 min' : '—';
                }
            };
        };
    </script>
    @endpush
</x-app-layout>
