# Social

## Requirements
    PHP >= 5.6.4
    OpenSSL PHP Extension
    PDO PHP Extension
    Mbstring PHP Extension
    Tokenizer PHP Extension
    XML PHP Extension
    GD PHP Extension

## Installation (local/testing)
    cp .env.example .env
    composer install
    php artisan key:generate
    php artisan migrate --seed
    php artisan passport:keys
    php artisan passport:client --password

## Deployment (prod)

Make sure the storage folder is writable by www-data.

    cp .env.example .env
    composer install --no-dev --optimize-autoloader
    php artisan key:generate
    php artisan migrate --seed
    php artisan passport:keys
    php artisan passport:client --password
    php artisan config:cache
    php artisan route:cache
    php artisan optimize
    vendor/bin/doctrine orm:clear-cache:query --flush
    vendor/bin/doctrine orm:clear-cache:metadata --flush
    vendor/bin/doctrine orm:clear-cache:result --flush
    vendor/bin/doctrine orm:generate-proxies
    vendor/bin/doctrine orm:ensure-production-settings
