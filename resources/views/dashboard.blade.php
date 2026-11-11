<x-app-layout>
    <div data-dashboard-root class="flex flex-col w-full gap-y-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    Intalnet &bull; Sala T&eacute;rmica 01 &bull; Monter&iacute;a, C&oacute;rdoba
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Supervisi&oacute;n de Sala T&eacute;cnica
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <div id="esp32-status-pill" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant font-label-md text-label-md">
                    <span id="esp32-status-dot" class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span id="esp32-status-text">Conectando al broker MQTT...</span>
                </div>

                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container font-label-md text-label-md text-on-surface">
                    <span class="material-symbols-outlined text-[16px] text-outline">sensors</span>
                    <span id="esp32-zone-text">{{ $settings['zona'] ?? 'Sala 01' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <x-metric-card
                title="Temperatura"
                value="&mdash;"
                unit="&deg;C"
                status="normal"
                statusLabel="Normal"
                icon="thermostat"
                iconColor="text-primary"
                range="Rango operativo: {{ $settings['tempMin'] ?? 18 }}&deg;C &ndash; {{ $settings['tempMax'] ?? 28 }}&deg;C"
                rangeId="metric-temp-range"
                valueId="metric-temp-val"
                statusBadgeId="metric-temp-badge"
            />

            <x-metric-card
                title="Humedad Relativa"
                value="&mdash;"
                unit="% RH"
                status="normal"
                statusLabel="Normal"
                icon="humidity_mid"
                iconColor="text-secondary"
                range="Rango operativo: {{ $settings['humMin'] ?? 40 }}% &ndash; {{ $settings['humMax'] ?? 60 }}%"
                rangeId="metric-hum-range"
                valueId="metric-hum-val"
                statusBadgeId="metric-hum-badge"
            />

            <x-metric-card
                title="Nivel de Gas (MQ-2)"
                value="&mdash;"
                unit="ADC"
                status="normal"
                statusLabel="Normal"
                icon="detector_smoke"
                iconColor="text-tertiary"
                range="Escala ADC de 12 bits (0 &ndash; 4095)"
                valueId="metric-gas-val"
                statusBadgeId="metric-gas-badge"
            >
                <div class="mt-5">
                    <div class="relative h-2 w-full rounded-full bg-surface-container-highest">
                        <div id="metric-gas-bar" class="h-2 rounded-full bg-tertiary transition-all duration-300" style="width: 0%"></div>
                        <span id="metric-gas-marker" class="absolute -top-1 h-4 w-0.5 bg-error/70" style="left: 36.6%"></span>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-[11px] text-outline font-code-sm text-code-sm">
                        <span>0</span>
                        <span>Umbral de humo: <span id="metric-gas-threshold">{{ $settings['gasAdcUmbral'] ?? 1500 }}</span> ADC</span>
                        <span>4095</span>
                    </div>
                </div>
            </x-metric-card>

            <x-metric-card
                title="Estado de la Sala"
                value="&mdash;"
                status="normal"
                statusLabel="Normal"
                icon="security"
                iconColor="text-primary-fixed"
                valueClass="text-headline-md"
                range="Estado autoritativo reportado por el ESP32"
                valueId="metric-estado-val"
                statusBadgeId="metric-estado-badge"
            />

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

            <div class="rounded-xl bg-surface-container p-6 flex flex-col gap-3 shadow-card border border-outline-variant/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined text-[20px]">campaign</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface">Buzzer de Alarma</span>
                    </div>
                    <span id="metric-buzzer-badge" class="px-2.5 py-1 rounded-md bg-tertiary/10 text-tertiary font-label-sm text-label-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                        <span class="status-text">Inactivo</span>
                    </span>
                </div>
                <span id="metric-buzzer-val" class="font-display-lg text-display-lg font-semibold text-on-surface">&mdash;</span>
                <span id="metric-buzzer-detail" class="font-body-sm text-body-sm text-outline">Sin datos del ESP32</span>
            </div>

            <div class="rounded-xl bg-surface-container p-6 flex flex-col gap-3 shadow-card border border-outline-variant/30">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[20px]">timer</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface">Uptime del Nodo</span>
                </div>
                <span id="metric-uptime-val" class="font-display-lg text-display-lg font-semibold text-on-surface">&mdash;</span>
                <span class="font-body-sm text-body-sm text-outline">Tiempo en l&iacute;nea reportados por el firmware</span>
            </div>

            <div class="rounded-xl bg-surface-container p-6 flex flex-col gap-3 shadow-card border border-outline-variant/30">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[20px]">memory</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface">Dispositivo</span>
                </div>
                <span id="metric-node-device" class="font-code-sm text-code-sm font-semibold text-on-surface">ESP32_SALA01</span>
                <span class="font-body-sm text-body-sm text-outline">&Uacute;ltimo paquete: <span id="metric-lastpacket-val" class="font-code-sm text-code-sm">&mdash;</span></span>
            </div>

        </div>

        <div class="rounded-xl bg-surface-container p-8 shadow-card">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Telemetr&iacute;a en Vivo</h2>
                        <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-tertiary/10 text-tertiary font-label-sm text-label-sm border border-tertiary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-ping"></span>
                            Tiempo Real (UTC -5)
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Escalas independientes (Temperatura izq / Humedad der) &mdash; datos publicados por el ESP32</p>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-primary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Temperatura (&deg;C - Izq)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-secondary rounded-full"></span>
                            <span class="font-label-sm text-label-sm text-on-surface">Humedad (% RH - Der)</span>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-lg bg-surface-container-low text-outline font-code-sm text-code-sm border border-outline-variant/30">
                        <span class="material-symbols-outlined text-[14px]">sensors</span>
                        <span>SHT40 + MQ-2 &bull; {{ $settings['intervalo'] ?? 3 }}s</span>
                    </div>
                </div>
            </div>

            <div class="relative w-full">
                <div id="telemetry-chart" class="w-full h-72 select-none"></div>
                <div id="telemetry-empty-state" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="flex flex-col items-center gap-2 text-outline">
                        <span class="material-symbols-outlined text-[28px]">sensors_off</span>
                        <span class="font-label-md text-label-md">Esperando telemetr&iacute;a del ESP32...</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="rounded-xl bg-surface-container overflow-hidden shadow-card">

            <div class="px-8 pt-8 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Alertas y Eventos</h2>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Cambios de estado y silenciamiento publicados por el ESP32 en <span class="font-code-sm text-code-sm">{{ $settings['topicAlerta'] ?? 'intalnet/sala01/alerta' }}</span></p>
                </div>
                <span id="telemetry-alert-count" class="font-code-sm text-code-sm text-outline">0 registros</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low text-outline font-label-sm text-label-sm uppercase tracking-wider">
                            <th class="py-3 px-8">Hora</th>
                            <th class="py-3 px-8">Evento</th>
                            <th class="py-3 px-8">Detalle</th>
                            <th class="py-3 px-8 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="telemetry-table-body" class="divide-y divide-outline-variant/30 font-body-md text-body-md">
                        <tr id="telemetry-alert-empty">
                            <td colspan="4" class="py-8 px-8 text-center text-outline font-body-sm text-body-sm">
                                Sin eventos registrados. El ESP32 publica las alertas en el t&oacute;pico de alertas.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script type="application/json" id="dashboard-config">@json($settings ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES)</script>
</x-app-layout>
