# AetherSens Enterprise &bull; Plataforma de Telemetría Ambiental IoT

[![Status: Producción](https://img.shields.io/badge/Estado-Producci%C3%B3n-00c853.svg)](https://github.com)
[![PHP: ^8.3](https://img.shields.io/badge/PHP-%5E8.3-777bb4.svg)](https://php.net)
[![Laravel: 13.x](https://img.shields.io/badge/Laravel-13.x-ff2d20.svg)](https://laravel.com)
[![Livewire: 3.x](https://img.shields.io/badge/Livewire-3.x-fb70a9.svg)](https://livewire.laravel.com)
[![MQTT: HiveMQ](https://img.shields.io/badge/MQTT-HiveMQ%20Broker-00b0ff.svg)](https://hivemq.com)
[![Normativa: ASHRAE TC 9.9](https://img.shields.io/badge/Normativa-ASHRAE%20TC%209.9-ffb300.svg)](https://tc0909.ashraetcs.org)

**AetherSens** es una plataforma integral de supervisión, auditoría y alerta temprana en tiempo real para centros de datos, cuartos de telecomunicaciones y salas de servidores. Diseñada específicamente para operar de forma resiliente bajo las condiciones climáticas extremas de **Montería (Córdoba, Colombia)**, integrando microcontroladores **ESP32**, sensores digitales **DHT11**, sensores de humo/gases **MQ-2** y transporte de telemetría de ultra-baja latencia sobre **MQTT**.

---

## 🏛️ Arquitectura del Sistema

```text
  [ Sensor DHT11 (Temp/Hum) ]
             +
  [ Sensor MQ-2 (Humo/Gas)  ]
             │
             ▼  (GPIO 4 / ADC 34)
   ┌───────────────────────┐
   │  ESP32 DevKit v1      │ ── Cadencia: 3s (Cálculo Heat Index)
   └───────────────────────┘
             │  (WiFi WPA2 Enterprise / VLAN 402)
             ▼
   ┌───────────────────────┐
   │ Broker MQTT (HiveMQ)  │ ── Host: broker.hivemq.com
   │ (Puerto 8884 WSS/TLS) │ ── Tópico: pas_iot/datacenter/telemetria
   └───────────────────────┘
             │  (WebSockets seguros en tiempo real)
             ▼
   ┌────────────────────────────────────────────────────────┐
   │             AetherSens Web Dashboard                   │
   │   Laravel 13 + Livewire 3 + Alpine.js + ApexCharts     │
   │                                                        │
   │  - Gráfica dual en tiempo real (UTC -5)                │
   │  - Tarjetas reactivas de KPI con umbrales dinámicos    │
   │  - Respaldo de clima real con Open-Meteo (Montería)    │
   │  - Registro de incidentes y persistencia de ajustes    │
   └────────────────────────────────────────────────────────┘
```

---

## 🌡️ Calibración Térmica (ASHRAE TC 9.9 - Montería)

En Montería, la humedad ambiental exterior oscila entre el **70% y el 85% RH** con temperaturas de hasta 38°C. Operar sistemas de aire acondicionado en el setpoint mínimo de 16°C provoca condensación inmediata por **punto de rocío** sobre placas electrónicas y escarcha en los evaporadores.

Por ello, AetherSens implementa los umbrales recomendados por **ASHRAE TC 9.9 Clase A1**:

| Variable | Rango Óptimo | Advertencia | Nivel Crítico | Justificación Técnica |
| :--- | :---: | :---: | :---: | :--- |
| **Temperatura** | **18.0 °C – 24.0 °C** | **24.1 °C – 27.9 °C** | **≥ 28.0 °C** *(o < 15 °C)* | Previene *thermal throttling* en CPU y congelamiento de serpentines. |
| **Humedad Relativa** | **40.0% – 60.0% RH** | **30%–39% / 61%–74%** | **≥ 75.0%** *(o < 25 %)* | Evita descargas electrostáticas (ESD) y corrosión acelerada. |
| **Detección de Humo** | **0 PPM (Normal)** | **15 – 49 PPM** | **≥ 50 PPM** | Alerta temprana antes de la ignición por sobrecalentamiento. |

---

## ⚡ Especificaciones de Conexión de Hardware (ESP32)

- **Microcontrolador:** ESP32 NodeMCU DevKit v1 (30/38 pines).
- **Sensor DHT11:**
  - `VCC` ──> `3.3V` / `5V`
  - `DATA` ──> `GPIO 4` (con resistencia pull-up de 10 kΩ a VCC)
  - `GND` ──> `GND`
- **Sensor de Humo MQ-2:**
  - `VCC` ──> `5V` (alimentación de filamento)
  - `AOUT` ──> `GPIO 34` (Canal ADC analógico)
  - `GND` ──> `GND`

### Estructura del Payload MQTT (JSON)
```json
{
  "temperatura": 21.8,
  "humedad": 46.5,
  "sensacion_termica": 22.1,
  "humo_ppm": 0,
  "zona": "Zona A - Sala de Racks (Montería)",
  "timestamp": "2026-10-31T18:45:00-05:00",
  "device_id": "ESP32_SRV_RACK_01"
}
```

---

## 🚀 Requisitos e Instalación

### Requisitos del Sistema
- **PHP:** 8.3 o superior (probado en PHP 8.5) con extensiones `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `curl`.
- **Node.js:** 18.x o superior con **NPM**.
- **Composer:** 2.x.
- **Base de Datos:** SQLite (desarrollo local) o MariaDB/MySQL (producción).

### Puesta en Marcha Rápida

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-organizacion/aethersens.git
   cd aethersens
   ```

2. **Instalar dependencias de backend y frontend:**
   ```bash
   composer install
   npm install
   ```

3. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Ejecutar migraciones y semillero inicial:**
   ```bash
   php artisan migrate --seed
   ```
   *Crea el usuario base:* `operador@aethersens.internal` (Contraseña: `password`).

5. **Compilar recursos estáticos de Vite:**
   ```bash
   npm run build
   ```

6. **Iniciar el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```
   El panel estará accesible en `http://localhost:8000`.

---

## 📂 Módulos de la Plataforma

- **`/` (Landing Page):** Portal institucional con indicadores de salud física, contención de pasillo frío y navegación anclada.
- **`/dashboard`:** Tablero de supervisión con telemetría en tiempo real, gráfica de doble eje UTC-5 sincronizada, estado del ESP32 y tabla de incidentes recientes.
- **`/historical-data`:** Análisis retrospectivo y tendencias de temperatura y humedad.
- **`/alert-logs`:** Registro cronológico de eventos críticos, advertencias y anomalías.
- **`/settings`:** Configuración persistente en tiempo real de umbrales ambientales, parámetros MQTT y tiempos de watchdog (`storage/app/settings.json` + `localStorage`).
- **`/docs`:** Centro oficial de documentación y descargas de manuales en texto y esquemas técnicos.

---

## 🔒 Seguridad y Privacidad

- Autenticación multifactorial (2FA / Google Authenticator) y soporte para llaves de seguridad Passkeys.
- Transmisión telemétrica opcional sobre WSS (WebSockets seguros en puerto 8884 con TLSv1.3).
- Registro auditado de eventos de sesión conforme a estándares de infraestructura crítica.

---

## 📄 Licencia

Este proyecto está licenciado bajo los términos de la **Licencia MIT**. Consulte el archivo `LICENSE` para más información.
