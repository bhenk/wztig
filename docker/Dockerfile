FROM php:7.4-apache

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && install-php-extensions gd
RUN docker-php-ext-install exif

RUN apt-get update && apt-get install -y default-mysql-client
RUN docker-php-ext-install pdo_mysql

RUN mkdir /var/www/gitzw
RUN mkdir /var/www/data
RUN mkdir /var/www/logs
RUN mkdir /var/vendor

RUN a2enmod rewrite
