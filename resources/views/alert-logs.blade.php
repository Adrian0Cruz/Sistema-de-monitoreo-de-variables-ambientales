<x-app-layout>
    <div x-data="{
        filtroSeveridad: 'todos',
        filtroSensor: 'todos',
        busqueda: '',
        alertas: [
            { id: 1, hora: '18:14:22', sensor: 'Temperatura', zona: 'Rack A-04 (Escape)', valor: '27.4 °C', umbral: '> 26.0 °C', severidad: 'critical', estado: 'Pendiente', label: 'Crítico' },
            { id: 2, hora: '17:42:05', sensor: 'Humedad Relativa', zona: 'Sala General Racks', valor: '58.2 %', umbral: '> 55.0 %', severidad: 'warning', estado: 'Reconocido', label: 'Advertencia' },
            { id: 3, hora: '16:10:18', sensor: 'Temperatura', zona: 'Rack B-02', valor: '25.3 °C', umbral: '> 24.0 °C', severidad: 'warning', estado: 'Resuelto', label: 'Advertencia' },
            { id: 4, hora: '14:20:00', sensor: 'Detección de Humo', zona: 'Sector Norte', valor: '0 PPM', umbral: '> 50 PPM', severidad: 'normal', estado: 'Normal', label: 'Seguro' },
            { id: 5, hora: '12:05:44', sensor: 'Temperatura', zona: 'Inflow Climatizador', valor: '17.8 °C', umbral: '< 18.0 °C', severidad: 'warning', estado: 'Resuelto', label: 'Advertencia' },
            { id: 6, hora: '09:30:12', sensor: 'Humedad Relativa', zona: 'Sala General Racks', valor: '44.8 %', umbral: 'Rango Óptimo', severidad: 'normal', estado: 'Normal', label: 'Normal' },
            { id: 7, hora: '04:15:33', sensor: 'Sensación Térmica', zona: 'Rack A-01', valor: '21.5 °C', umbral: 'Rango Óptimo', severidad: 'normal', estado: 'Normal', label: 'Normal' },
        ],
        reconocer(id) {
            const item = this.alertas.find(a => a.id === id);
            if (item) item.estado = 'Reconocido';
        },
        marcarTodasReconocidas() {
            this.alertas.forEach(a => {
                if (a.estado === 'Pendiente') a.estado = 'Reconocido';
            });
        },
        get filtradas() {
            return this.alertas.filter(a => {
                const matchSev = this.filtroSeveridad === 'todos' || a.severidad === this.filtroSeveridad;
                const matchSens = this.filtroSensor === 'todos' || a.sensor.toLowerCase().includes(this.filtroSensor.toLowerCase());
                const matchBusq = this.busqueda === '' || 
                    a.sensor.toLowerCase().includes(this.busqueda.toLowerCase()) || 
                    a.zona.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                    a.valor.toLowerCase().includes(this.busqueda.toLowerCase());
                return matchSev && matchSens && matchBusq;
            });
        }
    }" class="flex flex-col w-full gap-y-8">

        {{-- ============================================================ --}}
        {{-- PAGE HEADER: Incident Monitor + Actions                      --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    Centro de Datos &bull; Auditoría y Eventos
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Registro de Incidentes y Alertas
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <button @click="marcarTodasReconocidas()" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors font-label-md text-label-md border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[18px] text-tertiary">done_all</span>
                    <span>Reconocer Todas</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- METRICS SUMMARY TILES                                        --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Críticas Activas</span>
                    <div class="font-display-lg text-display-lg font-semibold text-error mt-1">1</div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Requiere acción</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-error/15 border border-error/30 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-[24px]">crisis_alert</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Advertencias</span>
                    <div class="font-display-lg text-display-lg font-semibold text-secondary mt-1">2</div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Bajo supervisión</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-secondary/15 border border-secondary/30 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[24px]">warning</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Incidentes Resueltos</span>
                    <div class="font-display-lg text-display-lg font-semibold text-tertiary mt-1">14</div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">Hoy (Últimas 24h)</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-tertiary/15 border border-tertiary/30 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined text-[24px]">check_circle</span>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex items-center justify-between">
                <div>
                    <span class="font-label-sm text-label-sm uppercase text-outline">Tiempo Medio Atención</span>
                    <div class="font-display-lg text-display-lg font-semibold text-primary mt-1">2.4 <span class="text-headline-md text-outline">min</span></div>
                    <span class="font-body-sm text-body-sm text-outline mt-0.5">SLA Cumplido (99.8%)</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">timer</span>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- FILTERS BAR                                                  --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-surface-container p-4 rounded-xl border border-outline-variant/30">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider mr-2">Severidad:</span>
                <button @click="filtroSeveridad = 'todos'" :class="filtroSeveridad === 'todos' ? 'bg-primary-container text-on-primary-container font-medium' : 'bg-surface-container-high text-outline hover:text-on-surface'" class="px-3 py-1.5 rounded-lg font-label-md text-label-md transition-colors">Todas</button>
                <button @click="filtroSeveridad = 'critical'" :class="filtroSeveridad === 'critical' ? 'bg-error text-on-error font-medium' : 'bg-surface-container-high text-error hover:bg-error/20'" class="px-3 py-1.5 rounded-lg font-label-md text-label-md transition-colors">Críticas</button>
                <button @click="filtroSeveridad = 'warning'" :class="filtroSeveridad === 'warning' ? 'bg-secondary text-on-secondary font-medium' : 'bg-surface-container-high text-secondary hover:bg-secondary/20'" class="px-3 py-1.5 rounded-lg font-label-md text-label-md transition-colors">Advertencias</button>
                <button @click="filtroSeveridad = 'normal'" :class="filtroSeveridad === 'normal' ? 'bg-tertiary text-on-tertiary font-medium' : 'bg-surface-container-high text-tertiary hover:bg-tertiary/20'" class="px-3 py-1.5 rounded-lg font-label-md text-label-md transition-colors">Normales</button>
            </div>

            <div class="flex items-center gap-3">
                {{-- Sensor Filter --}}
                <select x-model="filtroSensor" class="bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-1.5 font-label-md text-label-md text-on-surface focus:ring-1 focus:ring-primary focus:border-primary">
                    <option value="todos">Todos los Sensores</option>
                    <option value="Temperatura">Temperatura</option>
                    <option value="Humedad">Humedad</option>
                    <option value="Humo">Sensor de Humo</option>
                </select>

                {{-- Search Box --}}
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-2.5 top-2 text-[18px] text-outline">search</span>
                    <input x-model="busqueda" type="text" placeholder="Buscar zona o evento..." class="bg-surface-container-high border border-outline-variant/40 rounded-lg pl-8 pr-3 py-1.5 font-body-sm text-body-sm text-on-surface placeholder:text-outline/60 focus:ring-1 focus:ring-primary focus:border-primary w-48 sm:w-64">
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ALERTS LOG TABLE                                             --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card border border-outline-variant/30">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low text-outline font-label-sm text-label-sm uppercase tracking-wider border-b border-outline-variant/30">
                            <th class="py-3.5 px-8">Hora (UTC -5)</th>
                            <th class="py-3.5 px-8">Sensor / Variable</th>
                            <th class="py-3.5 px-8">Ubicación / Zona</th>
                            <th class="py-3.5 px-8">Valor Capturado</th>
                            <th class="py-3.5 px-8">Umbral Configurado</th>
                            <th class="py-3.5 px-8">Severidad</th>
                            <th class="py-3.5 px-8">Estado</th>
                            <th class="py-3.5 px-8 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md">
                        <template x-for="alerta in filtradas" :key="alerta.id">
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface-variant" x-text="alerta.hora"></td>
                                <td class="py-4 px-8 font-medium text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-outline" x-text="alerta.sensor === 'Temperatura' ? 'thermostat' : (alerta.sensor.includes('Humedad') ? 'humidity_mid' : 'detector_smoke')"></span>
                                    <span x-text="alerta.sensor"></span>
                                </td>
                                <td class="py-4 px-8 font-body-sm text-body-sm text-on-surface-variant" x-text="alerta.zona"></td>
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                    <span :class="alerta.severidad === 'critical' ? 'text-error font-semibold' : (alerta.severidad === 'warning' ? 'text-secondary font-medium' : 'text-tertiary')" x-text="alerta.valor"></span>
                                </td>
                                <td class="py-4 px-8 font-code-sm text-code-sm text-outline" x-text="alerta.umbral"></td>
                                <td class="py-4 px-8">
                                    <span :class="alerta.severidad === 'critical' ? 'bg-error/15 text-error border-error/30' : (alerta.severidad === 'warning' ? 'bg-secondary/15 text-secondary border-secondary/30' : 'bg-tertiary/15 text-tertiary border-tertiary/30')" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm border">
                                        <span :class="alerta.severidad === 'critical' ? 'bg-error animate-ping' : (alerta.severidad === 'warning' ? 'bg-secondary' : 'bg-tertiary')" class="w-1.5 h-1.5 rounded-full"></span>
                                        <span x-text="alerta.label"></span>
                                    </span>
                                </td>
                                <td class="py-4 px-8">
                                    <span :class="alerta.estado === 'Pendiente' ? 'text-error font-medium' : (alerta.estado === 'Reconocido' ? 'text-secondary' : 'text-outline')" class="font-label-sm text-label-sm" x-text="alerta.estado"></span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <button x-show="alerta.estado === 'Pendiente'" @click="reconocer(alerta.id)" class="px-3 py-1 rounded bg-surface-container-high hover:bg-surface-container-highest text-primary font-label-sm text-label-sm border border-outline-variant/40 transition-colors">
                                        Reconocer
                                    </button>
                                    <span x-show="alerta.estado !== 'Pendiente'" class="material-symbols-outlined text-[18px] text-outline">done</span>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filtradas.length === 0">
                            <td colspan="8" class="py-8 px-8 text-center text-outline font-body-md text-body-md">
                                No se encontraron registros que coincidan con los filtros seleccionados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

