#!/bin/sh

php artisan key:generate
php artisan storage:link

php artisan migrate --force --seed 2>/dev/null || true

exec "$@"
