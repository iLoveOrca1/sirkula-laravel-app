FROM php:8.3-fpm

RUN apt-get install -y php8.3-cli php8.3-fpm php8.3-mysql php8.3-pgsql \
    php8.3-sqlite3 php8.3-redis php8.3-memcached \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-gd \
    php8.3-zip php8.3-bcmath php8.3-intl php8.3-soap \
    php8.3-opcache php8.3-readline php8.3-xdebug \
    php8.3-pgsql



WORKDIR /var/www/html
RUN mkdir -p /var/www/sirkula

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/sirkula

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT [ "entrypoint.sh" ]
CMD ["php-fpm"]

