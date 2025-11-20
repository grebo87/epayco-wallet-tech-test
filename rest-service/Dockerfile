FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libxml2-dev \
    && docker-php-ext-install zip pdo pdo_mysql soap

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./ /var/www

RUN chown -R www-data:www-data /var/www/storage
RUN chmod -R 755 /var/www/storage
