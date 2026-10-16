FROM php:8.4-fpm

WORKDIR /var/www/html
COPY . .

# Update && upgrade the image
RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
&& rm -rf /var/lib/apt/lists/*

# Install php extensions via the installer helper
COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_pgsql pgsql pdo_mysql mysqli \
    gd zip bcmath intl soap

# Install nodejs
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENTRYPOINT [ "./docker/entrypoint.sh" ]
EXPOSE 9000
CMD ["php-fpm"]
