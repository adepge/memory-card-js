# Use the official PHP image as the base image
FROM php:7.4-apache

# Install necessary extensions and tools
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli \
    && docker-php-ext-enable mysqli

# Install MySQL client
RUN apt-get update && apt-get install -y default-mysql-client

# Copy the application code to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Make the database setup script executable
RUN chmod +x /var/www/html/setup_database.sh

# Expose port 80
EXPOSE 80

# Set up the database and start Apache
CMD ["/bin/bash", "-c", "/var/www/html/setup_database.sh && apache2-foreground"]