## Social

Make sure php-gd is enabled.

- cp .env.example .env
- composer install
- php artisan 
    - key:generate
    - migrate
    - db:seed
    - passport:keys
    - passport:client --password

