FROM php:8.2-apache

RUN docker-php-ext-install mysqli && \
    a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/files && \
    chmod -R 755 /var/www/html/files

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
