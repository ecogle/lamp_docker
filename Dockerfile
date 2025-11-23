# Use the official PHP image with Apache
FROM php:8.2-apache

# Install additional PHP extensions if needed
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (optional, for .htaccess support)
RUN a2enmod rewrite

# Copy your application code to the container (uncomment and adjust as needed)
# COPY src/ /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
