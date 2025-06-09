FROM php:8.2-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y libpq-dev unzip git \
    && install-php-extensions opcache \
    && install-php-extensions xdebug-^3 \
    && install-php-extensions @composer \
    && install-php-extensions intl-stable \
    && install-php-extensions gd-stable \
    && install-php-extensions pdo_pgsql \
    && install-php-extensions pgsql \
    && install-php-extensions zip

COPY ./php/* /usr/local/etc/php/conf.d/

WORKDIR /app

ARG DOCKER_USER=default_user
RUN addgroup $DOCKER_USER && useradd -m -g $DOCKER_USER $DOCKER_USER
USER $DOCKER_USER
