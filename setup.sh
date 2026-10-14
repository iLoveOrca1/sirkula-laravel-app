#!/usr/bin/bash

username=$USER
perms=$([[ $username == 'root' ]] && echo "" || echo "$perms")

banner="
  ██████  ██▓ ██▀███   ██ ▄█▀ █    ██  ██▓    ▄▄▄
▒██    ▒ ▓██▒▓██ ▒ ██▒ ██▄█▒  ██  ▓██▒▓██▒   ▒████▄
░ ▓██▄   ▒██▒▓██ ░▄█ ▒▓███▄░ ▓██  ▒██░▒██░   ▒██  ▀█▄
  ▒   ██▒░██░▒██▀▀█▄  ▓██ █▄ ▓▓█  ░██░▒██░   ░██▄▄▄▄██
▒██████▒▒░██░░██▓ ▒██▒▒██▒ █▄▒▒█████▓ ░██████▒▓█   ▓██▒
▒ ▒▓▒ ▒ ░░▓  ░ ▒▓ ░▒▓░▒ ▒▒ ▓▒░▒▓▒ ▒ ▒ ░ ▒░▓  ░▒▒   ▓▒█░
░ ░▒  ░ ░ ▒ ░  ░▒ ░ ▒░░ ░▒ ▒░░░▒░ ░ ░ ░ ░ ▒  ░ ▒   ▒▒ ░
░  ░  ░   ▒ ░  ░░   ░ ░ ░░ ░  ░░░ ░ ░   ░ ░    ░   ▒
      ░   ░     ░     ░  ░      ░         ░  ░     ░  ░
                                                       "


echo "$banner"

echo "Updating system. . . ."
$perms apt update && $perms apt upgrade -y
$perms apt install -y software-properties-common curl wget git unzip

echo "Adding ppa repo. . ."
$perms add-apt-repository ppa:ondrej/php -y
$perms apt update

# Install PHP 8.3 + ekstensi Laravel (rekomendasi stabil)
echo "Installing php extensions. . ."
$perms apt install -y php8.3-cli php8.3-fpm php8.3-mysql php8.3-pgsql \
    php8.3-sqlite3 php8.3-redis php8.3-memcached \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-gd \
    php8.3-zip php8.3-bcmath php8.3-intl php8.3-soap \
    php8.3-opcache php8.3-readline php8.3-xdebug \
    php8.3-pgsql

# Setting up composer
echo "Setting up composer. . ."
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"
$perms mv composer.phar /usr/local/bin/composer

# Running docker
echo "Running docker and other dependencies. . . ."
$perms docker compose -f "docker-compose.yml" up -d --build
composer install
npm ci

php -v
composer --version
node -v
npm -v
