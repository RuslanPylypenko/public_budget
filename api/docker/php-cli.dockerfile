FROM php:8.1-cli

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

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/usr/local/bin --filename=composer --quiet

WORKDIR /var/www/api
