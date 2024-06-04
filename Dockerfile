# Use the official PHP image as base
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    gnupg

# Install Node.js (version 18)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd exif pcntl bcmath

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . /var/www/html

# Install Composer dependencies
RUN composer install

# Install npm dependencies
RUN npm install

# Change permissions for the public directory
RUN chown -R www-data:www-data /var/www/html/public

# Expose port 8000 for Laravel
EXPOSE 8000

# Set the default command to run Laravel and Vite
CMD php artisan serve --host=0.0.0.0 --port=8000 & npx vite
