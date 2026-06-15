#!/bin/bash

cd /home/site/wwwroot

chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache

php -S 0.0.0.0:8080 -t public