# Use the official PHP image as the base image
FROM php:8.2.0-fpm-alpine

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Expose port
EXPOSE 8080

# Command to run the application
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]