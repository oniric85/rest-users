FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y git unzip && \
    docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG APP_USER
ARG APP_GROUP

RUN mkdir /home/${APP_USER} && \
    usermod -u 1000 -d /home/${APP_USER} ${APP_USER} && \
    groupmod -g 1000 ${APP_GROUP} && \
    chgrp ${APP_USER} /home/${APP_USER} && \
    chown ${APP_USER} /home/${APP_USER} && \
    chmod g+rwxs /home/${APP_USER}

COPY www.conf ${PHP_ROOT_DIR}/php-fpm.d/www.conf