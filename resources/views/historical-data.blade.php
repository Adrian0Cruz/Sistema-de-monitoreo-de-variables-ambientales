<x-app-layout>
    <div class="flex flex-col w-full gap-y-6 sm:gap-y-8">

        {{-- ============================================================ --}}
        {{-- ENCABEZADO DE PÁGINA: Analítica Histórica + Selector Rango    --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-[11px] sm:text-label-sm uppercase tracking-wider text-outline block">
                    {{ $settings['zona'] ?? 'Sala 01 - Intalnet (Montería)' }}
                </span>
                <h1 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface mt-1">
                    Datos Históricos y Analítica
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- Selector de Rango (24 Horas / 7 Días / 30 Días) --}}
                <div class="flex items-center bg-surface-container rounded-lg p-1 border border-outline-variant/30" id="range-selector">
                    <button type="button" data-range="24h" class="range-btn px-2.5 sm:px-3.5 py-1.5 rounded font-label-md text-xs sm:text-label-md bg-surface-container-high text-primary font-medium transition-colors">
                        24 Horas
                    </button>
                    <button type="button" data-range="7d" class="range-btn px-2.5 sm:px-3.5 py-1.5 rounded font-label-md text-xs sm:text-label-md text-outline hover:text-on-surface transition-colors">
                        7 Días
                    </button>
                    <button type="button" data-range="30d" class="range-btn px-2.5 sm:px-3.5 py-1.5 rounded font-label-md text-xs sm:text-label-md text-outline hover:text-on-surface transition-colors">
                        30 Días
                    </button>
                </div>

                {{-- Botón Exportar CSV --}}
                <button type="button" onclick="exportToCsv()" class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors font-label-md text-xs sm:text-label-md border border-outline-variant/40">
                    <span class="material-symbols-outlined text-[18px] text-primary">download</span>
                    <span>Exportar CSV</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TARJETAS KPI CON PROMEDIOS Y NIVELES (5 TARJETAS AJUSTADAS)  --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6 2xl:gap-8">

            {{-- Stat 1: Temperatura Media --}}
            <div class="rounded-xl bg-surface-container p-4 sm:p-5 2xl:p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">thermostat</span>
                        </div>
                        <span id="kpi-temp-badge" class="px-2.5 py-0.5 rounded-full bg-surface-container-highest text-outline font-label-sm text-label-sm border border-outline-variant/30 whitespace-nowrap">
                            En espera
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-sm sm:text-headline-sm text-on-surface font-medium mb-3">
                        Temperatura Media
                    </h3>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span id="kpi-temp-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                        <span class="font-headline-md text-headline-md text-outline">°C</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Mín: <strong id="kpi-temp-min" class="text-on-surface">—</strong></span>
                        <span>Máx: <strong id="kpi-temp-max" class="text-on-surface">—</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 2: Sensación Térmica Media --}}
            <div class="rounded-xl bg-surface-container p-4 sm:p-5 2xl:p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-fixed">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">device_thermostat</span>
                        </div>
                        <span id="kpi-heat-badge" class="px-2.5 py-0.5 rounded-full bg-surface-container-highest text-outline font-label-sm text-label-sm border border-outline-variant/30 whitespace-nowrap">
                            En espera
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-sm sm:text-headline-sm text-on-surface font-medium mb-3">
                        Sensación Térmica
                    </h3>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span id="kpi-heat-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                        <span class="font-headline-md text-headline-md text-outline">°C</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Índice Confort: <strong id="kpi-heat-index" class="text-outline">—</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 3: Humedad Media --}}
            <div class="rounded-xl bg-surface-container p-4 sm:p-5 2xl:p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">humidity_mid</span>
                        </div>
                        <span id="kpi-hum-badge" class="px-2.5 py-0.5 rounded-full bg-surface-container-highest text-outline font-label-sm text-label-sm border border-outline-variant/30 whitespace-nowrap">
                            En espera
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-sm sm:text-headline-sm text-on-surface font-medium mb-3">
                        Humedad Media
                    </h3>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span id="kpi-hum-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                        <span class="font-headline-md text-headline-md text-outline">% RH</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Mín: <strong id="kpi-hum-min" class="text-on-surface">—</strong></span>
                        <span>Máx: <strong id="kpi-hum-max" class="text-on-surface">—</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 4: Nivel de Gas Promedio (MQ-2) --}}
            <div class="rounded-xl bg-surface-container p-5 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined text-[20px]">detector_smoke</span>
                        </div>
                        <span id="kpi-gas-badge" class="px-2.5 py-0.5 rounded-full bg-surface-container-highest text-outline font-label-sm text-label-sm border border-outline-variant/30 whitespace-nowrap">
                            En espera
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-3">
                        Gas Promedio
                    </h3>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span id="kpi-gas-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                        <span class="font-headline-md text-headline-md text-outline">ADC</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Mín: <strong id="kpi-gas-min" class="text-on-surface">—</strong></span>
                        <span>Máx: <strong id="kpi-gas-max" class="text-on-surface">—</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 5: Cumplimiento de Umbral --}}
            <div class="rounded-xl bg-surface-container p-5 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <span id="kpi-compliance-badge" class="px-2.5 py-0.5 rounded-full bg-surface-container-highest text-outline font-label-sm text-label-sm border border-outline-variant/30 whitespace-nowrap">
                            En espera
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-medium mb-3">
                        Cumplimiento
                    </h3>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span id="kpi-compliance-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                        <span class="font-headline-md text-headline-md text-outline">%</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Tiempo seguro: <strong id="kpi-compliance-time" class="text-on-surface">—</strong></span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- GRÁFICA DE EVOLUCIÓN HISTÓRICA                               --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-4 sm:p-6 lg:p-8 shadow-card border border-outline-variant/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Evolución de Tendencia Histórica</h2>
                    <p id="chart-subtitle" class="font-body-sm text-body-sm text-outline mt-0.5">Perfil de Temperatura y Humedad durante las últimas 24 horas (UTC -5)</p>
                </div>

                <div class="flex flex-wrap items-center gap-3 sm:gap-6">
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-primary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Temperatura (°C)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-secondary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Humedad (% RH)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <div id="historical-evolution-chart" class="w-full min-h-[260px] sm:min-h-[300px]"></div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TABLA DE MUESTREOS REGISTRADOS (CON SCROLL INTERNO FIJO)    --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card border border-outline-variant/30">
            <div class="px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Muestreos Registrados</h2>
                    <p id="table-subtitle" class="font-body-sm text-body-sm text-outline mt-0.5">Historial cronológico de telemetría capturada en {{ $settings['zona'] ?? 'Sala 01 - Intalnet (Montería)' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="table-record-count" class="px-2.5 py-1 rounded-md bg-surface-container-high font-code-sm text-code-sm text-outline">12 registros</span>
                </div>
            </div>

            {{-- Contenedor con scroll interno fijo para no alargar la página ni requerir scroll global --}}
            <div class="max-h-80 overflow-y-auto overflow-x-auto divide-y divide-outline-variant/20 scrollbar-thin">
                <table class="w-full min-w-[760px] text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-surface-container-low shadow-sm">
                        <tr class="text-outline font-label-sm text-label-sm uppercase tracking-wider border-b border-outline-variant/30">
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Hora / Fecha (UTC -5)</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Zona</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Temperatura</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Sensación Térmica</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Humedad</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Gas (MQ-2)</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8 text-right">Estado Operativo</th>
                        </tr>
                    </thead>
                    <tbody id="historical-samples-body" class="divide-y divide-outline-variant/20 font-body-md text-body-md">
                        {{-- Contenido inyectado dinámicamente con fechas reales --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let chartInstance = null;
            let currentRange = '24h';

            const defaultZone = @json($settings['zona'] ?? 'Sala 01 - Intalnet (Montería)');
            const TELEMETRY_STORAGE_KEY = 'intalnet_telemetry_history';

            const getStoredSamples = () => {
                try {
                    const raw = window.localStorage.getItem(TELEMETRY_STORAGE_KEY);
                    return raw ? JSON.parse(raw) : [];
                } catch (e) {
                    return [];
                }
            };

            const filterSamplesByRange = (samples, rangeKey) => {
                const now = Date.now();
                const msMap = {
                    '24h': 24 * 3600 * 1000,
                    '7d': 7 * 24 * 3600 * 1000,
                    '30d': 30 * 24 * 3600 * 1000,
                };
                const maxAge = msMap[rangeKey] || msMap['24h'];
                return samples.filter(s => s && s.timestamp && (now - s.timestamp <= maxAge));
            };

            const setEl = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = val;
            };

            async function applyRange(rangeKey) {
                currentRange = rangeKey;

                // 1. Actualizar botones del selector
                document.querySelectorAll('.range-btn').forEach(btn => {
                    const active = btn.getAttribute('data-range') === rangeKey;
                    if (active) {
                        btn.className = 'range-btn px-3.5 py-1.5 rounded font-label-md text-label-md bg-surface-container-high text-primary font-medium transition-colors';
                    } else {
                        btn.className = 'range-btn px-3.5 py-1.5 rounded font-label-md text-label-md text-outline hover:text-on-surface transition-colors';
                    }
                });

                const subtitleEl = document.getElementById('chart-subtitle');
                const rangeNames = { '24h': 'las últimas 24 horas', '7d': 'los últimos 7 días', '30d': 'los últimos 30 días' };
                const activeRoom = window.localStorage.getItem('intalnet_selected_room') || 'sala01';

                // Intentar consultar backend optimizado con agregación SQL (sub-50ms)
                let dbData = null;
                try {
                    const res = await fetch(`/api/telemetry/history?range=${rangeKey}&sala=${activeRoom}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const json = await res.json();
                        if (json && json.total_samples > 0) {
                            dbData = json;
                        }
                    }
                } catch (e) {
                    console.warn('[AetherSens] Consulta de histórico en BD no disponible, usando buffer local', e);
                }

                if (dbData) {
                    if (subtitleEl) {
                        subtitleEl.textContent = `Consolidado de telemetría de BD durante ${rangeNames[rangeKey]} (UTC -5) · ${activeRoom.toUpperCase()}`;
                    }

                    const k = dbData.kpis;
                    setEl('kpi-temp-val', k.avg_temp ?? '—');
                    setEl('kpi-temp-min', k.min_temp != null ? `${k.min_temp} °C` : '—');
                    setEl('kpi-temp-max', k.max_temp != null ? `${k.max_temp} °C` : '—');
                    setEl('kpi-temp-badge', (k.avg_temp && k.avg_temp <= 27) ? 'Óptimo' : 'Elevado');

                    setEl('kpi-heat-val', k.avg_heat ?? '—');
                    setEl('kpi-heat-index', (k.avg_heat && k.avg_heat < 27) ? 'ASHRAE A1' : 'Alerta Térmica');
                    setEl('kpi-heat-badge', (k.avg_heat && k.avg_heat < 27) ? 'Nominal' : 'Bajo Observación');

                    setEl('kpi-hum-val', k.avg_hum ?? '—');
                    setEl('kpi-hum-min', k.min_hum != null ? `${k.min_hum} %` : '—');
                    setEl('kpi-hum-max', k.max_hum != null ? `${k.max_hum} %` : '—');
                    setEl('kpi-hum-badge', (k.avg_hum && k.avg_hum >= 40 && k.avg_hum <= 60) ? 'Normal' : 'Desviación');

                    setEl('kpi-gas-val', k.avg_gas != null ? String(k.avg_gas) : '—');
                    setEl('kpi-gas-min', k.min_gas != null ? `${k.min_gas} ADC` : '—');
                    setEl('kpi-gas-max', k.max_gas != null ? `${k.max_gas} ADC` : '—');
                    setEl('kpi-gas-badge', (k.avg_gas && k.avg_gas < 1800) ? 'Limpio' : 'Alerta Gas');

                    setEl('kpi-compliance-val', k.compliance_percent != null ? `${k.compliance_percent}%` : '—');
                    setEl('kpi-compliance-time', `${dbData.total_samples} muestras`);
                    setEl('kpi-compliance-badge', (k.compliance_percent && k.compliance_percent >= 95) ? 'Excelente' : 'Requiere Revisión');

                    if (chartInstance && dbData.chart) {
                        chartInstance.updateOptions({
                            xaxis: {
                                categories: dbData.chart.categories || []
                            },
                            series: [
                                { name: 'Temperatura', data: dbData.chart.temp || [] },
                                { name: 'Humedad', data: dbData.chart.hum || [] }
                            ]
                        }, true, true);
                    }

                    renderSamplesTable(dbData.samples || []);
                    window._lastHistoricalSamples = dbData.samples || [];
                    return;
                }

                // Fallback a localStorage si la BD no tiene muestras aún
                const allSamples = getStoredSamples();
                const samples = filterSamplesByRange(allSamples, rangeKey);
                window._lastHistoricalSamples = samples;

                if (samples.length === 0) {
                    // Estado vacío real cuando no hay mediciones
                    if (subtitleEl) {
                        subtitleEl.textContent = `Sin mediciones acumuladas para ${rangeNames[rangeKey]} (esperando telemetría del ESP32)`;
                    }

                    setEl('kpi-temp-val', '—');
                    setEl('kpi-temp-min', '—');
                    setEl('kpi-temp-max', '—');
                    setEl('kpi-temp-badge', 'En espera');

                    setEl('kpi-heat-val', '—');
                    setEl('kpi-heat-index', '—');
                    setEl('kpi-heat-badge', 'En espera');

                    setEl('kpi-hum-val', '—');
                    setEl('kpi-hum-min', '—');
                    setEl('kpi-hum-max', '—');
                    setEl('kpi-hum-badge', 'En espera');

                    setEl('kpi-gas-val', '—');
                    setEl('kpi-gas-min', '—');
                    setEl('kpi-gas-max', '—');
                    setEl('kpi-gas-badge', 'En espera');

                    setEl('kpi-compliance-val', '—');
                    setEl('kpi-compliance-time', '—');
                    setEl('kpi-compliance-badge', 'En espera');

                    if (chartInstance) {
                        chartInstance.updateOptions({
                            xaxis: { categories: [] },
                            series: [
                                { name: 'Temperatura', data: [] },
                                { name: 'Humedad', data: [] }
                            ]
                        }, true, true);
                    }

                    renderEmptyTable();
                    return;
                }

                // Cálculo con datos REALES reportados por el hardware
                if (subtitleEl) {
                    subtitleEl.textContent = `Consolidado de telemetría real durante ${rangeNames[rangeKey]} (UTC -5)`;
                }

                const temps = samples.map(s => Number(s.temp)).filter(Number.isFinite);
                const hums = samples.map(s => Number(s.hum)).filter(Number.isFinite);
                const heats = samples.map(s => Number(s.heat)).filter(Number.isFinite);
                const gases = samples.map(s => Number(s.gas)).filter(Number.isFinite);

                const avgTemp = (temps.reduce((a, b) => a + b, 0) / temps.length).toFixed(1);
                const minTemp = Math.min(...temps).toFixed(1) + ' °C';
                const maxTemp = Math.max(...temps).toFixed(1) + ' °C';

                const avgHum = (hums.reduce((a, b) => a + b, 0) / hums.length).toFixed(1);
                const minHum = Math.min(...hums).toFixed(1) + ' %';
                const maxHum = Math.max(...hums).toFixed(1) + ' %';

                const avgHeat = heats.length ? (heats.reduce((a, b) => a + b, 0) / heats.length).toFixed(1) : avgTemp;
                const avgGas = Math.round(gases.reduce((a, b) => a + b, 0) / gases.length);
                const minGas = Math.min(...gases) + ' ADC';
                const maxGas = Math.max(...gases) + ' ADC';

                const safeCount = samples.filter(s => s.status === 'normal').length;
                const compliancePercent = ((safeCount / samples.length) * 100).toFixed(1);

                setEl('kpi-temp-val', avgTemp);
                setEl('kpi-temp-min', minTemp);
                setEl('kpi-temp-max', maxTemp);
                setEl('kpi-temp-badge', avgTemp <= 27 ? 'Óptimo' : 'Elevado');

                setEl('kpi-heat-val', avgHeat);
                setEl('kpi-heat-index', avgHeat < 27 ? 'ASHRAE A1' : 'Alerta Térmica');
                setEl('kpi-heat-badge', avgHeat < 27 ? 'Nominal' : 'Bajo Observación');

                setEl('kpi-hum-val', avgHum);
                setEl('kpi-hum-min', minHum);
                setEl('kpi-hum-max', maxHum);
                setEl('kpi-hum-badge', (avgHum >= 40 && avgHum <= 60) ? 'Normal' : 'Desviación');

                setEl('kpi-gas-val', String(avgGas));
                setEl('kpi-gas-min', minGas);
                setEl('kpi-gas-max', maxGas);
                setEl('kpi-gas-badge', avgGas < 1800 ? 'Limpio' : 'Alerta Gas');

                setEl('kpi-compliance-val', compliancePercent);
                setEl('kpi-compliance-time', `${samples.length} muestras`);
                setEl('kpi-compliance-badge', compliancePercent >= 95 ? 'Excelente' : 'Requiere Revisión');

                // Limitar puntos para gráfica si hay muchas muestras (hasta 60 puntos)
                const step = Math.max(1, Math.floor(samples.length / 60));
                const chartSamples = samples.filter((_, idx) => idx % step === 0);

                if (chartInstance) {
                    chartInstance.updateOptions({
                        xaxis: {
                            categories: chartSamples.map(s => s.time || '')
                        },
                        series: [
                            { name: 'Temperatura', data: chartSamples.map(s => s.temp) },
                            { name: 'Humedad', data: chartSamples.map(s => s.hum) }
                        ]
                    }, true, true);
                }

                renderSamplesTable(samples.slice().reverse());
            }

            function renderEmptyTable() {
                const tbody = document.getElementById('historical-samples-body');
                const countEl = document.getElementById('table-record-count');
                if (!tbody) return;

                if (countEl) countEl.textContent = '0 registros';

                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="py-12 px-8 text-center text-outline font-body-md text-body-md">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[32px] text-outline">sensors_off</span>
                                <span class="font-medium text-on-surface">Esperando telemetría del ESP32</span>
                                <span class="text-xs text-outline max-w-md">Los datos históricos se consolidarán y graficarán automáticamente en cuanto el ESP32 comience a publicar mediciones reales en el broker MQTT.</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            function renderSamplesTable(samples) {
                const tbody = document.getElementById('historical-samples-body');
                const countEl = document.getElementById('table-record-count');
                if (!tbody) return;

                if (countEl) {
                    countEl.textContent = `${samples.length} registros`;
                }

                tbody.innerHTML = samples.map(item => {
                    const isWarning = item.status === 'warning';
                    const isCritical = item.status === 'critical';
                    const badgeClass = isCritical
                        ? 'bg-error/10 text-error border-error/20'
                        : (isWarning ? 'bg-secondary/10 text-secondary border-secondary/20' : 'bg-tertiary/10 text-tertiary border-tertiary/20');
                    const dotClass = isCritical ? 'bg-error' : (isWarning ? 'bg-secondary' : 'bg-tertiary');

                    return `
                        <tr class="hover:bg-surface-container-high/60 transition-colors">
                            <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface-variant">${item.time || '—'}</td>
                            <td class="py-4 px-8 font-medium text-on-surface">${item.zona || defaultZone}</td>
                            <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                <span class="text-primary font-medium">${item.temp}</span> <span class="text-outline">°C</span>
                            </td>
                            <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                <span class="text-primary-fixed">${item.heat || item.temp}</span> <span class="text-outline">°C</span>
                            </td>
                            <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                <span class="text-secondary font-medium">${item.hum}</span> <span class="text-outline">% RH</span>
                            </td>
                            <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                <span class="text-tertiary font-medium">${item.gas} ADC</span>
                            </td>
                            <td class="py-4 px-8 text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-sm text-label-sm border ${badgeClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${dotClass}"></span>
                                    <span>${item.label || 'Normal'}</span>
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            function initHistoricalChart() {
                const el = document.querySelector("#historical-evolution-chart");
                if (!el) return;
                if (typeof ApexCharts === 'undefined') {
                    setTimeout(initHistoricalChart, 150);
                    return;
                }

                const options = {
                    series: [
                        { name: 'Temperatura', data: [] },
                        { name: 'Humedad', data: [] }
                    ],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false },
                        background: 'transparent',
                        fontFamily: "'Geist', sans-serif",
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 500
                        },
                        noData: {
                            text: 'Esperando telemetría del ESP32...',
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: '#8b919d',
                                fontSize: '14px',
                                fontFamily: "'Geist', sans-serif"
                            }
                        }
                    },
                    colors: ['#a4c9ff', '#77d6d3'],
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: [2.5, 2] },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.25,
                            opacityTo: 0.02,
                            stops: [0, 95, 100]
                        }
                    },
                    grid: {
                        borderColor: '#414751',
                        strokeDashArray: 3,
                        opacity: 0.4
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                colors: '#8b919d',
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px'
                            }
                        },
                        axisBorder: { color: '#414751' },
                        axisTicks: { color: '#414751' }
                    },
                    yaxis: [
                        {
                            seriesName: 'Temperatura',
                            min: 15,
                            max: 35,
                            tickAmount: 4,
                            labels: {
                                style: { colors: '#a4c9ff', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                                formatter: (val) => val != null ? val.toFixed(1) + ' °C' : ''
                            },
                            title: {
                                text: 'Temperatura (°C)',
                                style: { color: '#a4c9ff', fontSize: '12px', fontWeight: 600 }
                            }
                        },
                        {
                            opposite: true,
                            seriesName: 'Humedad',
                            min: 20,
                            max: 100,
                            tickAmount: 4,
                            labels: {
                                style: { colors: '#77d6d3', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                                formatter: (val) => val != null ? Math.round(val) + ' %' : ''
                            },
                            title: {
                                text: 'Humedad (% RH)',
                                style: { color: '#77d6d3', fontSize: '12px', fontWeight: 600 }
                            }
                        }
                    ],
                    tooltip: {
                        theme: 'dark',
                        shared: true,
                        y: {
                            formatter: (val, { seriesIndex }) => val != null ? (seriesIndex === 0 ? val.toFixed(1) + ' °C' : val.toFixed(1) + ' % RH') : '--'
                        }
                    }
                };

                chartInstance = new ApexCharts(el, options);
                chartInstance.render();

                applyRange('24h');
            }

            document.querySelectorAll('.range-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const rangeKey = this.getAttribute('data-range');
                    applyRange(rangeKey);
                });
            });

            initHistoricalChart();

            window.addEventListener('intalnet:room-changed', function () {
                applyRange(currentRange);
            });

            window.exportToCsv = function () {
                const samples = (window._lastHistoricalSamples && window._lastHistoricalSamples.length)
                    ? window._lastHistoricalSamples
                    : filterSamplesByRange(getStoredSamples(), currentRange);

                if (!samples || samples.length === 0) {
                    alert('No hay registros de telemetría para exportar en este rango aún. Conecte el ESP32 para capturar datos.');
                    return;
                }

                const headers = ["Hora/Fecha (UTC-5)", "Zona", "Temperatura (C)", "Sensacion Termica (C)", "Humedad (%RH)", "Gas MQ-2", "Estado Operativo"];
                const rows = samples.map(s => [
                    `"${s.time || ''}"`,
                    `"${s.zona || defaultZone}"`,
                    s.temp,
                    s.heat,
                    s.hum,
                    `"${s.gas} ADC"`,
                    `"${s.label || 'Normal'}"`
                ]);

                const csvContent = "data:text/csv;charset=utf-8," + [headers.join(","), ...rows.map(r => r.join(","))].join("\n");
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `aethersens_telemetria_${currentRange}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
        });
    </script>
    @endpush
</x-app-layout>
