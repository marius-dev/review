FROM php:7.1-fpm

RUN apt-get update && apt-get upgrade -y \
  && apt-get install -y zlib1g-dev libicu-dev g++ libc-client-dev libkrb5-dev git \
  libzip-dev libpng-dev libmemcached-dev unzip

RUN docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-configure pcntl \
  && docker-php-ext-install pcntl \
  && docker-php-ext-install opcache \
  && docker-php-ext-install pdo_mysql \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap \
  && docker-php-ext-configure bcmath && docker-php-ext-install bcmath \
  && pecl install zip && docker-php-ext-enable zip \
  && docker-php-ext-configure gd && docker-php-ext-install gd


COPY php-fpm.conf /etc/php-fpm.conf
COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/app

#RUN pwd
#
#RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
#    && php composer-setup.php \
#    && php -r "unlink('composer-setup.php');" \
#    && php composer.phar install