FROM php:8.2-fpm-alpine

RUN mkdir -p /var/www/html

RUN apk --no-cache add shadow && usermod -u 1000 www-data

RUN apk --no-cache add postgresql-dev \
    && docker-php-ext-install pdo intl pdo_pgsql

ADD ./php/php.ini /usr/local/etc/php/php.ini