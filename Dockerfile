FROM php:fpm

# Dependensi sistem + library untuk ekstensi
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libxml2-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libmemcached-dev zlib1g-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql pgsql pdo_mysql mysqli \
        gd zip bcmath intl soap opcache \
    && printf "\n\n\n\n\n\n" | pecl install redis memcached \
    && docker-php-ext-enable redis memcached \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/sirkula
COPY . .

# /var/www/html -> symlink ke public Laravel
RUN rm -rf /var/www/html \
    && ln -s /var/www/sirkula/public /var/www/html \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
