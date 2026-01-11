FROM php:8.0-fpm

# Install Apache
RUN apt-get update && apt-get install -y apache2 \
    && a2dismod mpm_prefork \
    && a2enmod mpm_event proxy_fcgi setenvif rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# PHP-FPM Apache config
RUN echo '<FilesMatch \.php$>\n\
    SetHandler "proxy:unix:/var/run/php/php-fpm.sock|fcgi://localhost"\n\
</FilesMatch>' > /etc/apache2/conf-available/php-fpm.conf \
 && a2enconf php-fpm

# Copy app
COPY . /var/www/html/
WORKDIR /var/www/html/public

# Railway dynamic port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-enabled/000-default.conf

EXPOSE ${PORT}

CMD ["apachectl", "-D", "FOREGROUND"]
