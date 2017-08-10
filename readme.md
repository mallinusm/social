# Social

## Requirements
    PHP >= 5.6.4
    OpenSSL PHP Extension
    PDO PHP Extension
    Mbstring PHP Extension
    Tokenizer PHP Extension
    XML PHP Extension
    GD PHP Extension

## Installation
    Make sure storage is writable.
    cp .env.example .env
    composer install --no-dev --optimize-autoloader
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    php artisan passport:keys
    php artisan passport:client --password
