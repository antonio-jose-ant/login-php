FROM php:8.2-fpm

# Instalar dependencias necesarias para Composer y extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer dentro del contenedor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar composer y generar vendor
COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-interaction

# Copiar todo el proyecto
COPY . .

# Regenerar autoload (por si acaso)
RUN composer dump-autoload --optimize

CMD ["php-fpm"]
