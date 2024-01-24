FROM php:8.3-apache

RUN a2enmod rewrite

COPY /config/apache.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html

RUN docker-php-ext-install pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY /config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 80
EXPOSE 9000

CMD ["apache2-foreground"]