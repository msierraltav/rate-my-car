FROM php:8.4-apache

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

COPY /src /var/www/html/

COPY /config/config.ini /usr/local/etc/php/conf.d/

EXPOSE 8080