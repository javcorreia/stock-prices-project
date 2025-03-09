FROM php:8.4-fpm-bookworm

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd xdebug zip pcntl pdo_pgsql bcmath

COPY ./docker/configs/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/configs/php/www.conf /usr/local/etc/php-fpm.d/www.conf

#RUN groupadd -g 1000 appuser && \
#    useradd -u 1000 -g appuser -m -s /bin/bash appuser

WORKDIR /app-client
#RUN chown -R appuser:appuser /app-client

#USER appuser
#
#COPY --chown=appuser:appuser ./client /app-client
COPY ./client /app-client

CMD ["php-fpm"]
