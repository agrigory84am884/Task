FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

RUN apt update && apt install -y curl libcurl4 libxml2-dev git zip libzip-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev

WORKDIR /var/www/html