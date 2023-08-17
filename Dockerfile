# Use the official PHP image as the base image
FROM php:7.4-cli

# Set the working directory in the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel project files into the container
COPY . /var/www/html

# Install Laravel WebSockets dependencies
RUN composer install

# Expose the port on which the Laravel WebSockets server will run
EXPOSE 6001

# Define the command to run the Laravel WebSockets server
CMD ["php", "artisan", "websockets:serve"]

#docker build -t laravel-websockets-image .
#docker run -d -p 6001:6001 --name laravel-websockets-container laravel-websockets-image

