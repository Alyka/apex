# Use the official PHP image with PHP 8.2 as the base image
FROM php:8.2-fpm as app

USER root

# Install required dependencies and the PHP MongoDB extension
RUN apt-get update && \
    apt-get install -y \
        zip \
        unzip \
        curl \
        libzip-dev \
        libpng-dev \
        libssl-dev \
        libpq-dev \
        supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install gd zip pdo pdo_pgsql

# Set the working directory in the container
WORKDIR /var/www/html

# Set up permissions.
RUN chown -R www-data:www-data .
RUN chmod -R 755 .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
