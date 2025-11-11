#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
    apt-get update
    apt-get install -y php-cli unzip
fi

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=. --filename=composer
rm composer-setup.php

./composer install --optimize-autoloader --no-dev
php artisan config:cache
npm install
npm run build
