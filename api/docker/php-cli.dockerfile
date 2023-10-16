FROM php:8.1-cli-alpine

# Install required packages and extensions
RUN apk add --no-cache libzip-dev zip mysql-client \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Add your crontab file
COPY cron/tasks.cron /etc/crontabs/www-data

# Set the working directory
WORKDIR /var/www

# Start the cron service in the background
CMD ["crond", "-f"]