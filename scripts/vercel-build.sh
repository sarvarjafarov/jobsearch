#!/usr/bin/env bash
set -euo pipefail

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=. --filename=composer
rm composer-setup.php

./composer install --optimize-autoloader --no-dev
php artisan config:cache
npm install
npm run build
