FROM php:8.4-fpm

# Instalar extensiones para PostgreSQL y herramientas básicas
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl

RUN docker-php-ext-install pdo pdo_pgsql

RUN docker-php-ext-install pcntl

# Copiar Composer desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Permisos para que Laravel pueda escribir en storage y logs
RUN chown -R www-data:www-data /var/www