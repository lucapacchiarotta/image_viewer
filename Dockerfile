FROM php:8.3-apache

RUN apt-get update
RUN apt-get install -y git zlib1g-dev libzip-dev
RUN apt-get install --yes libicu-dev
#RUN apt-get install -y libsqlite3-dev

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl \
&& docker-php-ext-install zip pdo_mysql \
&& a2enmod rewrite \
&& curl -sS https://getcomposer.org/installer \
| php -- --install-dir=/usr/local/bin --filename=composer \

#RUN docker-php-ext-install pdo_sqlite
COPY ./docker/apache.site.conf /etc/apache2/sites-available/000-default.conf


WORKDIR /var/www
