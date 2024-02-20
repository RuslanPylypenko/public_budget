FROM php:8.2-cli-alpine

# Install required packages and extensions
RUN apk add --no-cache libzip-dev zip mysql-client bash \
    && docker-php-ext-install zip pdo pdo_mysql ftp

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY ./php/cli.ini /usr/local/etc/php/conf.d/

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Add your crontab file
COPY cron/tasks.cron /etc/crontabs/www-data

# Set the working directory
WORKDIR /var/www/api

# Start the cron service in the background
CMD ["crond", "-f"]