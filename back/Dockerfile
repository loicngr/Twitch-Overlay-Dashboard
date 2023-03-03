# https://github.com/dunglas/symfony-docker/blob/main/Dockerfile
ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-fpm AS symfony_php

RUN apt-get update

RUN apt-get install -y \
    unzip \
    git \
    acl

RUN docker-php-ext-install pdo_mysql opcache

RUN apt-get install -y \
        libzip-dev; \
    docker-php-ext-configure zip; \
    docker-php-ext-install -j$(nproc) zip;

RUN apt-get install -y \
    libicu-dev \
    && docker-php-ext-install -j$(nproc) intl

ARG APCU_VERSION=5.1.21
RUN pecl install apcu-${APCU_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable \
      apcu \
      opcache;

COPY docker/healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY docker/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini

COPY docker/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN apt-get install -y \
        nginx;

ADD docker/nginx/prod.conf /etc/nginx/sites-enabled/default

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log; \
    ln -sf /dev/stderr /var/log/nginx/error.log

VOLUME /var/run/php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /code

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
	composer clear-cache; \
    sync

COPY bin bin
COPY public public
COPY config config

VOLUME /code/var

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

EXPOSE 80

COPY docker/conf.d/php.prod.ini $PHP_INI_DIR/conf.d/php.prod.ini

COPY . .

# https://symfony.com/doc/current/performance.html#optimize-composer-autoloader
RUN set -eux; \
  mkdir -p var/cache var/log; \
  composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
  composer dump-autoload --classmap-authoritative --no-dev; \
  composer run-script --no-dev post-install-cmd; \
  composer clear-cache; \
  chmod +x bin/console; sync
