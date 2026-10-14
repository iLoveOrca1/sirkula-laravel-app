# ── Stage 1: Build frontend assets ──
FROM node:22-alpine AS node-builder

WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Stage 2: PHP-FPM application ──
FROM php:8.4-fpm

COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pdo_pgsql pgsql pdo_mysql mysqli \
    gd zip bcmath intl soap

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install PHP dependencies (production only)
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Copy built Vite assets from node stage
COPY --from=node-builder /build/public/build ./public/build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
