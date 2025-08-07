FROM php:8.2-apache

COPY . /var/www/html/

RUN mkdir -p /var/www/html/files && \
    chown -R www-data:www-data /var/www/html/files

RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
