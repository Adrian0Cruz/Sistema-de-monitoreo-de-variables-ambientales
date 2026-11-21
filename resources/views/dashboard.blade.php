<x-app-layout>
    <div data-dashboard-root class="flex flex-col w-full gap-y-6 sm:gap-y-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-[11px] sm:text-label-sm uppercase tracking-wider text-outline block">
                    Intalnet &bull; Sala T&eacute;rmica 01 &bull; Monter&iacute;a, C&oacute;rdoba
                </span>
                <h1 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface mt-1">
                    Supervisi&oacute;n de Sala T&eacute;cnica
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <div id="esp32-status-pill" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant font-label-md text-xs sm:text-label-md max-w-full">
                    <span id="esp32-status-dot" class="w-2 h-2 rounded-full bg-secondary shrink-0"></span>
                    <span id="esp32-status-text" class="truncate max-w-[190px] sm:max-w-none">Conectando al broker MQTT...</span>
                </div>

                <div class="flex items-center gap-1.5 sm:gap-2 px-3 py-1.5 rounded-lg bg-surface-container font-label-md text-xs sm:text-label-md text-on-surface">
                    <span class="material-symbols-outlined text-[16px] text-outline">sensors</span>
                    <span id="esp32-zone-text">{{ $settings['zona'] ?? 'Sala 01' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 2xl:gap-8">

            <x-metric-card
                title="Temperatura"
                value="—"
                unit="°C"
                status="normal"
                statusLabel="Normal"
                icon="thermostat"
                iconColor="text-primary"
                range="Rango operativo: {{ $settings['tempMin'] ?? 18 }} °C – {{ $settings['tempMax'] ?? 28 }} °C"
                rangeId="metric-temp-range"
                valueId="metric-temp-val"
                statusBadgeId="metric-temp-badge"
            />

            <x-metric-card
                title="Sensación Térmica"
                value="—"
                unit="°C"
                status="normal"
                statusLabel="Nominal"
                icon="device_thermostat"
                iconColor="text-primary-fixed"
                range="Índice de Confort ASHRAE / NOAA"
                rangeId="metric-heat-range"
                valueId="metric-heat-val"
                statusBadgeId="metric-heat-badge"
            />

            <x-metric-card
                title="Humedad Relativa"
                value="—"
                unit="% RH"
                status="normal"
                statusLabel="Normal"
                icon="humidity_mid"
                iconColor="text-secondary"
                range="Rango operativo: {{ $settings['humMin'] ?? 40 }}% – {{ $settings['humMax'] ?? 60 }}%"
                rangeId="metric-hum-range"
                valueId="metric-hum-val"
                statusBadgeId="metric-hum-badge"
            />

            <x-metric-card
                title="Nivel de Gas (MQ-2)"
                value="—"
                unit="ADC"
                status="normal"
                statusLabel="Normal"
                icon="detector_smoke"
                iconColor="text-tertiary"
                range="Escala ADC de 12 bits (0 – 4095)"
                valueId="metric-gas-val"
                statusBadgeId="metric-gas-badge"
            >
                <div class="mt-4 sm:mt-5">
                    <div class="relative h-2 w-full rounded-full bg-surface-container-highest">
                        <div id="metric-gas-bar" class="h-2 rounded-full bg-tertiary transition-all duration-300" style="width: 0%"></div>
                        <span id="metric-gas-marker" class="absolute -top-1 h-4 w-0.5 bg-error/70" style="left: {{ round((($settings['gasAdcUmbral'] ?? 3500) / 4095) * 100, 1) }}%"></span>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-[10px] sm:text-[11px] text-outline font-code-sm text-code-sm">
                        <span>0</span>
                        <span>Umbral: <span id="metric-gas-threshold">{{ $settings['gasAdcUmbral'] ?? 3500 }}</span> ADC</span>
                        <span>4095</span>
                    </div>
                </div>
            </x-metric-card>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 2xl:gap-8">

            <x-metric-card
                title="Estado de la Sala"
                value="—"
                status="normal"
                statusLabel="Normal"
                icon="security"
                iconColor="text-primary-fixed"
                valueClass="text-headline-md"
                range="Estado autoritativo reportado por el ESP32"
                valueId="metric-estado-val"
                statusBadgeId="metric-estado-badge"
            />

            <div class="rounded-xl bg-surface-container p-4 sm:p-5 xl:p-6 2xl:p-8 flex flex-col justify-between shadow-card border border-outline-variant/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary shrink-0">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px]" aria-hidden="true">campaign</span>
                        </div>
                        <span class="font-headline-sm text-sm sm:text-headline-sm text-on-surface truncate">Buzzer de Alarma</span>
                    </div>
                    <span id="metric-buzzer-badge" class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md bg-tertiary/10 text-tertiary font-label-sm text-xs sm:text-label-sm flex items-center gap-1.5 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary" aria-hidden="true"></span>
                        <span class="status-text">Inactivo</span>
                    </span>
                </div>
                <div>
                    <span id="metric-buzzer-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                    <p id="metric-buzzer-detail" class="font-body-sm text-body-sm text-outline mt-3">Sin datos del ESP32</p>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-4 sm:p-5 xl:p-6 2xl:p-8 flex flex-col justify-between shadow-card border border-outline-variant/30">
                <div class="flex items-center gap-2 sm:gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]" aria-hidden="true">timer</span>
                    </div>
                    <span class="font-headline-sm text-sm sm:text-headline-sm text-on-surface truncate">Uptime del Nodo</span>
                </div>
                <div>
                    <span id="metric-uptime-val" class="font-display-lg text-display-lg font-semibold text-on-surface">—</span>
                    <p class="font-body-sm text-body-sm text-outline mt-3">Tiempo en l&iacute;nea reportados por el firmware</p>
                </div>
            </div>

            <div class="rounded-xl bg-surface-container p-4 sm:p-5 xl:p-6 2xl:p-8 flex flex-col justify-between shadow-card border border-outline-variant/30">
                <div class="flex items-center gap-2 sm:gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary shrink-0">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]" aria-hidden="true">memory</span>
                    </div>
                    <span class="font-headline-sm text-sm sm:text-headline-sm text-on-surface truncate">Dispositivo</span>
                </div>
                <div>
                    <span id="metric-node-device" class="font-code-sm text-code-sm font-semibold text-on-surface block text-headline-sm">ESP32_SALA01</span>
                    <p class="font-body-sm text-body-sm text-outline mt-3">&Uacute;ltimo paquete: <span id="metric-lastpacket-val" class="font-code-sm text-code-sm text-on-surface">—</span></p>
                </div>
            </div>

        </div>

        <div class="rounded-xl bg-surface-container p-4 sm:p-6 lg:p-8 2xl:p-10 shadow-card border border-outline-variant/30" role="region" aria-label="Telemetría en tiempo real">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Telemetr&iacute;a en Vivo</h2>
                        <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-ping" aria-hidden="true"></span>
                            Tiempo Real (UTC -5)
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Escalas independientes (Temperatura izq / Humedad der) — datos publicados por el ESP32</p>
                </div>

                <div class="flex flex-wrap items-center gap-3 sm:gap-6">
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-primary rounded-full" aria-hidden="true"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Temperatura (°C - Izq)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-secondary rounded-full" aria-hidden="true"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Humedad (% RH - Der)</span>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low text-outline font-code-sm text-code-sm border border-outline-variant/30">
                        <span class="material-symbols-outlined text-[14px]" aria-hidden="true">sensors</span>
                        <span>SHT40 + MQ-2 • {{ $settings['intervalo'] ?? 5 }}s</span>
                    </div>
                </div>
            </div>

            <div class="relative w-full">
                <div id="telemetry-chart" class="w-full h-64 sm:h-72 lg:h-80 2xl:h-96 select-none"></div>
                <div id="telemetry-empty-state" class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-live="polite">
                    <div class="flex flex-col items-center gap-2 text-outline">
                        <span class="material-symbols-outlined text-[28px]" aria-hidden="true">sensors_off</span>
                        <span class="font-label-md text-label-md">Esperando telemetr&iacute;a del ESP32...</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card border border-outline-variant/30" role="region" aria-label="Tabla de eventos y alertas">

            <div class="px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Alertas y Eventos</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Historial del d&iacute;a reportado por el ESP32 en <span class="font-code-sm text-code-sm">{{ $settings['topicAlerta'] ?? 'intalnet/sala01/alerta' }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="telemetry-alert-count" class="px-2.5 py-1 rounded-md bg-surface-container-high font-code-sm text-code-sm text-outline">0 registros</span>
                </div>
            </div>

            <div class="max-h-80 overflow-y-auto overflow-x-auto divide-y divide-outline-variant/20 scrollbar-thin">
                <table class="w-full min-w-[560px] text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-surface-container-low shadow-sm">
                        <tr class="text-outline font-label-sm text-label-sm uppercase tracking-wider">
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Hora</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Evento</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8">Detalle</th>
                            <th class="py-3 px-4 sm:px-6 lg:px-8 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="telemetry-table-body" class="divide-y divide-outline-variant/30 font-body-md text-body-md">
                        <tr id="telemetry-alert-empty">
                            <td colspan="4" class="py-8 px-4 sm:px-6 lg:px-8 text-center text-outline font-body-sm text-body-sm">
                                Sin eventos registrados hoy. El ESP32 publica las alertas en el t&oacute;pico de alertas.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script type="application/json" id="dashboard-config">@json($settings ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES)</script>
</x-app-layout>
