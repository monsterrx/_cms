#!/bin/sh
set -eu

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/services.php
php artisan package:discover --ansi
chown -R www-data:www-data bootstrap/cache
service cron start

exec apache2-foreground
