<x-app-layout>
    <div class="flex flex-col w-full gap-y-8">

        {{-- ============================================================ --}}
        {{-- PAGE HEADER: Historical Analytics + Range Selector           --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    Centro de Datos &bull; Sala de Racks
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Datos Históricos y Analítica
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Range Selector --}}
                <div class="flex items-center bg-surface-container rounded-lg p-1 border border-outline-variant/30">
                    <button class="px-3 py-1.5 rounded font-label-md text-label-md bg-surface-container-high text-primary font-medium transition-colors">24 Horas</button>
                    <button class="px-3 py-1.5 rounded font-label-md text-label-md text-outline hover:text-on-surface transition-colors">7 Días</button>
                    <button class="px-3 py-1.5 rounded font-label-md text-label-md text-outline hover:text-on-surface transition-colors">30 Días</button>
                </div>

                {{-- Export Button --}}
                <button onclick="exportToCsv()" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors font-label-md text-label-md border border-outline-variant/40">
                    <span class="material-symbols-outlined text-[18px] text-primary">download</span>
                    <span>Exportar CSV</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CONSOLIDATED SUMMARY KPI CARDS                               --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Stat 1: Temperatura Media --}}
            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[20px]">thermostat</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface">Temp. Media</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                        Óptimo
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-display-lg font-semibold text-on-surface">21.8</span>
                        <span class="font-headline-md text-headline-md text-outline">°C</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Mín: <strong class="text-on-surface">18.4 °C</strong></span>
                        <span>Máx: <strong class="text-on-surface">24.6 °C</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 2: Humedad Media --}}
            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[20px]">humidity_mid</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface">Humedad Media</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                        Normal
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-display-lg font-semibold text-on-surface">46.5</span>
                        <span class="font-headline-md text-headline-md text-outline">% RH</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Mín: <strong class="text-on-surface">39.0 %</strong></span>
                        <span>Máx: <strong class="text-on-surface">54.2 %</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 3: Sensación Térmica Media --}}
            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-fixed">
                            <span class="material-symbols-outlined text-[20px]">device_thermostat</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface">Sensación Térmica</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                        Nominal
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-display-lg font-semibold text-on-surface">22.1</span>
                        <span class="font-headline-md text-headline-md text-outline">°C</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Índice Confort: <strong class="text-tertiary">ASHRAE A1</strong></span>
                    </div>
                </div>
            </div>

            {{-- Stat 4: Cumplimiento de Umbral --}}
            <div class="rounded-xl bg-surface-container p-6 shadow-card border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface">Cumplimiento</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                        Alta Calidad
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-display-lg font-semibold text-tertiary">99.4</span>
                        <span class="font-headline-md text-headline-md text-outline">%</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-outline-variant/20 font-body-sm text-body-sm text-outline">
                        <span>Tiempo seguro: <strong class="text-on-surface">23h 52m</strong></span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- HISTORICAL EVOLUTION CHART: 24h Profile                      --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card border border-outline-variant/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Evolución de Tendencia Histórica</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Perfil de Temperatura y Humedad durante las últimas 24 horas (UTC -5)</p>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
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
                <div id="historical-evolution-chart" class="w-full min-h-[300px]"></div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- HISTORICAL DATA LOGS TABLE                                   --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card border border-outline-variant/30">
            <div class="px-8 pt-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Muestreos Registrados</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Historial cronológico de telemetría capturada en Sala de Servidores</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-code-sm text-code-sm text-outline">Últimos registros</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low text-outline font-label-sm text-label-sm uppercase tracking-wider border-y border-outline-variant/30">
                            <th class="py-3.5 px-8">Hora (UTC -5)</th>
                            <th class="py-3.5 px-8">Zona</th>
                            <th class="py-3.5 px-8">Temperatura</th>
                            <th class="py-3.5 px-8">Humedad</th>
                            <th class="py-3.5 px-8">Sensación Térmica</th>
                            <th class="py-3.5 px-8 text-right">Estado Operativo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md">
                        @php
                            $muestras = [
                                ['hora' => '18:30:00', 'zona' => 'Zona A - Racks', 'temp' => '21.5', 'hum' => '45.0', 'heat' => '22.0', 'estado' => 'normal', 'label' => 'Normal'],
                                ['hora' => '17:30:00', 'zona' => 'Zona A - Racks', 'temp' => '22.1', 'hum' => '46.2', 'heat' => '22.4', 'estado' => 'normal', 'label' => 'Normal'],
                                ['hora' => '16:30:00', 'zona' => 'Zona A - Racks', 'temp' => '23.4', 'hum' => '48.0', 'heat' => '23.8', 'estado' => 'normal', 'label' => 'Normal'],
                                ['hora' => '15:30:00', 'zona' => 'Zona A - Racks', 'temp' => '24.8', 'hum' => '52.1', 'heat' => '25.3', 'estado' => 'warning', 'label' => 'Advertencia'],
                                ['hora' => '14:30:00', 'zona' => 'Zona A - Racks', 'temp' => '22.8', 'hum' => '47.4', 'heat' => '23.0', 'estado' => 'normal', 'label' => 'Normal'],
                                ['hora' => '13:30:00', 'zona' => 'Zona A - Racks', 'temp' => '21.2', 'hum' => '44.5', 'heat' => '21.6', 'estado' => 'normal', 'label' => 'Normal'],
                                ['hora' => '12:30:00', 'zona' => 'Zona A - Racks', 'temp' => '20.9', 'hum' => '43.8', 'heat' => '21.2', 'estado' => 'normal', 'label' => 'Normal'],
                            ];
                        @endphp

                        @foreach($muestras as $m)
                            <tr class="hover:bg-surface-container-high/60 transition-colors">
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface-variant">{{ $m['hora'] }}</td>
                                <td class="py-4 px-8 font-medium text-on-surface">{{ $m['zona'] }}</td>
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                    <span class="text-primary font-medium">{{ $m['temp'] }}</span> <span class="text-outline">°C</span>
                                </td>
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                    <span class="text-secondary font-medium">{{ $m['hum'] }}</span> <span class="text-outline">% RH</span>
                                </td>
                                <td class="py-4 px-8 font-code-sm text-code-sm text-on-surface">
                                    <span class="text-primary-fixed">{{ $m['heat'] }}</span> <span class="text-outline">°C</span>
                                </td>
                                <td class="py-4 px-8 text-right">
                                    <x-status-badge :status="$m['estado']" :label="$m['label']" :pill="true"/>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initHistoricalChart() {
                const el = document.querySelector("#historical-evolution-chart");
                if (!el) return;
                if (typeof ApexCharts === 'undefined') {
                    setTimeout(initHistoricalChart, 150);
                    return;
                }

                const hours24 = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
                const temp24  = [20.5, 20.2, 19.8, 20.1, 21.2, 22.0, 22.8, 24.1, 23.5, 22.2, 21.6, 21.1];
                const hum24   = [44.0, 43.5, 43.0, 44.2, 46.0, 48.2, 51.0, 53.5, 49.0, 46.5, 45.2, 44.8];

                const options = {
                    series: [
                        { name: 'Temperatura', data: temp24 },
                        { name: 'Humedad', data: hum24 }
                    ],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false },
                        background: 'transparent',
                        fontFamily: "'Geist', sans-serif"
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
                        categories: hours24,
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

                const chart = new ApexCharts(el, options);
                chart.render();
            }

            initHistoricalChart();
        });

        function exportToCsv() {
            const rows = [
                ["Hora (UTC-5)", "Zona", "Temperatura (C)", "Humedad (%RH)", "Sensacion Termica (C)", "Estado"],
                ["18:30:00", "Zona A - Racks", "21.5", "45.0", "22.0", "Normal"],
                ["17:30:00", "Zona A - Racks", "22.1", "46.2", "22.4", "Normal"],
                ["16:30:00", "Zona A - Racks", "23.4", "48.0", "23.8", "Normal"],
                ["15:30:00", "Zona A - Racks", "24.8", "52.1", "25.3", "Advertencia"],
                ["14:30:00", "Zona A - Racks", "22.8", "47.4", "23.0", "Normal"],
                ["13:30:00", "Zona A - Racks", "21.2", "44.5", "21.6", "Normal"]
            ];

            let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "aethersens_telemetria_historica.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
    @endpush
</x-app-layout>

