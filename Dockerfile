FROM php:8.0-fpm

# Install Apache
RUN apt-get update && apt-get install -y apache2 \
    && a2dismod mpm_prefork \
    && a2enmod mpm_event proxy proxy_fcgi setenvif rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure Apache to use PHP-FPM over TCP (9000)
RUN echo '<FilesMatch \.php$>\n\
    SetHandler "proxy:fcgi://127.0.0.1:9000"\n\
</FilesMatch>' > /etc/apache2/conf-available/php-fpm.conf \
 && a2enconf php-fpm

# Copy app
COPY . /var/www/html/

# Set DocumentRoot to /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-enabled/000-default.conf

# Allow .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Railway dynamic port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["sh", "-c", "php-fpm & apachectl -D FOREGROUND"]
