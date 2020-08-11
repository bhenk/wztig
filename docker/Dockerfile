FROM php:7.4-apache

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && install-php-extensions gd exif

RUN mkdir /var/www/gitzw
RUN mkdir /var/www/data
RUN mkdir /var/www/logs
RUN mkdir /var/vendor

RUN a2enmod rewrite
