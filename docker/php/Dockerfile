FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y git unzip

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG APP_USER
ARG APP_GROUP

RUN usermod -u 1000 ${APP_USER} && \
    groupmod -g 1000 ${APP_GROUP}

COPY www.conf ${PHP_ROOT_DIR}/php-fpm.d/www.conf