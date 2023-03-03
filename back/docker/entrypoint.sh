#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

echo "Entrypoint with args :" "$@"

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p var/cache var/log

  composer run-script --no-dev post-install-cmd -q

	if [ -f .env ] && grep -q ^DATABASE_SERVER_VERSION= .env; then
		echo "Waiting for db to be ready..."
		ATTEMPTS_LEFT_TO_REACH_DATABASE=60
		until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(bin/console dbal:run-sql --env "$APP_ENV" "SELECT 1" 2>&1); do
			if [ $? -eq 255 ]; then
				# If the Doctrine command exits with 255, an unrecoverable error occurred
				ATTEMPTS_LEFT_TO_REACH_DATABASE=0
				break
			fi
			sleep 1
			ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
			echo "Still waiting for db to be ready... Or maybe the db is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left"
		done

		if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
			echo "The database is not up or not reachable:"
			echo "$DATABASE_ERROR"
			exit 1
		else
			echo "The db is now ready and reachable"
		fi

    if [ ! "$2" = 'doctrine:migration:migrate' ]; then
		  SCHEMA_UP_TO_DATE=20
      until [ $SCHEMA_UP_TO_DATE -eq 0 ] || bin/console doctrine:migration:up-to-date --env "$APP_ENV" 2>&1; do
        sleep 4
        SCHEMA_UP_TO_DATE=$((SCHEMA_UP_TO_DATE - 1))
      done
      if [ $SCHEMA_UP_TO_DATE -eq 0 ]; then
        echo "The schema is not up to date (timeout)"
        exit 1
      fi
    fi
	fi

	echo "Fix ACL"
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
	  echo "Running NGINX in background"
	  nginx -g "daemon off;" &
  fi

	if [ "$1" = 'bin/console' ]; then
	  echo "Running PHP-FPM in background"
	  php-fpm &
  fi
fi

echo "exec docker-php-entrypoint"
exec docker-php-entrypoint "$@"
