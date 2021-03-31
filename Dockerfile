FROM php:8.0-cli-alpine

LABEL vendor="sharedBookshelf"

WORKDIR /mnt/code/

# xdebug
ENV XDEBUG_MODE=coverage
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

# php-gd
RUN apk add --no-cache libpng libpng-dev \
  && docker-php-ext-install gd \
  && apk del libpng-dev

# php7-pcntl for psalm
RUN apk add --no-cache php-pcntl \
  && docker-php-ext-install pcntl

# mysql & pdo
RUN docker-php-ext-install mysqli pdo pdo_mysql

# git &ssh (composer)
RUN apk add --no-cache git openssh

