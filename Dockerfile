# Use official PHP image
FROM php:8.2-apache

# Copy app files to web server directory
COPY . /var/www/html/

# Expose port
EXPOSE 8080

# Start Apache server
CMD ["apache2-foreground"]
