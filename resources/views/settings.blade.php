<x-app-layout>
    <div data-settings-root x-data="{
        guardado: false,
        guardando: false,
        form: @js($settings),
        init() {
            const saved = localStorage.getItem('intalnet_settings');
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    this.form = Object.assign({}, this.form, parsed);
                } catch (e) {
                    console.warn('[Intalnet] Error al leer ajustes de localStorage:', e);
                }
            }
        },
        async guardar() {
            this.guardando = true;
            localStorage.setItem('intalnet_settings', JSON.stringify(this.form));

            try {
                const response = await fetch('{{ route('settings.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                if (!response.ok) throw new Error('Error en backend');

                // Enviar umbrales actualizados al hardware ESP32 vía MQTT (con bandera retain=true)
                if (window.mqtt) {
                    const isHttps = window.location.protocol === 'https:';
                    const wsPort = isHttps ? (this.form.brokerWsPortTls || 8884) : (this.form.brokerWsPort || 8000);
                    const wsUrl = `${isHttps ? 'wss' : 'ws'}://${this.form.brokerHost || 'broker.hivemq.com'}:${wsPort}/mqtt`;

                    const client = window.mqtt.connect(wsUrl, {
                        clientId: 'AetherSens-WebConfig-' + Math.random().toString(16).substring(2, 8),
                        clean: true,
                        connectTimeout: 4000
                    });

                    client.on('connect', () => {
                        const topicConfig = this.form.topicConfig || 'intalnet/sala01/config';
                        const configPayload = JSON.stringify({
                            temp_adv: Number(this.form.tempMax),
                            temp_crit: Number(this.form.tempCritica),
                            gas_humo: Number(this.form.gasAdcUmbral)
                        });

                        client.publish(topicConfig, configPayload, { retain: true, qos: 1 }, (err) => {
                            if (!err) {
                                console.log('[AetherSens] Límites transmitidos al ESP32 vía MQTT (retained):', configPayload);
                            }
                            client.end();
                        });
                    });
                }
            } catch (err) {
                console.warn('[Intalnet] Ajustes guardados en navegador; error al sincronizar backend o MQTT:', err);
            } finally {
                this.guardando = false;
                this.guardado = true;
                setTimeout(() => this.guardado = false, 4000);
            }
        }
    }" class="flex flex-col w-full gap-y-6 sm:gap-y-8">

        {{-- ============================================================ --}}
        {{-- PAGE HEADER                                                  --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <span class="font-label-sm text-[11px] sm:text-label-sm uppercase tracking-wider text-outline block">
                    AetherSens Enterprise &bull; Parámetros de Operación &bull; Montería, CO
                </span>
                <h1 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface mt-1">
                    Configuración del Sistema
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span x-show="guardado" x-transition class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary/15 text-tertiary font-label-md text-xs sm:text-label-md border border-tertiary/30">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>Parámetros guardados y sincronizados</span>
                </span>

                <button @click="guardar()" :disabled="guardando" class="flex items-center gap-2 px-4 sm:px-5 py-2 rounded-lg bg-primary text-on-primary hover:bg-primary-container transition-colors font-label-md text-xs sm:text-label-md font-medium shadow-md cursor-pointer disabled:opacity-50">
                    <span class="material-symbols-outlined text-[18px]" :class="guardando ? 'animate-spin' : ''">
                        <span x-text="guardando ? 'sync' : 'save'">save</span>
                    </span>
                    <span x-text="guardando ? 'Guardando...' : 'Guardar Cambios'">Guardar Cambios</span>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 1. ENVIRONMENTAL THRESHOLDS SECTION                          --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-4 sm:p-6 lg:p-8 shadow-card border border-outline-variant/30">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-outline-variant/30">
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">tune</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Umbrales Ambientales de Telemetría (ASHRAE TC 9.9)</h2>
                    <p class="font-body-sm text-body-sm text-outline">Calibración adaptada al clima cálido de Montería y climatización por aire acondicionado (16°C mín)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Temperatura Mínima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Mínima Óptima (°C)</label>
                    <input type="number" step="0.5" x-model.number="form.tempMin" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Límite para prevenir condensación y sobreenfriamiento con aires a 16°C</span>
                </div>

                {{-- Temperatura Advertencia --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Máxima Advertencia (°C)</label>
                    <input type="number" step="0.5" x-model.number="form.tempMax" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-secondary font-code-sm text-code-sm focus:ring-1 focus:ring-secondary focus:border-secondary">
                    <span class="text-[11px] text-outline">TEMP_UMBRAL_ADVERTENCIA del firmware (28.0 °C)</span>
                </div>

                {{-- Temperatura Crítica --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Temperatura Crítica (°C)</label>
                    <input type="number" step="0.5" x-model.number="form.tempCritica" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-error font-code-sm text-code-sm focus:ring-1 focus:ring-error focus:border-error">
                    <span class="text-[11px] text-outline">TEMP_UMBRAL_CRITICO del firmware (35.0 °C) — LED rojo + buzzer</span>
                </div>

                {{-- Humedad Mínima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Mínima Permitida (% RH)</label>
                    <input type="number" step="1" x-model.number="form.humMin" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Piso para evitar descargas electrostáticas (ESD) (40% RH)</span>
                </div>

                {{-- Humedad Máxima --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Máxima Óptima (% RH)</label>
                    <input type="number" step="1" x-model.number="form.humMax" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-secondary font-code-sm text-code-sm focus:ring-1 focus:ring-secondary focus:border-secondary">
                    <span class="text-[11px] text-outline">Rango seguro en el clima tropical de Montería (60% RH)</span>
                </div>

                {{-- Humedad Crítica --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Humedad Crítica (% RH)</label>
                    <input type="number" step="1" x-model.number="form.humCritica" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-error font-code-sm text-code-sm focus:ring-1 focus:ring-error focus:border-error">
                    <span class="text-[11px] text-outline">Riesgo inminente de condensación corrosiva sobre circuitos (75% RH)</span>
                </div>

                {{-- Humo Umbral --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Sensibilidad Sensor MQ-2 (ADC)</label>
                    <input type="number" step="50" x-model.number="form.gasAdcUmbral" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-tertiary font-code-sm text-code-sm focus:ring-1 focus:ring-tertiary focus:border-tertiary">
                    <span class="text-[11px] text-outline">MQ2_UMBRAL_HUMO del firmware (1500 ADC sobre 0–4095) — dispara estado HUMO_DETECTADO</span>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 2. MQTT & ESP32 HARDWARE CONNECTION PARAMETERS               --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-4 sm:p-6 lg:p-8 shadow-card border border-outline-variant/30">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-outline-variant/30">
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[20px]">hub</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Parámetros MQTT & Dispositivos ESP32</h2>
                    <p class="font-body-sm text-body-sm text-outline">Enlace con el broker MQTT (puerto nativo del ESP32 y WebSocket para el navegador) y tópicos del proyecto Intalnet</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Broker Host --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Servidor Broker MQTT</label>
                    <input type="text" x-model="form.brokerHost" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Broker por defecto: broker.hivemq.com</span>
                </div>

                {{-- MQTT Port --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Puerto MQTT Nativo (ESP32)</label>
                    <input type="number" x-model.number="form.brokerPort" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Puerto usado por el sketch del ESP32 (1883)</span>
                </div>

                {{-- WebSocket Port --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Puerto WebSocket (navegador)</label>
                    <input type="number" x-model.number="form.brokerWsPort" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">WebSocket sin cifrar para entornos HTTP locales (8000)</span>
                </div>

                {{-- WebSocket TLS Port --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Puerto WebSocket Seguro (WSS)</label>
                    <input type="number" x-model.number="form.brokerWsPortTls" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Puerto SSL para el navegador en despliegues HTTPS (8884)</span>
                </div>

                {{-- Topic Telemetría --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Tópico de Telemetría</label>
                    <input type="text" x-model="form.topic" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-primary font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Debe coincidir exactamente con mqtt_topic_telemetria del sketch</span>
                </div>

                {{-- Topic Estado --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Tópico de Estado del Nodo</label>
                    <input type="text" x-model="form.topicEstado" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-primary font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">mqtt_topic_estado del sketch (mensaje retained ONLINE)</span>
                </div>

                {{-- Topic Alerta --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Tópico de Alertas</label>
                    <input type="text" x-model="form.topicAlerta" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-primary font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">mqtt_topic_alerta del sketch (cambios de estado y silenciamiento)</span>
                </div>

                {{-- Topic Configuración Remota (ESP32) --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Tópico de Configuración Remota (ESP32)</label>
                    <input type="text" x-model="form.topicConfig" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-primary font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">mqtt_topic_config del sketch para actualizar umbrales dinámicos</span>
                </div>

                {{-- Intervalo --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Intervalo de Transmisión (Segundos)</label>
                    <input type="number" x-model.number="form.intervalo" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Cadencia de envío del ESP32 (3 segundos)</span>
                </div>

                {{-- Timeout / Watchdog --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Watchdog de Pérdida de Señal (Segundos)</label>
                    <input type="number" x-model.number="form.watchdog" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Tiempo sin paquetes antes de marcar ESP32 desconectado</span>
                </div>

                {{-- Zona --}}
                <div class="space-y-1.5">
                    <label class="block font-label-sm text-label-sm text-on-surface">Identificador de Zona Activa</label>
                    <input type="text" x-model="form.zona" class="w-full bg-surface-container-high border border-outline-variant/40 rounded-lg px-3 py-2 text-on-surface font-code-sm text-code-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="text-[11px] text-outline">Etiqueta mostrada junto al estado del nodo</span>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 3. ALERTS & OPERATIONAL PREFERENCES                          --}}
        {{-- ============================================================ --}}
        <div class="rounded-xl bg-surface-container p-4 sm:p-6 lg:p-8 shadow-card border border-outline-variant/30">
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

