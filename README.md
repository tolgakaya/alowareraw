# Requirements
1. Git
2. PHP 8
3. MySQL 8
3. Composer


# Running
1. git clone https://github.com/tolgakaya/alowaretest.git && cd alowaretest
2. composer install --no-dev -a
3. php artisan key:generate
4. create database aloware
5. edit .env file, update db user and password 
6. php artisan migrate
7. php artisan serve
8. browse URL http://127.0.0.1:8000

# Testing
php artisan test
