#!/bin/bash
set -e

# Banner for styling
echo "
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

# Buat symlink dari /var/www/html ke /var/www/sirkula
mkdir -p /var/www/html

# Hapus index.php default jika ada
rm -f /var/www/html/index.php

# Symlink file utama
ln -sf /var/www/sirkula/public/index.php /var/www/html/index.php

# Symlink seluruh public directory (untuk assets: css, js, images)
ln -sfn /var/www/sirkula/public /var/www/html/public
ln -sfn /var/www/sirkula/bootstrap /var/www/html/bootstrap

# Optional: symlink storage untuk akses langsung (tidak direkomendasikan untuk production)
# ln -sfn /var/www/sirkula/storage/app/public /var/www/html/storage

exec "$@"
