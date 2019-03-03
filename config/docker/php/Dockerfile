FROM php:7.3-fpm

RUN apt-get update \
    && apt-get install -y git \
    && apt-get install -y zip \
    && apt-get install -y libpq-dev \
    && apt-get install -y libgmp-dev re2c libmhash-dev libmcrypt-dev file \
    && ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/ \
    && docker-php-ext-install bcmath \
    && docker-php-ext-enable bcmath \
    && pecl install xdebug-2.7.0RC2 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure gmp \
    && docker-php-ext-install gmp

COPY config/ /usr/local/etc/php/conf.d/
