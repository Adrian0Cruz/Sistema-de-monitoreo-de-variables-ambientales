# ==============================================================================
# AetherSens - Imagen Docker Oficial (PHP 8.3 FPM + Nginx/Node/Composer)
# ==============================================================================
FROM php:8.3-fpm-alpine

LABEL maintainer="AetherSens Team <soporte@aethersens.internal>"
LABEL description="Contenedor de backend y procesamiento para la plataforma AetherSens"

# 1. Instalar utilidades esenciales del sistema
RUN apk add --no-cache \
    bash \
    curl \
    git \
    nodejs \
    npm \
    dos2unix \
    linux-headers

# 2. Instalar extensiones oficiales de PHP mediante mlocati installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    pdo_mysql \
    pdo_sqlite \
    sqlite3 \
    bcmath \
    mbstring \
    xml \
    curl \
    zip \
    gd \
    intl \
    opcache \
    pcntl \
    redis \
    exif

# 3. Instalar Composer 2 oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Establecer directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar código de la aplicación
COPY . /var/www/html

# 6. Preparar estructura de almacenamiento y permisos
RUN mkdir -p /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/logs \
             /var/www/html/storage/app/public \
             /var/www/html/bootstrap/cache \
             /var/www/html/database \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database 2>/dev/null || true \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# 7. Copiar y configurar script de entrada
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# 8. Exponer puerto FastCGI
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

