FROM php:8.4-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean

# Instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    xml \
    pcntl \
    zip \
    opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Ajustar permisos (mejor hacerlo en runtime también)
RUN chown -R www-data:www-data /var/www