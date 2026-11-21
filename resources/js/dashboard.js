import ApexCharts from 'apexcharts';
import mqtt from 'mqtt';

window.ApexCharts = ApexCharts;
window.mqtt = mqtt;

const STORAGE_KEY = 'intalnet_settings';
const TELEMETRY_STORAGE_KEY = 'intalnet_telemetry_history';
const INCIDENTS_STORAGE_KEY = 'intalnet_incident_logs';
const MAX_CHART_POINTS = 60;
const MAX_ALERT_ROWS = 150;
const MAX_STORED_SAMPLES = 1000;
const MAX_STORED_INCIDENTS = 150;
const ADC_FULL_SCALE = 4095;

const DEFAULT_SETTINGS = {
    brokerHost: 'broker.hivemq.com',
    brokerPort: 1883,
    brokerWsPort: 8000,
    brokerWsPortTls: 8884,
    topic: 'intalnet/sala01/telemetria',
    topicEstado: 'intalnet/sala01/estado',
    topicAlerta: 'intalnet/sala01/alerta',
    topicConfig: 'intalnet/sala01/config',
    tempMin: 18,
    tempMax: 35,
    tempCritica: 38,
    humMin: 40,
    humMax: 60,
    humCritica: 75,
    gasAdcUmbral: 1800,
    intervalo: 5,
    watchdog: 10,
    zona: 'Sala 01 - Intalnet (Montería)',
    audioAlert: true,
};

const ESP_STATE_MAP = {
    NORMAL: { status: 'normal', label: 'Normal' },
    ADVERTENCIA: { status: 'warning', label: 'Advertencia' },
    CRITICO: { status: 'critical', label: 'Crítico' },
    HUMO_DETECTADO: { status: 'critical', label: 'Humo Detectado' },
};

const STATUS_STYLES = {
    normal: { badge: 'bg-tertiary/10 text-tertiary', dot: 'bg-tertiary', label: 'Normal' },
    warning: { badge: 'bg-secondary/10 text-secondary', dot: 'bg-secondary', label: 'Advertencia' },
    critical: { badge: 'bg-error/10 text-error', dot: 'bg-error', label: 'Crítico' },
};

const numberOr = (value, fallback) => {
    const parsed = parseFloat(value);
    return Number.isFinite(parsed) ? parsed : fallback;
};

const formatUtcMinus5 = (date = new Date()) => {
    try {
        return new Intl.DateTimeFormat('es-CO', {
            timeZone: 'America/Bogota',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }).format(date);
    } catch (error) {
        return date.toISOString().slice(11, 19);
    }
};

const formatUptime = (seconds) => {
    const total = Math.max(0, Math.floor(numberOr(seconds, 0)));
    const days = Math.floor(total / 86400);
    const hours = Math.floor((total % 86400) / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const rest = total % 60;
    const clock = [hours, minutes, rest].map((unit) => String(unit).padStart(2, '0')).join(':');

    return days > 0 ? `${days}d ${clock}` : clock;
};

const readSettings = () => {
    let settings = { ...DEFAULT_SETTINGS };

    const configNode = document.getElementById('dashboard-config');
    if (configNode) {
        try {
            settings = { ...settings, ...JSON.parse(configNode.textContent) };
        } catch (error) {
            console.warn('[Intalnet] No se pudo leer la configuración del servidor:', error);
        }
    }

    try {
        const stored = window.localStorage.getItem(STORAGE_KEY);
        if (stored) {
            settings = { ...settings, ...JSON.parse(stored) };
        }
    } catch (error) {
        console.warn('[Intalnet] No se pudo leer la configuración local:', error);
    }

    return settings;
};

const buildBrokerUrl = (settings, secure) => {
    const host = settings.brokerHost || DEFAULT_SETTINGS.brokerHost;
    const port = secure
        ? numberOr(settings.brokerWsPortTls, DEFAULT_SETTINGS.brokerWsPortTls)
        : numberOr(settings.brokerWsPort, DEFAULT_SETTINGS.brokerWsPort);

    return `${secure ? 'wss' : 'ws'}://${host}:${port}/mqtt`;
};

export function initDashboard() {
    const root = document.querySelector('[data-dashboard-root]');

    if (!root) {
        return;
    }

    const settings = readSettings();
    const chartElement = document.getElementById('telemetry-chart');

    let chart = null;
    let client = null;
    let watchdogTimer = null;
    let lastEspState = null;
    let lastAlertKey = null;
    let lastAlertAt = 0;
    let useSecureSocket = window.location.protocol === 'https:';
    let reconnectAttempt = 0;

    let temperatureSeries = [];
    let humiditySeries = [];

    const element = (id) => document.getElementById(id);

    const setText = (id, value) => {
        const node = element(id);
        if (node) {
            node.textContent = value;
        }
    };

    const setBadge = (id, status, label) => {
        const badge = element(id);
        if (!badge) {
            return;
        }

        const style = STATUS_STYLES[status] || STATUS_STYLES.normal;
        const dot = badge.querySelector('.rounded-full');
        const text = badge.querySelector('.status-text');

        badge.className = `px-2.5 py-1 rounded-md font-label-sm text-label-sm flex items-center gap-1.5 transition-colors ${style.badge}`;

        if (dot) {
            dot.className = `w-1.5 h-1.5 rounded-full ${style.dot}`;
        }

        if (text) {
            text.textContent = label || style.label;
        }
    };

    const setConnectionState = (state, message) => {
        const dot = element('esp32-status-dot');
        const text = element('esp32-status-text');

        if (dot) {
            const dotClasses = {
                connecting: 'w-2 h-2 rounded-full bg-secondary animate-pulse',
                broker: 'w-2 h-2 rounded-full bg-secondary',
                live: 'w-2 h-2 rounded-full bg-tertiary animate-pulse',
                stale: 'w-2 h-2 rounded-full bg-secondary',
                error: 'w-2 h-2 rounded-full bg-error',
            };

            dot.className = dotClasses[state] || dotClasses.broker;
        }

        if (text) {
            text.textContent = message;
        }

        const headerDot = element('esp32-header-dot');
        const headerText = element('esp32-header-status');

        if (headerDot) {
            const dotClasses = {
                connecting: 'w-2 h-2 rounded-full bg-secondary animate-pulse',
                broker: 'w-2 h-2 rounded-full bg-secondary',
                live: 'w-2 h-2 rounded-full bg-tertiary animate-pulse',
                stale: 'w-2 h-2 rounded-full bg-secondary',
                error: 'w-2 h-2 rounded-full bg-error',
            };

            headerDot.className = dotClasses[state] || dotClasses.broker;
        }

        if (headerText) {
            headerText.textContent = `ESP32: ${message.replace(/^ESP32\s+/i, '')}`;
            headerText.className = `font-code-sm text-code-sm ${
                state === 'live'
                    ? 'text-tertiary'
                    : (state === 'error' ? 'text-error' : 'text-secondary')
            }`;
        }

        window.dispatchEvent(new CustomEvent('intalnet:esp-status', { detail: { state, message } }));
    };

    const evaluateTemperature = (value) => {
        const min = numberOr(settings.tempMin, DEFAULT_SETTINGS.tempMin);
        const max = numberOr(settings.tempMax, DEFAULT_SETTINGS.tempMax);
        const critical = numberOr(settings.tempCritica, DEFAULT_SETTINGS.tempCritica);

        if (value >= critical) {
            return 'critical';
        }

        if (value > max || value < min) {
            return 'warning';
        }

        return 'normal';
    };

    const evaluateHumidity = (value) => {
        const min = numberOr(settings.humMin, DEFAULT_SETTINGS.humMin);
        const max = numberOr(settings.humMax, DEFAULT_SETTINGS.humMax);
        const critical = numberOr(settings.humCritica, DEFAULT_SETTINGS.humCritica);

        if (value >= critical || value < min - 10) {
            return 'critical';
        }

        if (value > max || value < min) {
            return 'warning';
        }

        return 'normal';
    };

    const evaluateGas = (value) => {
        const threshold = numberOr(settings.gasAdcUmbral, DEFAULT_SETTINGS.gasAdcUmbral);
        return value >= threshold ? 'critical' : 'normal';
    };

    const calculateHeatIndex = (tempC, humRH) => {
        if (tempC === null || tempC === undefined || humRH === null || humRH === undefined) {
            return null;
        }
        const T = Number(tempC);
        const RH = Number(humRH);
        if (!Number.isFinite(T) || !Number.isFinite(RH)) {
            return null;
        }

        if (T < 20) {
            return T;
        }

        const Tf = T * 1.8 + 32;
        let hiF = 0.5 * (Tf + 61.0 + ((Tf - 68.0) * 1.2) + (RH * 0.094));

        if (hiF >= 80) {
            hiF = -42.379 + 2.04901523 * Tf + 10.14333127 * RH
                - 0.22475541 * Tf * RH - 0.00683783 * Tf * Tf
                - 0.05481717 * RH * RH + 0.00122874 * Tf * Tf * RH
                + 0.00085282 * Tf * RH * RH - 0.00000199 * Tf * Tf * RH * RH;
        }

        const hiC = (hiF - 32) / 1.8;
        return Number(hiC.toFixed(1));
    };

    const applyThresholdsToLabels = () => {
        setText('metric-temp-range', `Rango operativo: ${settings.tempMin}°C – ${settings.tempMax}°C`);
        setText('metric-hum-range', `Rango operativo: ${settings.humMin}% – ${settings.humMax}%`);
        setText('metric-gas-threshold', String(settings.gasAdcUmbral));

        const marker = element('metric-gas-marker');
        if (marker) {
            const position = Math.min(100, (numberOr(settings.gasAdcUmbral, DEFAULT_SETTINGS.gasAdcUmbral) / ADC_FULL_SCALE) * 100);
            marker.style.left = `${position}%`;
        }

        setText('esp32-zone-text', settings.zona || DEFAULT_SETTINGS.zona);
    };

    const renderChart = () => {
        if (!chartElement) {
            return;
        }

        const options = {
            series: [
                { name: 'Temperatura', data: [...temperatureSeries] },
                { name: 'Humedad', data: [...humiditySeries] },
            ],
            chart: {
                type: 'area',
                height: 290,
                animations: { enabled: true, easing: 'linear', dynamicAnimation: { speed: 500 } },
                toolbar: { show: false },
                background: 'transparent',
                fontFamily: "'Geist', sans-serif",
            },
            colors: ['#a4c9ff', '#77d6d3'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: [2.5, 2] },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.28, opacityTo: 0.02, stops: [0, 95, 100] },
            },
            grid: {
                borderColor: '#414751',
                strokeDashArray: 3,
                opacity: 0.4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 10, right: 20, bottom: 0, left: 20 },
            },
            legend: { show: false },
            xaxis: {
                type: 'datetime',
                tickAmount: 5,
                labels: {
                    style: { colors: '#8b919d', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                    hideOverlappingLabels: true,
                    formatter: (value, timestamp) => formatUtcMinus5(new Date(timestamp ?? value)),
                },
                axisBorder: { show: true, color: '#414751' },
                axisTicks: { show: true, color: '#414751' },
                tooltip: { enabled: false },
            },
            yaxis: [
                {
                    seriesName: 'Temperatura',
                    min: (min) => (Number.isFinite(min) ? Math.min(15, Math.floor((min - 2) / 5) * 5) : 15),
                    max: (max) => (Number.isFinite(max) ? Math.max(35, Math.ceil((max + 2) / 5) * 5) : 35),
                    tickAmount: 4,
                    axisTicks: { show: true, color: '#a4c9ff' },
                    axisBorder: { show: true, color: '#a4c9ff' },
                    labels: {
                        style: { colors: '#a4c9ff', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                        formatter: (value) => (value === null || value === undefined ? '' : `${Number(value).toFixed(1)} °C`),
                    },
                    title: {
                        text: 'Temperatura (°C)',
                        style: { color: '#a4c9ff', fontSize: '12px', fontFamily: "'Geist', sans-serif", fontWeight: 600 },
                    },
                },
                {
                    opposite: true,
                    seriesName: 'Humedad',
                    min: 0,
                    max: 100,
                    tickAmount: 4,
                    axisTicks: { show: true, color: '#77d6d3' },
                    axisBorder: { show: true, color: '#77d6d3' },
                    labels: {
                        style: { colors: '#77d6d3', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                        formatter: (value) => (value === null || value === undefined ? '' : `${Math.round(Number(value))} %`),
                    },
                    title: {
                        text: 'Humedad (% RH)',
                        style: { color: '#77d6d3', fontSize: '12px', fontFamily: "'Geist', sans-serif", fontWeight: 600 },
                    },
                },
            ],
            tooltip: {
                theme: 'dark',
                shared: true,
                intersect: false,
                style: { fontSize: '12px', fontFamily: "'Geist', sans-serif" },
                x: {
                    formatter: (value) => `Hora (UTC -5): ${formatUtcMinus5(new Date(value))}`,
                },
                y: {
                    formatter: (value, context) => {
                        if (value === null || value === undefined) {
                            return '--';
                        }

                        return context.seriesIndex === 0
                            ? `${Number(value).toFixed(1)} °C`
                            : `${Number(value).toFixed(1)} % RH`;
                    },
                },
            },
        };

        chart = new ApexCharts(chartElement, options);
        chart.render();
    };

    const pushPoint = (temperature, humidity, timestamp) => {
        temperatureSeries.push([timestamp, temperature]);
        humiditySeries.push([timestamp, humidity]);

        while (temperatureSeries.length > MAX_CHART_POINTS) {
            temperatureSeries.shift();
            humiditySeries.shift();
        }

        if (chart) {
            chart.updateSeries([
                { name: 'Temperatura', data: [...temperatureSeries] },
                { name: 'Humedad', data: [...humiditySeries] },
            ], true);
        }
    };

    // ══════════════════════════════════════════════════════════════════════════
    // NOTIFICACIONES PUSH DEL NAVEGADOR (Web Notification API) & TONO DE AUDIO
    // ══════════════════════════════════════════════════════════════════════════

    let notificationPermission = typeof Notification !== 'undefined' ? Notification.permission : 'denied';

    const requestNotificationPermission = () => {
        if (typeof Notification === 'undefined') return;
        if (Notification.permission === 'default') {
            Notification.requestPermission().then((permission) => {
                notificationPermission = permission;
                console.info(`[AetherSens] Permiso de notificaciones: ${permission}`);
            });
        } else {
            notificationPermission = Notification.permission;
        }
    };

    requestNotificationPermission();

    window.addEventListener('intalnet:request-push-perm', () => {
        if (typeof Notification === 'undefined') {
            alert('Este navegador no soporta Notificaciones Web.');
            return;
        }
        Notification.requestPermission().then((permission) => {
            notificationPermission = permission;
            if (permission === 'granted') {
                sendBrowserNotification(
                    '🔔 Notificaciones AetherSens Activas',
                    'Las notificaciones Web Push han sido habilitadas exitosamente para este sistema de monitoreo.',
                    'normal'
                );
            }
        });
    });

    const playAudioAlert = (severity = 'critical') => {
        if (!settings.audioAlert) return;
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = severity === 'critical' ? 'sawtooth' : 'sine';
            osc.frequency.setValueAtTime(severity === 'critical' ? 880 : 587, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(severity === 'critical' ? 440 : 880, ctx.currentTime + 0.25);
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {}
    };

    let lastNotificationKey = null;
    let lastNotificationAt = 0;

    const sendBrowserNotification = (title, body, severity = 'warning') => {
        playAudioAlert(severity);

        // Vibración física en teléfonos celulares (Android / iOS soportados)
        if (typeof navigator !== 'undefined' && 'vibrate' in navigator) {
            try {
                navigator.vibrate(severity === 'critical' ? [300, 150, 300, 150, 300] : [200, 100, 200]);
            } catch (e) {}
        }

        if (typeof Notification === 'undefined' || Notification.permission !== 'granted') return;

        // Anti-spam: agrupar por categoría/título para evitar disparos múltiples por pequeños cambios de ADC o temperatura
        const categoryKey = title.trim();
        const now = Date.now();
        if (categoryKey === lastNotificationKey && now - lastNotificationAt < 15000) {
            return;
        }
        lastNotificationKey = categoryKey;
        lastNotificationAt = now;

        const iconEmoji = severity === 'critical' ? '🚨' : (severity === 'warning' ? '⚠️' : '🔔');

        const notificationOptions = {
            body: `${iconEmoji} ${body}`,
            icon: '/favicon.png',
            badge: '/favicon.png',
            tag: 'aethersens-active-alert',
            vibrate: severity === 'critical' ? [300, 150, 300, 150, 300] : [200, 100, 200],
            requireInteraction: severity === 'critical',
            silent: false,
            data: { url: '/dashboard' }
        };

        // En dispositivos móviles, la forma oficial y soportada es a través del Service Worker
        if ('serviceWorker' in navigator && navigator.serviceWorker.ready) {
            navigator.serviceWorker.ready.then((registration) => {
                registration.showNotification(title, notificationOptions);
            }).catch(() => {
                tryFallbackNotification(title, notificationOptions, severity);
            });
        } else {
            tryFallbackNotification(title, notificationOptions, severity);
        }
    };

    const tryFallbackNotification = (title, options, severity) => {
        try {
            const notification = new Notification(title, options);
            notification.onclick = () => {
                window.focus();
                notification.close();
            };
            if (severity !== 'critical') {
                setTimeout(() => notification.close(), 8000);
            }
        } catch (e) {
            console.warn('[AetherSens] Error al crear notificación estándar:', e);
        }
    };

    const addAlertRow = ({ event, detail, status, key }) => {
        const tbody = element('telemetry-table-body');

        if (!tbody) {
            return;
        }

        const now = Date.now();
        if (key && key === lastAlertKey && now - lastAlertAt < 5000) {
            return;
        }

        lastAlertKey = key || null;
        lastAlertAt = now;

        const emptyRow = element('telemetry-alert-empty');
        if (emptyRow) {
            emptyRow.remove();
        }

        const style = STATUS_STYLES[status] || STATUS_STYLES.normal;
        const row = document.createElement('tr');
        row.className = 'hover:bg-surface-container-high/60 transition-colors bg-surface-container/20';
        row.innerHTML = `
            <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface-variant">${formatUtcMinus5()}</td>
            <td class="py-5 px-8 font-medium text-on-surface">${event}</td>
            <td class="py-5 px-8 font-code-sm text-code-sm text-on-surface">${detail}</td>
            <td class="py-5 px-8 text-right">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-sm text-label-sm ${style.badge}">
                    <span class="w-1.5 h-1.5 rounded-full ${style.dot}"></span>
                    ${style.label}
                </span>
            </td>
        `;

        tbody.insertBefore(row, tbody.firstChild);

        while (tbody.children.length > MAX_ALERT_ROWS) {
            tbody.removeChild(tbody.lastChild);
        }

        const counter = element('telemetry-alert-count');
        if (counter) {
            counter.textContent = `${tbody.children.length} registros`;
        }

        // Guardar en el log persistente de incidentes ÚNICAMENTE si es una condición de alerta real (warning o critical)
        if (status === 'critical' || status === 'warning') {
            try {
                const rawInc = window.localStorage.getItem(INCIDENTS_STORAGE_KEY);
                const incList = rawInc ? JSON.parse(rawInc) : [];
                const dateObj = new Date();
                const dayStr = String(dateObj.getDate()).padStart(2, '0');
                const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                const displayDate = `${dayStr} ${months[dateObj.getMonth()]}`;

                incList.unshift({
                    id: Date.now() + Math.floor(Math.random() * 1000),
                    fecha: dateObj.toISOString().slice(0, 10),
                    fechaDisplay: displayDate,
                    hora: formatUtcMinus5(),
                    sensor: event.includes('Humo') ? 'Detección de Humo' : (event.includes('Humedad') ? 'Humedad Relativa' : 'Temperatura'),
                    zona: settings.zona || DEFAULT_SETTINGS.zona,
                    valor: detail,
                    umbral: status === 'critical' ? 'Umbral Crítico' : 'Umbral Advertencia',
                    severidad: status === 'critical' ? 'critical' : 'warning',
                    estado: 'Pendiente',
                    label: status === 'critical' ? 'Crítico' : 'Advertencia'
                });
                while (incList.length > MAX_STORED_INCIDENTS) {
                    incList.pop();
                }
                window.localStorage.setItem(INCIDENTS_STORAGE_KEY, JSON.stringify(incList));
            } catch (e) {}

            // Disparar notificación push al navegador
            sendBrowserNotification(
                status === 'critical' ? `🚨 Alerta Crítica · ${event}` : `⚠️ Advertencia · ${event}`,
                detail,
                status
            );
        }
    };

    const renderBuzzerState = (silenced, state) => {
        const critical = state === 'ESTADO_CRITICO' || state === 'ESTADO_HUMO' || state === 'CRITICO' || state === 'HUMO_DETECTADO';
        const value = silenced ? 'SILENCIADO' : (critical ? 'ACTIVO' : 'EN REPOSO');
        const status = silenced ? 'warning' : (critical ? 'critical' : 'normal');

        setText('metric-buzzer-val', value);
        setBadge('metric-buzzer-badge', status, silenced ? 'Silenciado' : (critical ? 'Alarma' : 'Inactivo'));
        setText('metric-buzzer-detail', silenced
            ? 'El operador accionó el pulsador industrial NO'
            : (critical ? 'Alarma sonora activa por condición crítica' : 'Sin condiciones que requieran alarma'));

        // Emitir evento para que el botón de silenciamiento en el header conozca el estado
        window.dispatchEvent(new CustomEvent('intalnet:alarm-state', {
            detail: {
                estado: state?.replace('ESTADO_', '') || 'NORMAL',
                silenciado: silenced,
            }
        }));
    };

    const handleTelemetry = (payload) => {
        const timestamp = Date.now();
        const time = formatUtcMinus5(new Date(timestamp));

        const temperature = numberOr(payload.temperatura, null);
        const humidity = numberOr(payload.humedad_relativa ?? payload.humedad, null);
        const gas = numberOr(payload.nivel_gas, null);
        const espState = typeof payload.estado === 'string' ? payload.estado.toUpperCase() : null;
        const silenced = payload.silenciado === true || payload.silenciado === 'true';

        if (temperature !== null) {
            setText('metric-temp-val', temperature.toFixed(1));
            setBadge('metric-temp-badge', evaluateTemperature(temperature));
        }

        if (humidity !== null) {
            setText('metric-hum-val', humidity.toFixed(1));
            setBadge('metric-hum-badge', evaluateHumidity(humidity));
        }

        if (temperature !== null && humidity !== null) {
            const heatIndex = calculateHeatIndex(temperature, humidity);
            if (heatIndex !== null) {
                setText('metric-heat-val', heatIndex.toFixed(1));
                const heatStatus = heatIndex < 27 ? 'normal' : (heatIndex < 35 ? 'warning' : 'critical');
                const heatLabel = heatIndex < 27 ? 'Óptimo' : (heatIndex < 35 ? 'Precaución' : 'Peligro');
                setBadge('metric-heat-badge', heatStatus, heatLabel);
                setText('metric-heat-range', heatIndex < 27
                    ? 'Índice ASHRAE A1 (Confortable)'
                    : (heatIndex < 35 ? 'Bochorno moderado en sala' : 'Riesgo de estrés térmico'));
            }
        }

        if (gas !== null) {
            setText('metric-gas-val', String(Math.round(gas)));
            setBadge('metric-gas-badge', evaluateGas(gas));

            const bar = element('metric-gas-bar');
            if (bar) {
                const percent = Math.min(100, (gas / ADC_FULL_SCALE) * 100);
                bar.style.width = `${percent}%`;
                bar.className = `h-2 rounded-full transition-all duration-300 ${evaluateGas(gas) === 'critical' ? 'bg-error' : 'bg-tertiary'}`;
            }
        }

        if (espState) {
            const mapped = ESP_STATE_MAP[espState] || { status: 'normal', label: espState };
            setText('metric-estado-val', espState);
            setBadge('metric-estado-badge', mapped.status, mapped.label);
            renderBuzzerState(silenced, espState);

            if (lastEspState && lastEspState !== espState) {
                addAlertRow({
                    event: 'Cambio de estado de sala',
                    detail: `${lastEspState} → ${espState}`,
                    status: mapped.status,
                    key: `estado:${espState}`,
                });
            }

            lastEspState = espState;
        }

        setText('metric-uptime-val', formatUptime(payload.uptime_s));
        setText('metric-lastpacket-val', time);

        if (temperature !== null && humidity !== null) {
            pushPoint(temperature, humidity, timestamp);

            try {
                const rawHist = window.localStorage.getItem(TELEMETRY_STORAGE_KEY);
                const hist = rawHist ? JSON.parse(rawHist) : [];
                const heatIdx = calculateHeatIndex(temperature, humidity);

                hist.push({
                    timestamp,
                    time,
                    temp: Number(temperature.toFixed(1)),
                    hum: Number(humidity.toFixed(1)),
                    heat: heatIdx !== null ? Number(heatIdx.toFixed(1)) : Number(temperature.toFixed(1)),
                    gas: gas !== null ? Math.round(gas) : 0,
                    status: espState ? (ESP_STATE_MAP[espState]?.status || 'normal') : 'normal',
                    label: espState ? (ESP_STATE_MAP[espState]?.label || 'Normal') : 'Normal',
                    zona: settings.zona || DEFAULT_SETTINGS.zona,
                });
                while (hist.length > MAX_STORED_SAMPLES) {
                    hist.shift();
                }
                window.localStorage.setItem(TELEMETRY_STORAGE_KEY, JSON.stringify(hist));
            } catch (e) {}

            // Persistencia asíncrona permanente en la Base de Datos central
            try {
                const activeRoom = window.localStorage.getItem('intalnet_selected_room') || 'sala01';
                fetch('/api/telemetry', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        temperatura: temperature,
                        humedad: humidity,
                        sensacion_termica: calculateHeatIndex(temperature, humidity),
                        gas: gas || 0,
                        estado: espState || 'NORMAL',
                        silenciado: silenced,
                        uptime_s: payload.uptime_s || null,
                        dispositivo: payload.dispositivo || 'ESP32_SALA01',
                        sala_id: payload.sala_id || activeRoom,
                        zona: settings.zona || DEFAULT_SETTINGS.zona,
                    })
                }).catch(() => {});
            } catch (e) {}
        }

        const emptyState = element('telemetry-empty-state');
        if (emptyState) {
            emptyState.classList.add('hidden');
        }

        setConnectionState('live', `ESP32 en línea · ${time}`);

        clearTimeout(watchdogTimer);
        watchdogTimer = setTimeout(() => {
            setConnectionState('stale', `ESP32 sin señal (>${settings.watchdog}s)`);
        }, numberOr(settings.watchdog, DEFAULT_SETTINGS.watchdog) * 1000);
    };

    const handleDeviceState = (payload) => {
        const device = payload.dispositivo || 'ESP32_SALA01';
        setText('metric-node-device', device);

        if (String(payload.estado).toUpperCase() === 'ONLINE') {
            setConnectionState('broker', `Broker conectado · ${device} registrado`);
        }
    };

    const handleAlert = (payload) => {
        const event = payload.evento || 'EVENTO';
        const time = formatUtcMinus5();

        if (event === 'CAMBIO_ESTADO') {
            const state = String(payload.nuevo_estado || '').toUpperCase();
            const mapped = ESP_STATE_MAP[state] || { status: 'warning', label: state || 'Cambio' };
            const temperature = numberOr(payload.temperatura, null);
            const gas = numberOr(payload.gas, null);
            const detail = [
                state || '—',
                temperature !== null ? `${temperature.toFixed(1)} °C` : null,
                gas !== null ? `${Math.round(gas)} ADC` : null,
            ].filter(Boolean).join(' · ');

            addAlertRow({
                event: `Cambio de estado: ${mapped.label}`,
                detail,
                status: mapped.status,
                key: `cambio:${state}`,
            });

            return;
        }

        if (event === 'SILENCIADO_MANUAL' || event === 'SILENCIADO_REMOTO') {
            addAlertRow({
                event: 'Buzzer Silenciado',
                detail: payload.mensaje || (event === 'SILENCIADO_REMOTO'
                    ? 'Silenciado remotamente desde el panel web AetherSens'
                    : 'Silenciado mediante pulsador industrial NO'),
                status: 'warning',
                key: `silencio:${time}`,
            });

            // Confirmar al botón del header que la alarma fue silenciada
            window.dispatchEvent(new CustomEvent('intalnet:alarm-silenced-ack'));
            return;
        }

        addAlertRow({
            event: `Alerta ESP32 · ${event}`,
            detail: payload.mensaje || payload.detalle || '—',
            status: 'warning',
            key: `evento:${event}:${time}`,
        });
    };


    // ══════════════════════════════════════════════════════════════════════════
    // SILENCIAMIENTO REMOTO DE ALARMA VÍA MQTT (Web → ESP32)
    // ══════════════════════════════════════════════════════════════════════════

    window.addEventListener('intalnet:silence-alarm', () => {
        if (!client || !client.connected) {
            console.warn('[AetherSens] No se puede silenciar: sin conexión MQTT.');
            return;
        }

        const topicConfig = settings.topicConfig || 'intalnet/sala01/config';
        const payload = JSON.stringify({ silenciar_buzzer: true });

        client.publish(topicConfig, payload, { qos: 1, retain: false }, (err) => {
            if (err) {
                console.error('[AetherSens] Error al enviar comando de silenciamiento:', err);
            } else {
                console.info(`[AetherSens] Comando de silenciamiento enviado a ${topicConfig}`);

                // Registrar en la tabla de alertas localmente de forma inmediata
                addAlertRow({
                    event: 'Acción Operador · Silenciamiento remoto',
                    detail: 'Comando enviado al ESP32 vía MQTT desde el panel web',
                    status: 'warning',
                    key: `web-silence:${Date.now()}`,
                });
            }
        });
    });

    const connect = () => {
        const url = buildBrokerUrl(settings, useSecureSocket);

        setConnectionState('connecting', `Conectando a ${url}...`);

        client = mqtt.connect(url, {
            clientId: `WebDashboard_${Math.random().toString(16).slice(2, 10)}`,
            clean: true,
            connectTimeout: 8000,
            reconnectPeriod: 4000,
            keepalive: 30,
        });

        client.on('connect', () => {
            reconnectAttempt = 0;
            setConnectionState('broker', 'Broker conectado · esperando telemetría ESP32');

            const topics = [settings.topic, settings.topicEstado, settings.topicAlerta];

            client.subscribe(topics, { qos: 0 }, (error) => {
                if (error) {
                    console.error('[Intalnet] Error al suscribirse a los tópicos MQTT:', error);
                    setConnectionState('error', 'Error al suscribirse a los tópicos');
                    return;
                }

                console.info(`[Intalnet] Suscrito a: ${topics.join(', ')}`);
            });
        });

        client.on('message', (topic, message) => {
            let payload;

            try {
                payload = JSON.parse(message.toString());
            } catch (error) {
                console.warn(`[Intalnet] Payload no JSON en ${topic}:`, error);
                return;
            }

            if (topic === settings.topic || topic.endsWith('/telemetria')) {
                handleTelemetry(payload);
                return;
            }

            if (topic === settings.topicEstado || topic.endsWith('/estado')) {
                handleDeviceState(payload);
                return;
            }

            if (topic === settings.topicAlerta || topic.endsWith('/alerta')) {
                handleAlert(payload);
            }
        });

        client.on('error', (error) => {
            console.error('[Intalnet] Error de conexión MQTT:', error);
            setConnectionState('error', 'Error de conexión MQTT (revise la consola)');

            reconnectAttempt += 1;

            if (!useSecureSocket && reconnectAttempt >= 2) {
                useSecureSocket = true;
                client.end(true);
                setTimeout(connect, 1000);
            }
        });

        client.on('offline', () => {
            setConnectionState('stale', 'Broker desconectado · reintentando');
        });

        client.on('reconnect', () => {
            setConnectionState('connecting', 'Reconectando al broker MQTT...');
        });
    };

    applyThresholdsToLabels();
    renderChart();
    connect();

    // Reaccionar a cambios en el selector de salas
    window.addEventListener('intalnet:room-changed', (e) => {
        const roomId = e.detail?.roomId || 'sala01';
        const baseTopic = roomId === 'all' ? 'intalnet/+/telemetria' : `intalnet/${roomId}/telemetria`;
        const estadoTopic = roomId === 'all' ? 'intalnet/+/estado' : `intalnet/${roomId}/estado`;
        const alertaTopic = roomId === 'all' ? 'intalnet/+/alerta' : `intalnet/${roomId}/alerta`;

        if (client && client.connected) {
            try {
                client.unsubscribe([settings.topic, settings.topicEstado, settings.topicAlerta]);
            } catch (err) {}
            settings.topic = baseTopic;
            settings.topicEstado = estadoTopic;
            settings.topicAlerta = alertaTopic;
            client.subscribe([baseTopic, estadoTopic, alertaTopic], { qos: 0 });
        }

        temperatureSeries = [];
        humiditySeries = [];
        if (chart) {
            chart.updateSeries([
                { name: 'Temperatura', data: [] },
                { name: 'Humedad', data: [] },
            ], true);
        }

        const emptyState = element('telemetry-empty-state');
        if (emptyState) emptyState.classList.remove('hidden');

        const zoneEl = element('esp32-zone-text');
        if (zoneEl) {
            zoneEl.textContent = roomId === 'all' ? 'Todas las Salas (NOC)' : `Sala ${roomId.replace('sala', '')}`;
        }
        setConnectionState('broker', `Broker conectado · esperando telemetría de ${roomId}`);
    });

    window.addEventListener('beforeunload', () => {
        clearTimeout(watchdogTimer);

        if (client) {
            client.end(true);
        }
    });
}
