FROM php:8.0.14-fpm-buster

ENV WORKDIR=/usr/local/src/app
ENV APP_ENV=dev

RUN apt-get update \
    && apt-get install -y unzip \
    && docker-php-ext-install -j$(nproc) opcache \
    && pecl install xdebug-3.1.2 && docker-php-ext-enable xdebug \
    && useradd app --shell /bin/bash --create-home \
    && mkdir -p /home/app/.composer \
    && chown -R app:app /home/app \
    && mkdir -p ${WORKDIR} \
    && chown -R app:www-data ${WORKDIR} \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN curl -sL https://deb.nodesource.com/setup_17.x | bash -
RUN apt-get install -y --no-install-recommends nodejs

# Copy configuration files
COPY ./docker/php/fpm/dev/etc/php.ini /usr/local/etc/php/conf.d/symfony.ini

# Copy from composer image https://docs.docker.com/samples/library/composer/#suggestions
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Copy application sources to container
COPY --chown=app:www-data ./bin ${WORKDIR}/bin
COPY --chown=app:www-data ./config ${WORKDIR}/config
COPY --chown=app:www-data ./public ${WORKDIR}/public
COPY --chown=app:www-data ./src ${WORKDIR}/src
COPY --chown=app:www-data ./composer.json ${WORKDIR}/composer.json
COPY --chown=app:www-data ./composer.lock ${WORKDIR}/composer.lock
COPY --chown=app:www-data ./symfony.lock ${WORKDIR}/symfony.lock

USER app

# Define default working directory
WORKDIR ${WORKDIR}
