FROM php:8.0-apache

# Copy the application code
COPY . /var/www/html/

# Set the working directory to public
WORKDIR /var/www/html/public

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
