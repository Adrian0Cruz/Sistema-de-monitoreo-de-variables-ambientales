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
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant font-label-md text-label-md">
                    <span class="w-2 h-2 rounded-full {{ ($esp32Status ?? true) ? 'bg-tertiary' : 'bg-error' }}"></span>
                    <span>{{ ($esp32Status ?? true) ? 'ESP32 Sincronizado' : 'ESP32 Desconectado' }}</span>
                </div>

                {{-- Active Zone --}}
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container font-label-md text-label-md text-on-surface">
                    <span class="material-symbols-outlined text-[16px] text-outline">sensors</span>
                    <span>{{ $zona ?? 'Zona A' }}</span>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PRIMARY KPI CARDS: Temperatura | Humedad | Humo              --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Temperatura --}}
            @php
                $tempStatus      = $tempStatus ?? 'normal';
                $tempStatusLabel = match($tempStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Normal',
                };
            @endphp
            <x-metric-card
                title="Temperatura"
                value="{{ $temperatura ?? '21.5' }}"
                unit="°C"
                :status="$tempStatus"
                :statusLabel="$tempStatusLabel"
                icon="thermostat"
                iconColor="text-primary"
                range="Rango operativo: 18°C – 24°C"
            />

            {{-- Card 2: Humedad Relativa --}}
            @php
                $humedadStatus      = $humedadStatus ?? 'normal';
                $humedadStatusLabel = match($humedadStatus) {
                    'warning'  => 'Advertencia',
                    'critical' => 'Crítico',
                    default    => 'Normal',
                };
            @endphp
            <x-metric-card
                title="Humedad Relativa"
                value="{{ $humedad ?? '45' }}"
                unit="% RH"
                :status="$humedadStatus"
                :statusLabel="$humedadStatusLabel"
                icon="humidity_mid"
                iconColor="text-secondary"
                range="Rango operativo: 40% – 55%"
            />

            {{-- Card 3: Detección de Humo --}}
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
        {{-- HISTORICAL CHART: 24-Hour Telemetry SVG                      --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card">

            {{-- Chart Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Historial (24 Horas)</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Telemetría ambiental continua en Sala de Servidores</p>
                </div>

                {{-- Legend + Time Controls --}}
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-0.5 bg-primary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Temperatura (°C)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-0.5 bg-secondary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Humedad (%)</span>
                        </div>
                    </div>
                    <div class="flex items-center bg-surface-container-low rounded-lg p-1">
                        <button class="px-3 py-1 rounded font-label-sm text-label-sm bg-surface-container-high text-on-surface font-medium transition-colors">24h</button>
                        <button class="px-3 py-1 rounded font-label-sm text-label-sm text-outline hover:text-on-surface transition-colors">7d</button>
                    </div>
                </div>
            </div>

            {{-- SVG Chart Canvas (static — replace paths with dynamic data when ready) --}}
            {{-- $chartDataTemp and $chartDataHum can override the SVG path d attributes --}}
            <div class="relative w-full h-64 select-none">
                <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 800 200">
                    <defs>
                        <linearGradient id="tempGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%"   stop-color="#a4c9ff" stop-opacity="0.18"/>
                            <stop offset="100%" stop-color="#a4c9ff" stop-opacity="0.0"/>
                        </linearGradient>
                        <linearGradient id="humGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%"   stop-color="#77d6d3" stop-opacity="0.14"/>
                            <stop offset="100%" stop-color="#77d6d3" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    {{-- Grid lines --}}
                    <line x1="0" x2="800" y1="40"  stroke="#414751" stroke-dasharray="3 3" stroke-opacity="0.4" stroke-width="1"></line>
                    <line x1="0" x2="800" y1="90"  stroke="#414751" stroke-dasharray="3 3" stroke-opacity="0.4" stroke-width="1"></line>
                    <line x1="0" x2="800" y1="140" stroke="#414751" stroke-dasharray="3 3" stroke-opacity="0.4" stroke-width="1"></line>
                    <line x1="0" x2="800" y1="190" stroke="#414751" stroke-opacity="0.5" stroke-width="1"></line>

                    {{-- Humedad area fill --}}
                    <path
                        d="{{ $chartHumPath ?? 'M 0,135 Q 130,120 260,110 T 530,95 T 800,105 L 800,190 L 0,190 Z' }}"
                        fill="url(#humGradient)"/>

                    {{-- Temperatura area fill --}}
                    <path
                        d="{{ $chartTempPath ?? 'M 0,85 Q 130,95 260,78 T 530,68 T 800,75 L 800,190 L 0,190 Z' }}"
                        fill="url(#tempGradient)"/>

                    {{-- Humedad line (Secondary – Teal) --}}
                    <path
                        d="{{ $chartHumLine ?? 'M 0,135 Q 130,120 260,110 T 530,95 T 800,105' }}"
                        fill="none" stroke="#77d6d3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>

                    {{-- Temperatura line (Primary – Blue) --}}
                    <path
                        d="{{ $chartTempLine ?? 'M 0,85 Q 130,95 260,78 T 530,68 T 800,75' }}"
                        fill="none" stroke="#a4c9ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>

                    {{-- Data point indicators --}}
                    <circle cx="800" cy="{{ $chartTempEndY ?? 75 }}" r="4" fill="#0f131c" stroke="#a4c9ff" stroke-width="2.5"/>
                    <circle cx="800" cy="{{ $chartHumEndY ?? 105 }}"  r="4" fill="#0f131c" stroke="#77d6d3" stroke-width="2"/>
                </svg>
            </div>

            {{-- Time axis labels --}}
            <div class="flex justify-between items-center pt-3 font-code-sm text-code-sm text-outline">
                <span>00:00</span>
                <span>04:00</span>
                <span>08:00</span>
                <span>12:00</span>
                <span>16:00</span>
                <span>20:00</span>
                <span>24:00</span>
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
                    <tbody class="divide-y divide-outline-variant/30 font-body-md text-body-md">

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

                        @endif

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>
