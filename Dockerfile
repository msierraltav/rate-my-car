FROM php:8.4-apache
WORKDIR "/application"

# RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY /src /var/www/html/

COPY /config/config.ini /usr/local/etc/php/conf.d/

EXPOSE 80