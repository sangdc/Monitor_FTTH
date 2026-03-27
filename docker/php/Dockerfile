FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    iputils-ping \
    && docker-php-ext-install zip pdo pdo_mysql mbstring bcmath gd

RUN a2enmod rewrite

WORKDIR /var/www/html

# Configure PHP
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "date.timezone = Asia/Ho_Chi_Minh" >> /usr/local/etc/php/conf.d/custom.ini

# Create directories with proper permissions
RUN mkdir -p /var/www/html/temp && \
    mkdir -p /var/www/html/assets && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html
