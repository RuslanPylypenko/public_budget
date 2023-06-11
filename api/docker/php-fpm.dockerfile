FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libxml2-dev \
    wget \
    && docker-php-ext-install \
    ctype \
    iconv \
    pcntl \
    session \
    simplexml \
    bcmath \
    pdo_mysql

ADD ./php/default.ini /usr/local/etc/php/conf.d/default.ini

WORKDIR /var/www/api
