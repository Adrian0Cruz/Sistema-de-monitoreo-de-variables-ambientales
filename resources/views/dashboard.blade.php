<x-app-layout>
    <div class="flex flex-col w-full gap-y-8">

        {{-- ============================================================ --}}
        {{-- PAGE HEADER: Location + Sync Status                          --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    Centro de Datos &bull; Sala de Racks
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Supervisión Ambiental
                </h1>
            </div>

            <div class="flex items-center gap-3">
                {{-- ESP32 Sync Status --}}
                <div id="esp32-status-pill" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant font-label-md text-label-md">
                    <span id="esp32-status-dot" class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span id="esp32-status-text">Esperando señal ESP32...</span>
                </div>

                {{-- Active Zone --}}
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container font-label-md text-label-md text-on-surface">
                    <span class="material-symbols-outlined text-[16px] text-outline">sensors</span>
                    <span id="esp32-zone-text">{{ $zona ?? 'Zona A' }}</span>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PRIMARY KPI CARDS: Temp | Humedad | Sensación | Humo          --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Card 1: Temperatura --}}
            @php
                $tempStatus      = $tempStatus ?? 'normal';
                $tempStatusLabel = match($tempStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Normal',
                };
                $tMin = $settings['tempMin'] ?? 18;
                $tMax = $settings['tempMax'] ?? 24;
            @endphp
            <x-metric-card
                title="Temperatura"
                value="{{ $temperatura ?? '21.5' }}"
                unit="°C"
                :status="$tempStatus"
                :statusLabel="$tempStatusLabel"
                icon="thermostat"
                iconColor="text-primary"
                range="Rango operativo: {{ $tMin }}°C – {{ $tMax }}°C"
                rangeId="metric-temp-range"
                valueId="metric-temp-val"
                statusBadgeId="metric-temp-badge"
            />

            {{-- Card 2: Humedad Relativa --}}
            @php
                $humedadStatus      = $humedadStatus ?? 'normal';
                $humedadStatusLabel = match($humedadStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Normal',
                };
                $hMin = $settings['humMin'] ?? 40;
                $hMax = $settings['humMax'] ?? 60;
            @endphp
            <x-metric-card
                title="Humedad Relativa"
                value="{{ $humedad ?? '45.0' }}"
                unit="% RH"
                :status="$humedadStatus"
                :statusLabel="$humedadStatusLabel"
                icon="humidity_mid"
                iconColor="text-secondary"
                range="Rango operativo: {{ $hMin }}% – {{ $hMax }}%"
                rangeId="metric-hum-range"
                valueId="metric-hum-val"
                statusBadgeId="metric-hum-badge"
            />

            {{-- Card 3: Sensación Térmica (Índice de Calor - ESP32 / DHT11) --}}
            @php
                $sensacionStatus      = $sensacionStatus ?? 'normal';
                $sensacionStatusLabel = match($sensacionStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Normal',
                };
            @endphp
            <x-metric-card
                title="Sensación Térmica"
                value="{{ $sensacionTermica ?? ($indiceCalor ?? '22.0') }}"
                unit="°C"
                :status="$sensacionStatus"
                :statusLabel="$sensacionStatusLabel"
                icon="device_thermostat"
                iconColor="text-primary-fixed"
                range="Índice de calor calculado (DHT11)"
                valueId="metric-heat-val"
                statusBadgeId="metric-heat-badge"
            />

            {{-- Card 4: Detección de Humo --}}
            @php
                $humoStatus      = $humoStatus ?? 'normal';
                $humoStatusLabel = match($humoStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Seguro',
                };
                $humoValueColor  = match($humoStatus) {
                    'warning'  => 'text-secondary',
                    'critical' => 'text-error',
                    default    => 'text-tertiary',
                };
            @endphp
            <x-metric-card
                title="Detección de Humo"
                :value="$humo ?? 'Sin Humo'"
                unit=""
                :status="$humoStatus"
                :statusLabel="$humoStatusLabel"
                icon="detector_smoke"
                iconColor="text-tertiary"
                :valueColor="$humoValueColor"
                range="Estado operativo óptimo"
            />

        </div>

        {{-- ============================================================ --}}
        {{-- REAL-TIME TELEMETRY CHART: ApexCharts Dual-Axis               --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card">

            {{-- Chart Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Telemetría en Vivo</h2>
                        <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-ping"></span>
                            Tiempo Real (UTC -5)
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Supervisión continua con escalas independientes (Temperatura izq / Humedad der)</p>
                </div>

                {{-- Legend & Rate --}}
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-primary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Temperatura (°C - Izq)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-secondary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Humedad (% RH - Der)</span>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low text-outline font-code-sm text-code-sm border border-outline-variant/30">
                        <span class="material-symbols-outlined text-[14px]">sensors</span>
                        <span>DHT11 &bull; 3s</span>
                    </div>
                </div>
            </div>

            {{-- ApexCharts Live Container --}}
            <div class="w-full">
                <div id="telemetry-chart" class="w-full h-72 select-none"></div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ALERT LOG TABLE: Recent Events                                --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card">

            {{-- Table Header --}}
            <div class="px-8 pt-8 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Alertas Recientes</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Eventos y cambios de umbral registrados en tiempo real</p>
                </div>
                <span class="font-code-sm text-code-sm text-outline">
                    {{ isset($alertas) ? $alertas->count() : 4 }} registros
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low text-outline font-label-sm text-label-sm uppercase tracking-wider">
                            <th class="py-3 px-8">Hora</th>
                            <th class="py-3 px-8">Variable</th>
                            <th class="py-3 px-8">Valor</th>
                            <th class="py-3 px-8 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="telemetry-table-body" class="divide-y divide-outline-variant/30 font-body-md text-body-md">

                        @if(isset($alertas) && $alertas->count())

                            @foreach($alertas as $alerta)
                                <tr class="hover:bg-surface-container-high/60 transition-colors">
                                    <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">
                                        {{ $alerta->hora ?? $alerta->created_at?->format('H:i:s') ?? '--:--:--' }}
                                    </td>
                                    <td class="py-5 px-8 font-medium text-on-surface">
                                        {{ $alerta->variable ?? $alerta->nombre ?? '—' }}
                                    </td>
                                    <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">
                                        {{ $alerta->valor ?? '—' }}
                                        <span class="text-outline">{{ $alerta->unidad ?? '' }}</span>
                                    </td>
                                    <td class="py-5 px-8 text-right">
                                        <x-status-badge
                                            :status="$alerta->estado ?? 'normal'"
                                            :label="match($alerta->estado ?? 'normal') {
                                                'warning', 'alerta'   => 'Alerta',
                                                'critical', 'critico' => 'Crítico',
                                                default               => 'Normal',
                                            }"
                                            :pill="true"
                                        />
                                    </td>
                                </tr>
                            @endforeach

                        @else

                            {{-- Static placeholder rows (Stitch design defaults) --}}
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">14:38:12</td>
                                <td class="py-5 px-8 font-medium text-on-surface">Temperatura Ambiente</td>
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">21.5 <span class="text-outline">°C</span></td>
                                <td class="py-5 px-8 text-right">
                                    <x-status-badge status="normal" label="Normal" :pill="true"/>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">14:25:04</td>
                                <td class="py-5 px-8 font-medium text-on-surface">Temperatura de Escape</td>
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">26.8 <span class="text-outline">°C</span></td>
                                <td class="py-5 px-8 text-right">
                                    <x-status-badge status="alerta" label="Alerta" :pill="true"/>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">13:50:22</td>
                                <td class="py-5 px-8 font-medium text-on-surface">Sensor de Humo</td>
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">0 <span class="text-outline">PPM</span></td>
                                <td class="py-5 px-8 text-right">
                                    <x-status-badge status="normal" label="Normal" :pill="true"/>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">12:15:40</td>
                                <td class="py-5 px-8 font-medium text-on-surface">Humedad Relativa</td>
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">45.0 <span class="text-outline">%</span></td>
                                <td class="py-5 px-8 text-right">
                                    <x-status-badge status="normal" label="Normal" :pill="true"/>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">12:10:15</td>
                                <td class="py-5 px-8 font-medium text-on-surface">Sensación Térmica</td>
                                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">22.0 <span class="text-outline">°C</span></td>
                                <td class="py-5 px-8 text-right">
                                    <x-status-badge status="normal" label="Normal" :pill="true"/>
                                </td>
                            </tr>

                        @endif

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    @push('scripts')
<script>
    (function () {
        // =========================================================================
        // 1. UTILIDAD DE HORA UTC-5 (Colombia, Perú, Ecuador, EST)
        // =========================================================================
        function getUtcMinus5Time(dateObj = new Date()) {
            try {
                return new Intl.DateTimeFormat('es-CO', {
                    timeZone: 'America/Bogota',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).format(dateObj);
            } catch (e) {
                const utcMs = dateObj.getTime() + (dateObj.getTimezoneOffset() * 60000);
                const utcMinus5Date = new Date(utcMs - (5 * 3600000));
                return utcMinus5Date.toTimeString().split(' ')[0];
            }
        }

        const maxPoints = 20;
        let tempSeriesData = [];
        let humSeriesData  = [];

        // Valor actual más reciente
        let currentTemp = 21.5;
        let currentHum  = 45.0;
        let lastMqttPacketTime = 0;

        // Cargar configuración de Laravel y sincronizar con localStorage del usuario
        let appSettings = @js($settings ?? []);
        try {
            const localSaved = localStorage.getItem('aethersens_settings');
            if (localSaved) {
                appSettings = Object.assign({}, appSettings, JSON.parse(localSaved));
            }
        } catch (e) {
            console.warn('[AetherSens] No se pudo leer localStorage:', e);
        }

        // Reflejar rangos configurados en el DOM inmediatamente
        function applyConfiguredRanges() {
            const tempRangeEl = document.getElementById('metric-temp-range');
            if (tempRangeEl && appSettings.tempMin !== undefined && appSettings.tempMax !== undefined) {
                tempRangeEl.textContent = `Rango operativo: ${appSettings.tempMin}°C – ${appSettings.tempMax}°C`;
            }
            const humRangeEl = document.getElementById('metric-hum-range');
            if (humRangeEl && appSettings.humMin !== undefined && appSettings.humMax !== undefined) {
                humRangeEl.textContent = `Rango operativo: ${appSettings.humMin}% – ${appSettings.humMax}%`;
            }
            if (appSettings.zona) {
                const zoneEl = document.getElementById('esp32-zone-text');
                if (zoneEl) zoneEl.textContent = appSettings.zona;
            }
        }
        applyConfiguredRanges();

        // Evaluación dinámica de umbrales según parámetros guardados (ASHRAE TC 9.9 / Montería)
        function evaluateTempStatus(val) {
            const min = Number(appSettings.tempMin ?? 18);
            const max = Number(appSettings.tempMax ?? 24);
            const crit = Number(appSettings.tempCritica ?? 28);
            if (val >= min && val <= max) return 'normal';
            if (val >= crit || val < (min - 3)) return 'critical';
            return 'warning';
        }

        function evaluateHumStatus(val) {
            const min = Number(appSettings.humMin ?? 40);
            const max = Number(appSettings.humMax ?? 60);
            const crit = Number(appSettings.humCritica ?? 75);
            if (val >= min && val <= max) return 'normal';
            if (val >= crit || val < (min - 10)) return 'critical';
            return 'warning';
        }

        // Generar 20 puntos iniciales con marcas temporales reales en milisegundos
        const nowMs = Date.now();
        for (let i = maxPoints - 1; i >= 0; i--) {
            const t = nowMs - (i * 3000);
            const tempVal = parseFloat((21.5 + Math.sin(i / 2) * 0.3).toFixed(1));
            const humVal  = parseFloat((45.0 + Math.cos(i / 2) * 1.0).toFixed(1));
            tempSeriesData.push([t, tempVal]);
            humSeriesData.push([t, humVal]);
        }

        function formatXaxisLabel(val, timestamp) {
            const raw = typeof timestamp === 'number' && !isNaN(timestamp)
                ? timestamp
                : (typeof val === 'number' && !isNaN(val) ? val : Number(val));
            if (!raw || isNaN(raw)) return '';
            return getUtcMinus5Time(new Date(raw));
        }

        const chartOptions = {
            series: [
                {
                    name: 'Temperatura',
                    data: tempSeriesData
                },
                {
                    name: 'Humedad',
                    data: humSeriesData
                }
            ],
            chart: {
                type: 'area',
                height: 290,
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 800
                    }
                },
                toolbar: {
                    show: false
                },
                background: 'transparent',
                fontFamily: "'Geist', sans-serif"
            },
            colors: ['#a4c9ff', '#77d6d3'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: [2.5, 2]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.28,
                    opacityTo: 0.02,
                    stops: [0, 95, 100]
                }
            },
            grid: {
                borderColor: '#414751',
                strokeDashArray: 3,
                opacity: 0.4,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 10,
                    right: 20,
                    bottom: 0,
                    left: 20
                }
            },
            legend: {
                show: false
            },
            xaxis: {
                type: 'datetime',
                tickAmount: 5,
                labels: {
                    show: true,
                    style: {
                        colors: '#8b919d',
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px'
                    },
                    rotate: 0,
                    hideOverlappingLabels: true,
                    formatter: formatXaxisLabel
                },
                axisBorder: {
                    show: true,
                    color: '#414751'
                },
                axisTicks: {
                    show: true,
                    color: '#414751'
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: [
                {
                    // Eje Y Izquierdo: TEMPERATURA (°C) - ESCALA DINÁMICA
                    seriesName: 'Temperatura',
                    min: function (min) {
                        if (min === undefined || min === null || isNaN(min)) return 15;
                        return Math.min(15, Math.floor((min - 2) / 5) * 5);
                    },
                    max: function (max) {
                        if (max === undefined || max === null || isNaN(max)) return 35;
                        return Math.max(35, Math.ceil((max + 2) / 5) * 5);
                    },
                    tickAmount: 4,
                    axisTicks: {
                        show: true,
                        color: '#a4c9ff'
                    },
                    axisBorder: {
                        show: true,
                        color: '#a4c9ff'
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: '#a4c9ff',
                            fontFamily: "'JetBrains Mono', monospace",
                            fontSize: '11px'
                        },
                        formatter: function (val) {
                            return val !== undefined && val !== null ? Number(val).toFixed(1) + ' °C' : '';
                        }
                    },
                    title: {
                        text: 'Temperatura (°C)',
                        style: {
                            color: '#a4c9ff',
                            fontSize: '12px',
                            fontFamily: "'Geist', sans-serif",
                            fontWeight: 600
                        }
                    }
                },
                {
                    // Eje Y Derecho: HUMEDAD RELATIVA (% RH)
                    opposite: true,
                    seriesName: 'Humedad',
                    min: 0,
                    max: 100,
                    tickAmount: 4,
                    axisTicks: {
                        show: true,
                        color: '#77d6d3'
                    },
                    axisBorder: {
                        show: true,
                        color: '#77d6d3'
                    },
                    labels: {
                        show: true,
                        style: {
                            colors: '#77d6d3',
                            fontFamily: "'JetBrains Mono', monospace",
                            fontSize: '11px'
                        },
                        formatter: function (val) {
                            return val !== undefined && val !== null ? Math.round(Number(val)) + ' %' : '';
                        }
                    },
                    title: {
                        text: 'Humedad (% RH)',
                        style: {
                            color: '#77d6d3',
                            fontSize: '12px',
                            fontFamily: "'Geist', sans-serif",
                            fontWeight: 600
                        }
                    }
                }
            ],
            tooltip: {
                theme: 'dark',
                shared: true,
                intersect: false,
                style: {
                    fontSize: '12px',
                    fontFamily: "'Geist', sans-serif"
                },
                x: {
                    formatter: function (val) {
                        const raw = typeof val === 'number' && !isNaN(val) ? val : Number(val);
                        return 'Hora (UTC -5): ' + (raw && !isNaN(raw) ? getUtcMinus5Time(new Date(raw)) : val);
                    }
                },
                y: {
                    formatter: function (val, { seriesIndex }) {
                        if (val === undefined || val === null) return '--';
                        return seriesIndex === 0
                            ? Number(val).toFixed(1) + ' °C'
                            : Number(val).toFixed(1) + ' % RH';
                    }
                }
            }
        };

        function startApp() {
            const chartElement = document.querySelector("#telemetry-chart");
            if (!chartElement) {
                setTimeout(startApp, 100);
                return;
            }

            if (typeof ApexCharts === 'undefined') {
                console.warn('[AetherSens] Esperando carga de ApexCharts...');
                setTimeout(startApp, 150);
                return;
            }

            chart = new ApexCharts(chartElement, chartOptions);
            chart.render().then(() => {
                console.log('[AetherSens] ApexCharts renderizado con éxito.');
            });

            // Timer de avance en tiempo real (reloj UTC-5 continuo cada 3 segundos)
            setInterval(function () {
                const nowMs = Date.now();
                // Si hace más de 3.5 segundos no llega paquete MQTT, avanza el tiempo con el valor actual
                if (nowMs - lastMqttPacketTime > 3500) {
                    pushPointToChart(currentTemp, currentHum, nowMs);
                }
            }, 3000);

            // Obtener clima real de la ciudad actual (o Montería como respaldo) antes de sincronizar con el ESP32
            initWeatherBaseline();

            // Iniciar conexión MQTT
            connectMqtt();
        }

        // =========================================================================
        // 2. CLIMA REAL DE RESPALDO (Geolocalización o Montería, Córdoba)
        // =========================================================================
        const DEFAULT_MONTERIA_LAT  = 8.7579;
        const DEFAULT_MONTERIA_LON  = -75.8814;
        const DEFAULT_MONTERIA_CITY = 'Montería, Córdoba';

        function initWeatherBaseline() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        if (lastMqttPacketTime > 0) return; // Si ya llegó telemetría del ESP32, no sobrescribir
                        const userLat = pos.coords.latitude;
                        const userLon = pos.coords.longitude;
                        fetchRealWeather(userLat, userLon, 'Ubicación Local');
                    },
                    function (err) {
                        console.info('[AetherSens] Permiso de geolocalización no otorgado (' + err.message + '). Usando Montería, Córdoba como referencia.');
                        if (lastMqttPacketTime > 0) return;
                        fetchRealWeather(DEFAULT_MONTERIA_LAT, DEFAULT_MONTERIA_LON, DEFAULT_MONTERIA_CITY);
                    },
                    { timeout: 5000, maximumAge: 300000 }
                );
            } else {
                fetchRealWeather(DEFAULT_MONTERIA_LAT, DEFAULT_MONTERIA_LON, DEFAULT_MONTERIA_CITY);
            }
        }

        async function fetchRealWeather(lat, lon, cityName) {
            try {
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature&timezone=auto`;
                const res = await fetch(url);
                if (!res.ok) return;
                const data = await res.json();

                // Si ya llegó telemetría real del ESP32, no sobrescribir
                if (lastMqttPacketTime > 0) return;

                const weatherTemp = parseFloat(data.current.temperature_2m);
                const weatherHum  = parseFloat(data.current.relative_humidity_2m);
                const weatherHeat = parseFloat(data.current.apparent_temperature);

                currentTemp = weatherTemp;
                currentHum  = weatherHum;

                // 1. Temperatura
                const tempEl = document.getElementById('metric-temp-val');
                if (tempEl) tempEl.textContent = weatherTemp.toFixed(1);
                const tempStatus = evaluateTempStatus(weatherTemp);
                updateCardStatus('metric-temp-badge', tempStatus);

                // 2. Humedad Relativa
                const humEl = document.getElementById('metric-hum-val');
                if (humEl) humEl.textContent = weatherHum.toFixed(1);
                const humStatus = evaluateHumStatus(weatherHum);
                updateCardStatus('metric-hum-badge', humStatus);

                // 3. Sensación Térmica
                const heatEl = document.getElementById('metric-heat-val');
                if (heatEl) heatEl.textContent = weatherHeat.toFixed(1);
                const heatStatus = evaluateTempStatus(weatherHeat);
                updateCardStatus('metric-heat-badge', heatStatus);

                // 4. Ubicación y Estado
                const zoneEl = document.getElementById('esp32-zone-text');
                if (zoneEl) zoneEl.textContent = cityName;

                const statusText = document.getElementById('esp32-status-text');
                if (statusText && lastMqttPacketTime === 0) {
                    statusText.textContent = 'Clima: ' + cityName + ' (Esperando ESP32)';
                }

                // 5. Poblar serie de tiempo inicial de la gráfica con estos datos reales
                const now = Date.now();
                tempSeriesData = [];
                humSeriesData  = [];
                for (let i = maxPoints - 1; i >= 0; i--) {
                    const t = now - (i * 3000);
                    const tVal = parseFloat((weatherTemp + Math.sin(i / 2) * 0.15).toFixed(1));
                    const hVal = parseFloat((weatherHum + Math.cos(i / 2) * 0.4).toFixed(1));
                    tempSeriesData.push([t, tVal]);
                    humSeriesData.push([t, hVal]);
                }

                if (chart) {
                    chart.updateSeries([
                        { name: 'Temperatura', data: tempSeriesData },
                        { name: 'Humedad', data: humSeriesData }
                    ], false);
                }

                console.log(`[AetherSens] Clima ambiental de referencia (${cityName}) cargado: ${weatherTemp}°C | ${weatherHum}% RH | ST: ${weatherHeat}°C`);
            } catch (e) {
                console.warn('[AetherSens] Error obteniendo clima Open-Meteo:', e);
            }
        }

        function pushPointToChart(temp, hum, timestampMs = Date.now()) {
            if (!chart) return;

            const ts = typeof timestampMs === 'number' && !isNaN(timestampMs) ? timestampMs : Date.now();
            tempSeriesData.push([ts, temp]);
            humSeriesData.push([ts, hum]);

            if (tempSeriesData.length > maxPoints) {
                tempSeriesData.shift();
                humSeriesData.shift();
            }

            // En modo datetime con tickAmount: 5, ApexCharts redistribuye automáticamente las marcas
            // temporales en el eje X sin necesidad de modificar opciones ni amontonar etiquetas.
            chart.updateSeries([
                { name: 'Temperatura', data: tempSeriesData },
                { name: 'Humedad', data: humSeriesData }
            ], true);
        }

        // =========================================================================
        // 2. CONEXIÓN MQTT WEBSOCKETS AL ESP32 (broker.hivemq.com)
        // =========================================================================
        function connectMqtt() {
            if (typeof mqtt === 'undefined') {
                console.warn('[AetherSens] Esperando carga de cliente MQTT.js...');
                setTimeout(connectMqtt, 200);
                return;
            }

            const isHttps = window.location.protocol === 'https:';
            // En desarrollo local (HTTP) el puerto 8000 (ws) es más rápido y compatible; en HTTPS se usa 8884 (wss)
            const brokerHost = appSettings.brokerHost || 'broker.hivemq.com';
            const brokerPort = isHttps ? (appSettings.brokerPort || 8884) : 8000;
            let brokerUrl = isHttps ? `wss://${brokerHost}:${brokerPort}/mqtt` : `ws://${brokerHost}:${brokerPort}/mqtt`;
            const TOPIC   = appSettings.topic || 'pas_iot/datacenter/telemetria';

            const dot  = document.getElementById('esp32-status-dot');
            const text = document.getElementById('esp32-status-text');
            let watchdogTimer = null;
            let connectionAttempts = 0;

            console.log('[AetherSens] Conectando a broker MQTT:', brokerUrl);
            if (text && lastMqttPacketTime === 0) text.textContent = 'Conectando a Broker...';

            const client = mqtt.connect(brokerUrl, {
                clientId: 'WebDashboard_' + Math.random().toString(16).substring(2, 8),
                clean: true,
                connectTimeout: 5000,
                reconnectPeriod: 3000,
            });

            client.on('connect', function () {
                console.log('[AetherSens] ¡Conectado al Broker HiveMQ! Suscrito al tópico:', TOPIC);
                if (text && lastMqttPacketTime === 0) text.textContent = 'Broker Listo (Esperando señal ESP32)';
                if (dot && lastMqttPacketTime === 0) dot.className = 'w-2 h-2 rounded-full bg-secondary';

                client.subscribe(TOPIC, { qos: 0 }, function (err) {
                    if (err) {
                        console.error('[AetherSens] Error suscribiendo a tópico MQTT:', err);
                    } else {
                        console.log('[AetherSens] Suscripción activa a:', TOPIC);
                    }
                });
            });

            client.on('error', function (err) {
                console.error('[AetherSens] Error en conexión MQTT:', err);
                connectionAttempts++;
                // Si falla en ws:8000, intentar con wss:8884
                if (connectionAttempts === 2 && !brokerUrl.includes('8884')) {
                    console.warn('[AetherSens] Alternando a puerto seguro WSS 8884...');
                    client.end(true);
                    brokerUrl = `wss://${brokerHost}:8884/mqtt`;
                    setTimeout(connectMqtt, 1000);
                    return;
                }
                if (text && lastMqttPacketTime === 0) text.textContent = 'Error Broker (Ver F12)';
                if (dot && lastMqttPacketTime === 0) dot.className = 'w-2 h-2 rounded-full bg-error';
            });

            client.on('offline', function () {
                if (text && lastMqttPacketTime === 0) text.textContent = 'Broker Desconectado';
                if (dot && lastMqttPacketTime === 0) dot.className = 'w-2 h-2 rounded-full bg-outline';
            });

            client.on('message', function (topic, message) {
                try {
                    const rawStr = message.toString();
                    const payload = JSON.parse(rawStr);
                    const timeUtc5 = getUtcMinus5Time();
                    lastMqttPacketTime = Date.now();

                    console.info('%c[AetherSens Telemetría Recibida]:', 'color: #77d6d3; font-weight: bold;', payload);

                    // Marcar estado ESP32 activo
                    if (dot) dot.className = 'w-2 h-2 rounded-full bg-tertiary animate-pulse';
                    if (text) text.textContent = 'ESP32 Sincronizado (' + timeUtc5 + ')';

                    const watchdogSeconds = Number(appSettings.watchdog || 10);
                    clearTimeout(watchdogTimer);
                    watchdogTimer = setTimeout(function () {
                        if (dot) dot.className = 'w-2 h-2 rounded-full bg-secondary';
                        if (text) text.textContent = `ESP32 Sin señal reciente (>${watchdogSeconds}s)`;
                    }, watchdogSeconds * 1000);

                    // Zona ESP32
                    const zoneEl = document.getElementById('esp32-zone-text');
                    if (zoneEl) zoneEl.textContent = payload.zona || appSettings.zona || 'Zona A';

                    // 1. Temperatura
                    let temp = null;
                    if (payload.temperatura !== undefined) {
                        temp = parseFloat(payload.temperatura);
                        currentTemp = temp;
                        const tempEl = document.getElementById('metric-temp-val');
                        if (tempEl) tempEl.textContent = temp.toFixed(1);

                        const status = evaluateTempStatus(temp);
                        updateCardStatus('metric-temp-badge', status);
                        addLogTableRow('Temperatura Ambiente', temp.toFixed(1), '°C', status, timeUtc5);
                    }

                    // 2. Humedad Relativa
                    let hum = null;
                    const humRaw = payload.humedad_relativa !== undefined ? payload.humedad_relativa : payload.humedad;
                    if (humRaw !== undefined) {
                        hum = parseFloat(humRaw);
                        currentHum = hum;
                        const humEl = document.getElementById('metric-hum-val');
                        if (humEl) humEl.textContent = hum.toFixed(1);

                        const status = evaluateHumStatus(hum);
                        updateCardStatus('metric-hum-badge', status);
                        addLogTableRow('Humedad Relativa', hum.toFixed(1), '% RH', status, timeUtc5);
                    }

                    // 3. Sensación Térmica
                    if (payload.sensacion_termica !== undefined) {
                        const heat = parseFloat(payload.sensacion_termica);
                        const heatEl = document.getElementById('metric-heat-val');
                        if (heatEl) heatEl.textContent = heat.toFixed(1);

                        const status = evaluateTempStatus(heat);
                        updateCardStatus('metric-heat-badge', status);
                    }

                    // Actualizar punto real en la gráfica
                    if (temp !== null && hum !== null && !isNaN(temp) && !isNaN(hum)) {
                        pushPointToChart(temp, hum, Date.now());
                    }

                } catch (err) {
                    console.error('[AetherSens] Error procesando telemetría MQTT:', err);
                }
            });
        }

        function updateCardStatus(badgeId, status) {
            const badge = document.getElementById(badgeId);
            if (!badge) return;

            const dot = badge.querySelector('.rounded-full');
            const text = badge.querySelector('.status-text') || badge.querySelector('span:last-child');

            badge.className = 'px-2.5 py-1 rounded-md font-label-sm text-label-sm flex items-center gap-1.5 transition-colors ' +
                (status === 'critical' ? 'bg-error/10 text-error' :
                 status === 'warning'  ? 'bg-secondary/10 text-secondary' :
                                         'bg-tertiary/10 text-tertiary');

            if (dot) {
                dot.className = 'w-1.5 h-1.5 rounded-full ' +
                    (status === 'critical' ? 'bg-error' :
                     status === 'warning'  ? 'bg-secondary' :
                                             'bg-tertiary');
            }

            if (text) {
                text.textContent = (status === 'critical' ? 'Crítico' : (status === 'warning' ? 'Advertencia' : 'Normal'));
            }
        }

        function addLogTableRow(variable, valor, unidad, status, timeStr) {
            const tbody = document.getElementById('telemetry-table-body');
            if (!tbody) return;

            const badgeBg = status === 'critical' ? 'bg-error/10 text-error' : (status === 'warning' ? 'bg-secondary/10 text-secondary' : 'bg-tertiary/10 text-tertiary');
            const dotBg   = status === 'critical' ? 'bg-error' : (status === 'warning' ? 'bg-secondary' : 'bg-tertiary');
            const label   = status === 'critical' ? 'Crítico' : (status === 'warning' ? 'Alerta' : 'Normal');

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-surface-container-high/60 transition-colors bg-surface-container/20';
            tr.innerHTML = `
                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">${timeStr}</td>
                <td class="py-5 px-8 font-medium text-on-surface">${variable}</td>
                <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">${valor} <span class="text-outline">${unidad}</span></td>
                <td class="py-5 px-8 text-right">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-sm text-label-sm ${badgeBg}">
                        <span class="w-1.5 h-1.5 rounded-full ${dotBg}"></span>
                        ${label}
                    </span>
                </td>
            `;

            tbody.insertBefore(tr, tbody.firstChild);

            while (tbody.children.length > 7) {
                tbody.removeChild(tbody.lastChild);
            }
        }

        // Ejecutar inicio cuando el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startApp);
        } else {
            startApp();
        }
    })();
</script>
@endpush
</x-app-layout>
