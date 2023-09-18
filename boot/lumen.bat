@echo off
cd ..
cd ./lumen
call composer install
call php artisan migrate
call php artisan db:seed
call php -S localhost:8000 -t public