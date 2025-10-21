# Use an official PHP image with Apache
FROM php:8.2-apache

# Copy all project files to the web root
COPY . /var/www/html/

# Enable common PHP extensions (like mysqli)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Expose port 80 (Render uses this)
EXPOSE 80

