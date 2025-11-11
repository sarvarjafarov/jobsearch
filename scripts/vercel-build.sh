#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
    if command -v apt-get >/dev/null 2>&1; then
        apt-get update
        apt-get install -y php-cli php-xml unzip
    elif command -v yum >/dev/null 2>&1; then
        yum install -y php8.2-cli php8.2-xml unzip || yum install -y php-cli php-xml unzip
    elif command -v apk >/dev/null 2>&1; then
        apk add --no-cache php php-cli php-phar php-openssl php-mbstring php-xml unzip
    else
        echo "PHP is required but no supported package manager was found." >&2
        exit 1
    fi
fi

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=. --filename=composer
rm composer-setup.php

./composer install --optimize-autoloader --no-dev
php artisan config:cache
npm install
npm run build
