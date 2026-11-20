# Guía de Despliegue con Docker – AetherSens

Esta guía describe la arquitectura de contenedores de **AetherSens** y los pasos para levantar el entorno completo de forma rápida y reproducible en cualquier servidor o equipo de desarrollo con Docker.

---

## 1. Arquitectura de Servicios en Contenedores

El archivo `docker-compose.yml` orquesta 6 servicios optimizados:

| Servicio | Contenedor | Descripción / Función | Puertos Expuestos |
| :--- | :--- | :--- | :--- |
| **`web`** | `aethersens-web` | Servidor web Nginx (proxy inverso, compresión Gzip, caché de assets Vite). | `8000:80` |
| **`app`** | `aethersens-app` | Servidor de backend Laravel ejecutando **PHP 8.3 FPM** con Composer, Node y npm. | `9000` (interno) |
| **`db`** | `aethersens-db` | Base de datos relacional **MySQL 8.0** con comprobación de estado de salud (*healthcheck*). | `3306:3306` |
| **`redis`** | `aethersens-redis` | Motor de caché en memoria, sesiones concurrentes y driver de colas de telemetría. | `6379:6379` |
| **`queue`** | `aethersens-queue` | Worker en segundo plano (`php artisan queue:work`) para procesar alarmas y logs. | Interno |
| **`mosquitto`** | `aethersens-mosquitto` | Broker MQTT local para telemetría autónoma sin conexión externa a Internet. | `1883` (TCP), `9001` (WebSocket) |

---

## 2. Puesta en Marcha Rápida (Primer Despliegue)

### Paso 1: Configurar variables de entorno
Cree su archivo `.env` basado en la plantilla de Docker:
```bash
# En Windows PowerShell o CMD:
copy .env.docker.example .env

# En Linux o macOS:
cp .env.docker.example .env
```

*(Si ya cuenta con un archivo `.env` existente, asegúrese de apuntar `DB_HOST=db` y `REDIS_HOST=redis`).*

### Paso 2: Iniciar Docker Desktop
Asegúrese de que el motor de Docker esté en ejecución en su sistema.

### Paso 3: Construir y levantar los contenedores
```bash
docker compose up -d --build
```

### Paso 4: Ejecutar migraciones de base de datos
Una vez que los contenedores estén en estado activo (*healthy*):
```bash
docker compose exec app php artisan migrate
```

*(Opcional)* Si desea cargar datos iniciales de prueba:
```bash
docker compose exec app php artisan db:seed
```

### Paso 5: Compilar activos frontend (Vite)
Para compilar los estilos de Tailwind, MQTT.js y ApexCharts dentro del contenedor:
```bash
docker compose exec app npm run build
```

---

## 3. Acceso a la Plataforma

- **Aplicación Web / Panel**: [http://localhost:8000](http://localhost:8000)
- **Base de Datos MySQL**: Host `localhost`, Puerto `3306`, Usuario `aethersens`, Contraseña `secret`, BD `aethersens`
- **Broker MQTT Local (Mosquitto)**:
  - Protocolo nativo ESP32: `localhost:1883` (o la IP local del servidor)
  - Protocolo WebSockets (Navegador): `ws://localhost:9001`

---

## 4. Comandos Frecuentes de Mantenimiento

### Detener los contenedores:
```bash
docker compose down
```

### Detener eliminando volúmenes (reinicio de cero):
```bash
docker compose down -v
```

### Ver registros (logs) en tiempo real:
```bash
docker compose logs -f
# O de un servicio específico:
docker compose logs -f app
docker compose logs -f mosquitto
```

### Ejecutar comandos Artisan dentro del contenedor:
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:list
```

### Abrir terminal interactivo dentro del contenedor de la aplicación:
```bash
docker compose exec app sh
```

### Ejecutar pruebas automatizadas:
```bash
docker compose exec app php artisan test
```

---

## 5. Elección de Base de Datos: MySQL vs. SQLite

- **Para usar MySQL (Recomendado para producción / múltiples racks)**:
  En su archivo `.env`, mantenga:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=aethersens
  DB_USERNAME=aethersens
  DB_PASSWORD=secret
  ```

- **Para usar SQLite (Ligero / Pruebas unitarias)**:
  Cambie en su archivo `.env`:
  ```env
  DB_CONNECTION=sqlite
  ```
  El contenedor creará y gestionará automáticamente `database/database.sqlite` con los permisos adecuados.

