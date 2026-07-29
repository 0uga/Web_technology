FROM php:8.4-fpm-alpine AS php

RUN apk add -U --no-cache curl-dev
RUN docker-php-ext-install curl
RUN docker-php-ext-install exif 

RUN apk add --no-cache $PHPIZE_DEPS
RUN pecl install apcu \
    && docker-php-ext-enable apcu


RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install gd


RUN docker-php-ext-install pdo_mysql

RUN install -o www-data -g www-data -d /var/www/upload/image/

RUN echo -e "post_max_size = 5M\nupload_max_filesize = 5M" >> ${PHP_INI_DIR}/php.ini
