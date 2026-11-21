# Manual de Despliegue y Operación en Producción – AetherSens Enterprise
### Documento Técnico para el Equipo de Infraestructura y Sistemas de Intalnet S.A.S.

---

## 1. Resumen Ejecutivo de la Plataforma

**AetherSens** es la plataforma de telemetría y supervisión ambiental en tiempo real diseñada para el monitoreo continuo de las **Salas Técnicas y Centros de Cómputo de Intalnet (Montería, Córdoba)**.

El sistema garantiza el cumplimiento de las directrices térmicas y climáticas internacionales **ASHRAE TC 9.9**, protegiendo switches de agregación, OLTs de fibra óptica, routers de borde y servidores contra fallas de climatización, sobrecalentamiento y conatos de incendio mediante detección temprana de gases y humo.

### Arquitectura de Componentes
El stack se compone de tres elementos principales comunicados en red:

1. **Nodo Físico IoT (ESP32 en Rack):**
   - Microcontrolador ESP32 de doble núcleo a 240 MHz.
   - Sensor **Sensirion SHT40** de grado industrial (precisión $\pm 0.2^\circ\text{C}$, $\pm 1.8\%\text{ RH}$) conectado por bus $\text{I}^2\text{C}$.
   - Sensor **MQ-2** conectado al conversor analógico-digital (ADC) para detección de humo y gases combustibles.
   - Buzzer piezoeléctrico de alarma sonora local en el rack con posibilidad de silenciamiento remoto por software.
   - Publica datos cada 3 a 5 segundos usando el protocolo ligero **MQTT (Message Queuing Telemetry Transport)**.

2. **Broker MQTT Privado (Eclipse Mosquitto en Docker):**
   - Servidor de mensajería asíncrona de ultrabaja latencia que corre localmente en el contenedor `aethersens-mosquitto`.
   - **Puerto 1883 (TCP):** Recibe la telemetría enviada por el ESP32 desde la red local o VLAN de gestión de Intalnet.
   - **Puerto 9001 (WebSockets) / Proxy Nginx `/mqtt`:** Retransmite los paquetes en milisegundos directamente a los navegadores web de los operadores del NOC sin necesidad de refrescar la pantalla.

3. **Plataforma Web & API (Laravel 12 + PHP 8.3 + Nginx + MySQL 8 + Redis):**
   - Interfaz web PWA accesible desde computadores del NOC o smartphones de guardia.
   - Control de acceso por roles:
     - **Nivel 1 (Administrador):** Gestión de usuarios, salas, umbrales y descarga de reportes.
     - **Nivel 2 (Técnico de Operación):** Supervisión continua y autorización de silenciamiento remoto de alarma.
     - **Nivel 3 (Observador):** Vista exclusiva de telemetría e incidencias.
   - Registro en base de datos MySQL de cada evento crítico, superación de umbrales y auditoría de operadores.

---

## 2. Topología de Red y Flujo de Comunicación

```
  [ ESP32 en Rack Sala Técnica ]
             │
             │ MQTT nativo sobre TCP (Puerto 1883)
             ▼
  ┌───────────────────────────────────────────────────────────┐
  │                 SERVIDOR DOCKER (INTALNET)                │
  │                                                           │
  │   [ aethersens-mosquitto ] (Broker Privado)               │
  │        ▲               │                                  │
  │        │               │ WebSocket interno (Puerto 9001)  │
  │        │               ▼                                  │
  │        │         [ aethersens-web ] (Nginx)               │
  │        │         Proxy inverso en ruta: /mqtt             │
  │        │         Puerto 80 (HTTP) / 443 (HTTPS)           │
  │        │               ▲                                  │
  │        │               │ FastCGI                          │
  │        │               ▼                                  │
  │        │         [ aethersens-app ] (Laravel PHP 8.3)     │
  │        │               ▲               ▲                  │
  │        │               │               │                  │
  │        │               ▼               ▼                  │
  │        │      [ aethersens-db ]  [ aethersens-redis ]     │
  │        │          (MySQL 8)         (Caché/Sesiones)      │
  └────────┼───────────────────────────────┼──────────────────┘
           │                               │
           │                               │ HTTPS / WSS (WebSockets Seguros)
           │                               ▼
           └──────────────────> [ Navegadores Operadores NOC ]
```

---

## 3. Elección y Configuración del Dominio Institucional

Para la publicación del servicio bajo el dominio corporativo de Intalnet, se recomienda crear un registro DNS tipo **A** o **CNAME** que apunte a la IP pública o IP fija del servidor en la red de Intalnet:

### Opciones de Nomenclatura para el Subdominio:

| Opción | Subdominio Sugerido | Descripción y Caso de Uso |
| :--- | :--- | :--- |
| **Opción 1 (Recomendada)** | **`noc.intalnet.com`** o **`monitoreo.intalnet.com`** | Nomenclatura estándar en la industria de telecomunicaciones para el Centro de Operaciones de Red (*Network Operations Center*). Fácil de recordar para los ingenieros de guardia. |
| **Opción 2 (Identidad)** | **`aethersens.intalnet.com`** | Identifica directamente el nombre formal del software y evita confusiones con otros sistemas de monitoreo de red (PRTG, Zabbix, LibreNMS). |
| **Opción 3 (Infraestructura)** | **`salastecnicas.intalnet.com`** | Muy claro para auditorías de infraestructura física y datacenter. |
| **Opción 4 (IoT / Telemetría)** | **`iot.intalnet.com`** o **`telemetria.intalnet.com`** | Ideal si a futuro se centralizan otros sensores (sensores de puerta, UPS, generadores diésel). |

*Nota: Reemplace `intalnet.com` por la extensión que aplique (ej. `.com.co` o `.co`).*

---

## 4. Requisitos del Servidor

- **Sistema Operativo:** Ubuntu Server 22.04 LTS o 24.04 LTS (o cualquier distribución Linux con soporte Docker).
- **Recursos Mínimos:** 2 vCPU, 2 GB a 4 GB RAM, 20 GB de disco SSD.
- **Software:** 
  - Docker Engine $\ge 24.0$
  - Docker Compose v2 (`docker compose`)
- **Reglas de Firewall (UFW / Mikrotik / Router de Borde):**
  - **Puerto 80 / 443 TCP:** Entrada abierta para acceso web seguro de los operadores (HTTPS).
  - **Puerto 1883 TCP:** Entrada habilitada **únicamente** desde la subred/VLAN donde estén conectados los ESP32 (no exponer este puerto a internet público para máxima seguridad).

---

## 5. Procedimiento de Despliegue Paso a Paso

### Paso 1: Clonar el Repositorio en el Servidor
```bash
git clone <URL_DEL_REPOSITORIO> /opt/aethersens
cd /opt/aethersens
```

### Paso 2: Crear el Archivo de Configuración de Producción
Copie el archivo de ejemplo preparado para Docker:
```bash
cp .env.docker.example .env
```

Edite el archivo `.env` (`nano .env`):
```env
APP_NAME=AetherSens
APP_ENV=production
APP_DEBUG=false
APP_URL=https://noc.intalnet.com   # <- Coloque el dominio real elegido

# Base de datos en Docker (Cambie 'secret' por una clave segura)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=aethersens
DB_USERNAME=aethersens
DB_PASSWORD=ClaveMuySegura2026!
DB_ROOT_PASSWORD=RootSuperSeguro2026!

# Puertos
APP_PORT=8000
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
MQTT_PORT=1883
MQTT_WS_PORT=9001
```

### Paso 3: Construir y Levantar los Contenedores
```bash
docker compose up -d --build
```
Verifique que los 6 contenedores estén activos:
```bash
docker compose ps
```
*(Los contenedores `app`, `web`, `db`, `redis`, `queue` y `mosquitto` deben figurar en estado `Up` o `healthy`).*

### Paso 4: Inicializar la Base de Datos y Claves de Cifrado
Ejecute dentro del contenedor de la aplicación:
```bash
# 1. Generar la clave única de la aplicación
docker compose exec app php artisan key:generate --force

# 2. Ejecutar las migraciones de la base de datos
docker compose exec app php artisan migrate --force

# 3. Crear el usuario Administrador inicial y configuración base
docker compose exec app php artisan db:seed --force

# 4. Optimizar cachés de Laravel para producción
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

### Credenciales por Defecto de Primer Acceso:
- **URL:** `https://noc.intalnet.com` (o `http://IP_SERVIDOR:8000` si aún no tiene dominio)
- **Usuario:** `adrian@mail.com`
- **Contraseña:** `password`
- *(Recomendación de seguridad: cambie la contraseña inmediatamente tras el primer ingreso en "Configuración > Perfil").*

---

## 6. Configuración de HTTPS y WebSockets con SSL (Nginx / Certbot)

Si el servidor tiene Nginx en el host principal (o Nginx Proxy Manager / Cloudflare / Traefik) actuando como terminador SSL, configure el archivo virtual host de la siguiente manera:

```nginx
server {
    listen 80;
    server_name noc.intalnet.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name noc.intalnet.com;

    # Certificados SSL Let's Encrypt o corporativos
    ssl_certificate /etc/letsencrypt/live/noc.intalnet.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/noc.intalnet.com/privkey.pem;

    # Tráfico Web hacia el contenedor Nginx de AetherSens (puerto 8000)
    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Tráfico WebSocket hacia el Broker Mosquitto a través de la ruta /mqtt
    location /mqtt {
        proxy_pass http://127.0.0.1:8000/mqtt;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_read_timeout 86400s;
        proxy_send_timeout 86400s;
    }
}
```

> **¿Por qué es importante esto?**
> Cuando un operador abre la web vía `https://`, los navegadores modernos bloquean cualquier WebSocket sin cifrar (`ws://`).
> Con la configuración anterior, la conexión WebSocket viaja de forma segura por `wss://noc.intalnet.com/mqtt` reutilizando el mismo puerto 443 y el mismo certificado SSL.

---

## 7. Configuración del Nodo IoT (ESP32)

En el código fuente del microcontrolador (`firmware/aethersens_esp32_sht40/aethersens_esp32_sht40.ino`), modifique las constantes de red antes de compilarlo con Arduino IDE:

```cpp
// 1. Red WiFi de la Sala Técnica de Intalnet
const char* WIFI_SSID     = "WIFI_RACKS_INTALNET";
const char* WIFI_PASSWORD = "ClaveWifiSegura";

// 2. Broker MQTT Privado (IP del Servidor Docker o Nombre de Host Local)
const char* MQTT_BROKER   = "192.168.10.50"; // o "noc.intalnet.com"
const int   MQTT_PORT     = 1883;

// 3. Tópicos de la Sala 01 (Montería)
const char* TOPIC_TELEMETRY = "intalnet/sala01/telemetria";
const char* TOPIC_STATUS    = "intalnet/sala01/estado";
const char* TOPIC_ALERT     = "intalnet/sala01/alerta";
const char* TOPIC_CONTROL   = "intalnet/sala01/control";
```

### Formato de Telemetría JSON Emitido por el ESP32:
```json
{
  "temp": 21.4,
  "hum": 48.2,
  "gas_adc": 420,
  "humo_detectado": false,
  "buzzer_activo": false,
  "uptime": 128450,
  "nodo": "ESP32_SALA01"
}
```

---

## 8. Comandos Frecuentes de Operación y Soporte

| Tarea | Comando a Ejecutar |
| :--- | :--- |
| **Ver logs en vivo de todo el stack** | `docker compose logs -f` |
| **Ver telemetría que entra al broker** | `docker compose logs -f mosquitto` |
| **Ver logs de la aplicación Laravel** | `docker compose logs -f app` |
| **Reiniciar el stack completo** | `docker compose restart` |
| **Detener el stack** | `docker compose down` |
| **Actualizar a una nueva versión del código** | `git pull && docker compose exec app npm run build && docker compose exec app php artisan migrate --force && docker compose exec app php artisan optimize:clear` |
| **Crear un respaldo rápido de la base de datos** | `docker compose exec db mysqldump -u aethersens -psecret aethersens > backup_$(date +%Y%m%d).sql` |

---

## 9. Preguntas Frecuentes y Diagnóstico

### 1. La gráfica dice "Esperando telemetría del ESP32":
- Revise si el contenedor Mosquitto está arriba: `docker compose ps mosquitto`.
- En el ESP32, conecte el cable USB y abra el monitor serie a 115200 baudios: confirme si obtuvo IP y si dice `[MQTT] Conectado exitosamente`.
- Verifique que el firewall del servidor permita el tráfico al puerto 1883 TCP desde la IP del ESP32.

### 2. El panel no actualiza en tiempo real pero el ESP32 está enviando:
- Abra la consola del navegador (`F12 > Console`).
- Si ve error `WebSocket connection to wss://... failed`, verifique que el proxy de `/mqtt` esté activo y que el certificado SSL sea válido.

### 3. ¿El broker es totalmente privado?
- **Sí.** Al usar el contenedor `mosquitto` en Docker, ningún dato sale a servidores externos de terceros en internet. El tráfico se procesa 100% en los servidores propios de Intalnet.
