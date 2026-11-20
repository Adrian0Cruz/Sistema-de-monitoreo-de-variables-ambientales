#!/bin/sh
set -e

# ==============================================================================
# AetherSens - Script de Entrada para Contenedor Docker (PHP-FPM)
# ==============================================================================

# Asegurar existencia de directorios críticos de almacenamiento
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

# Copiar .env si no existe
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.docker.example ]; then
        echo "[AetherSens Docker] Copiando .env.docker.example a .env..."
        cp /var/www/html/.env.docker.example /var/www/html/.env
    elif [ -f /var/www/html/.env.example ]; then
        echo "[AetherSens Docker] Copiando .env.example a .env..."
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Si se usa SQLite y la base de datos no existe, crear el archivo
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Instalar dependencias si vendor no está presente
if [ ! -d /var/www/html/vendor ]; then
    echo "[AetherSens Docker] Instalando dependencias de Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generar clave de aplicación si no está asignada
if grep -q "APP_KEY=$" /var/www/html/.env 2>/dev/null || grep -q "APP_KEY=\s*$" /var/www/html/.env 2>/dev/null || ! grep -q "APP_KEY=" /var/www/html/.env 2>/dev/null; then
    echo "[AetherSens Docker] Generando clave de aplicación (APP_KEY)..."
    php artisan key:generate --force || true
fi

# Ajustar permisos para www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Crear enlace simbólico de almacenamiento público
php artisan storage:link --quiet 2>/dev/null || true

# Ejecutar el comando pasado al contenedor (por defecto php-fpm)
exec "$@"

