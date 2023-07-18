FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libxml2-dev \
    && docker-php-ext-install \
    ctype \
    iconv \
    pcntl \
    session \
    simplexml \
    bcmath \
    pdo_mysql

# Install Xdebug extension
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Xdebug configuration
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD ./php/default.ini /usr/local/etc/php/conf.d/default.ini

WORKDIR /var/www/api