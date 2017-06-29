## Social

- Make sure php-gd is enabled.
- Make sure storage/app/public/avatars dir exists and is writable.
- cp .env.example .env
- composer install
- php artisan 
    - key:generate
    - migrate
    - db:seed
    - passport:keys
    - passport:client --password

