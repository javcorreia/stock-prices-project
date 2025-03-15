FROM php:8.4-fpm-bookworm

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd xdebug zip pcntl pdo_pgsql bcmath redis

COPY ./docker/configs/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/configs/php/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer/composer:2.8-bin /composer /usr/bin/composer

WORKDIR /app-api

COPY ./api /app-api

CMD ["php-fpm"]
