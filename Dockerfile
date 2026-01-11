FROM php:8.0-fpm

# Install Apache
RUN apt-get update && apt-get install -y apache2 \
    && a2dismod mpm_prefork \
    && a2enmod mpm_event proxy_fcgi setenvif rewrite \
    && a2enconf php8.0-fpm

# Copy application code
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/public

# Railway uses dynamic ports
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["apachectl", "-D", "FOREGROUND"]
