# Use an image with PHP 8.2 and Composer
FROM composer:2 as composer

# Use the official PHP image with PHP 8.2 as the base image
FROM php:8.2-fpm as app

USER root

# Install required dependencies and the PHP MongoDB extension
RUN apt-get update && \
    apt-get install -y \
        zip \
        unzip \
        curl \
        zip \
        libzip-dev \
        libpng-dev \
        libssl-dev \
        libpq-dev \
        supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install gd zip pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the rest of the application files
COPY . .

# Copy only necessary files from the composer stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set up permissions.
RUN chown -R www-data:www-data .
RUN chmod -R 755 .

# Switch to www-data user.
USER www-data

# Run Composer install
RUN composer install --no-dev --optimize-autoloader

# Copy .env.example to .env
RUN cp .env.example .env
