<x-app-layout>
    <div x-data="{
        guardado: false,
        form: {
            tempMin: 18.0,
            tempMax: 24.0,
            tempCritica: 28.0,
            humMin: 40.0,
            humMax: 55.0,
            humCritica: 70.0,
            humoPpm: 50,
            brokerHost: 'broker.hivemq.com',
            brokerPort: 8884,
            topic: 'pas_iot/datacenter/telemetria',
            intervalo: 3,
            watchdog: 10,
            zona: 'Zona A - Sala de Racks',
            audioAlert: true,
            auditLog: true
        },
        guardar() {
            this.guardado = true;
            setTimeout(() => this.guardado = false, 3500);
        }
    }" class="flex flex-col w-full gap-y-8">

        {{-- ============================================================ --}}
        {{-- PAGE HEADER                                                  --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">
                    AetherSens Enterprise &bull; Parámetros de Operación
                </span>
                <h1 class="font-headline-lg text-headline-lg text-on-surface mt-1">
                    Configuración del Sistema
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span x-show="guardado" x-transition class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary/15 text-tertiary font-label-md text-label-md border border-tertiary/30">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>Parámetros guardados con éxito</span>
                </span>

                <button @click="guardar()" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-primary text-on-primary hover:bg-primary-container transition-colors font-label-md text-label-md font-medium shadow-md">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 1. ENVIRONMENTAL THRESHOLDS SECTION                          --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card border border-outline-variant/30">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-outline-variant/30">
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">tune</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Umbrales Ambientales de Telemetría</h2>
                    <p class="font-body-sm text-body-sm text-outline">Límites de tolerancia para activar estados de Advertencia y Crítico</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Temperatura Mínima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Mínima Óptima (°C)</label>
                    <input type="number" step="0.5" x-model="form.tempMin" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Por debajo se alerta por enfriamiento excesivo</span>
                </div>

                {{-- Temperatura Advertencia --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Máxima Advertencia (°C)</label>
                    <input type="number" step="0.5" x-model="form.tempMax" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-secondary font-code-sm text-code-sm focus:ring-1 focus:ring-secondary focus:border-secondary">
                    <span class="text-[11px] text-outline">Límite operativo estándar ASHRAE (24°C)</span>
                </div>

                {{-- Temperatura Crítica --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Crítica (°C)</label>
                    <input type="number" step="0.5" x-model="form.tempCritica" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-error font-code-sm text-code-sm focus:ring-1 focus:ring-error focus:border-error">
                    <span class="text-[11px] text-outline">Dispara alarma inmediata de sobrecalentamiento</span>
                </div>

                {{-- Humedad Mínima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Mínima Permitida (% RH)</label>
                    <input type="number" step="1" x-model="form.humMin" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Riesgo de descarga electrostática (ESD)</span>
                </div>

                {{-- Humedad Máxima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Máxima Óptima (% RH)</label>
                    <input type="number" step="1" x-model="form.humMax" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-secondary font-code-sm text-code-sm focus:ring-1 focus:ring-secondary focus:border-secondary">
                    <span class="text-[11px] text-outline">Rango recomendado para hardware de racks</span>
                </div>

                {{-- Humedad Crítica --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Crítica (% RH)</label>
                    <input type="number" step="1" x-model="form.humCritica" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-error font-code-sm text-code-sm focus:ring-1 focus:ring-error focus:border-error">
                    <span class="text-[11px] text-outline">Riesgo crítico de condensación y cortocircuito</span>
                </div>

                {{-- Humo Umbral --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Sensibilidad Sensor de Humo (PPM)</label>
                    <input type="number" step="5" x-model="form.humoPpm" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-tertiary font-code-sm text-code-sm focus:ring-1 focus:ring-tertiary focus:border-tertiary">
                    <span class="text-[11px] text-outline">Umbral para declarar presencia de partículas de combustión</span>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 2. MQTT & ESP32 HARDWARE CONNECTION PARAMETERS               --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card border border-outline-variant/30">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-outline-variant/30">
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[20px]">hub</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Parámetros MQTT & Dispositivos ESP32</h2>
                    <p class="font-body-sm text-body-sm text-outline">Configuración de enlace con el broker público HiveMQ y canal de telemetría</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Broker Host --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Servidor Broker MQTT</label>
                    <input type="text" x-model="form.brokerHost" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Broker por defecto: broker.hivemq.com</span>
                </div>

                {{-- WebSocket Port --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Puerto WebSockets Seguro (WSS)</label>
                    <input type="number" x-model="form.brokerPort" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Puerto SSL para navegador (8884)</span>
                </div>

                {{-- Topic --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Tópico de Telemetría</label>
                    <input type="text" x-model="form.topic" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-primary font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Debe coincidir exactamente con el sketch del ESP32</span>
                </div>

                {{-- Intervalo --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Intervalo de Transmisión (Segundos)</label>
                    <input type="number" x-model="form.intervalo" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Cadencia de envío del DHT11 (3 segundos)</span>
                </div>

                {{-- Timeout / Watchdog --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Watchdog de Pérdida de Señal (Segundos)</label>
                    <input type="number" x-model="form.watchdog" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Tiempo sin paquetes antes de marcar ESP32 desconectado</span>
                </div>

                {{-- Zona --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Identificador de Zona Activa</label>
                    <input type="text" x-model="form.zona" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Etiqueta asignada en el payload JSON</span>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 3. ALERTS & OPERATIONAL PREFERENCES                          --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-8 shadow-card border border-outline-variant/30">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-outline-variant/30">
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Notificaciones y Alertas del Operador</h2>
                    <p class="font-body-sm text-body-sm text-outline">Comportamiento sonoro y auditoría de eventos en el panel de control</p>
                </div>
            </div>

            <div class="space-y-6">

                <div class="flex items-center justify-between py-2 border-b border-outline-variant/20">
                    <div>
                        <div class="font-body-md text-body-md font-medium text-on-surface">Alarma Sonora en Navegador</div>
                        <div class="font-body-sm text-body-sm text-outline">Emitir tono de alerta sonora cuando se registre una transgresión de umbral crítico</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.audioAlert" class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between py-2 border-b border-outline-variant/20">
                    <div>
                        <div class="font-body-md text-body-md font-medium text-on-surface">Auditoría Automática de Eventos</div>
                        <div class="font-body-sm text-body-sm text-outline">Registrar automáticamente en la tabla de incidentes cada cambio de estado operativo</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.auditLog" class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>

