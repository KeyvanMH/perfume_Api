FROM php:8.3.19-fpm-bookworm 
WORKDIR /var/www/html
COPY . /var/www/html
RUN  docker-php-ext-install mysqli && pecl install redis && docker-php-ext-enable redis && docker-php-ext-install pdo pdo_mysql
