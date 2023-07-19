FROM php:8.1.0-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini
RUN echo 'max_execution_time = 300' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini;


RUN EXT_DIR=$(php-config --extension-dir) ; SRC_DIR=/usr/src/php/ext \
    && apk add git \
    && git clone https://github.com/xdebug/xdebug ${SRC_DIR}/xdebug ; cd ${SRC_DIR}/xdebug ;  git checkout tags/3.1.5 \
    && docker-php-ext-install -j8 xdebug \
    && mkdir -p /root/modules/ ; cp ${EXT_DIR}/xdebug.so /root/modules/


COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_mysql

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

    # Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www

USER www-data

CMD ["sh", "-c", "docker-php-entrypoint php-fpm"]

EXPOSE 9000