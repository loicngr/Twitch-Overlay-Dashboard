FROM composer/composer:2-bin AS composer
FROM mlocati/php-extension-installer:latest AS php_extension_installer
FROM caddy:2.6-builder-alpine AS app_caddy_builder

RUN xcaddy build

FROM php:8.1-fpm-alpine AS app_php

ARG STABILITY="stable"
ENV STABILITY ${STABILITY}

ARG SYMFONY_VERSION=""
ENV SYMFONY_VERSION ${SYMFONY_VERSION}

ENV APP_ENV=prod

WORKDIR /srv/app

COPY --from=php_extension_installer --link /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

RUN set -eux; \
    install-php-extensions \
    	intl \
    	zip \
    	apcu \
		opcache \
    ;

###> recipes ###
###< recipes ###

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link docker/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --link docker/php/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -p /var/run/php

COPY --link docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --link docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

VOLUME /srv/app

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer --link /composer /usr/bin/composer

COPY --link composer.* symfony.* ./
RUN set -eux; \
    if [ -f composer.json ]; then \
		composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
		composer clear-cache; \
    fi

COPY --link  . ./
RUN rm -Rf docker/

RUN set -eux; \
	mkdir -p var/cache var/log; \
    if [ -f composer.json ]; then \
		composer dump-autoload --classmap-authoritative --no-dev; \
		composer dump-env prod; \
		composer run-script --no-dev post-install-cmd; \
		chmod +x bin/console; \
		bin/console lexik:jwt:generate-keypair; \
    	sync; \
    fi

# Dev image
FROM app_php AS app_php_dev

ENV APP_ENV=dev XDEBUG_MODE=off
VOLUME /srv/app/var/

RUN rm "$PHP_INI_DIR/conf.d/app.prod.ini"; \
	mv "$PHP_INI_DIR/php.ini" "$PHP_INI_DIR/php.ini-production"; \
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --link docker/php/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/

RUN set -eux; \
	install-php-extensions xdebug

RUN rm -f .env.local.php

#FROM caddy:2.6-alpine

WORKDIR /srv/app

COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --from=app_php --link /srv/app/public public/
COPY --link docker/caddy/Caddyfile /etc/caddy/Caddyfile