FOLDER_BACK=./back
FOLDER_FRONT=./front
APP_NAME=twitchOverlay
APP_VERSION=0.0.1

########################################
################ COMMON ################
########################################
docker-prune:
	@docker system prune -a -f --volumes && \
	docker volume rm $(docker volume ls -q)

docker-fix-chown:
	docker compose run --rm php chown -R $(id -u):$(id -g) .

check:
	@make front-fixer && \
	make back-fixer

#########################################
################ BACKEND ################
#########################################
back-docker-build-prod:
	@cd $(FOLDER_BACK) && \
	docker compose build --pull --no-cache

back-docker-down:
	@cd $(FOLDER_BACK) && \
	docker compose down --rmi "local"

back-tests: back-reset-test
	@cd $(FOLDER_BACK) && \
	./vendor/phpunit/phpunit/phpunit -c ./phpunit.xml.dist

back-fixer:
	@cd $(FOLDER_BACK) && \
	./vendor/bin/php-cs-fixer fix src --show-progress=dots

back-reset-test:
	@cd $(FOLDER_BACK) && \
	composer tests-reset

back-reset:
	@cd $(FOLDER_BACK) && \
	bin/console d:d:d --force && \
	bin/console d:d:c && \
	bin/console d:m:m --no-interaction && \
	cd .. && \
	make back-load-fixtures

back-load-fixtures:
	@cd $(FOLDER_BACK) && bin/console d:f:load

back-load-test-fixtures:
	@cd $(FOLDER_BACK) && bin/console --env=test doctrine:fixtures:load -q -n

back-serve:
	@cd $(FOLDER_BACK) && symfony serve --no-tls

##########################################
################ FRONTEND ################
##########################################
front-docker-build-prod:
	@cd $(FOLDER_FRONT) && \
	docker compose build --pull --no-cache

front-docker-down:
	@cd $(FOLDER_FRONT) && \
	docker compose down --rmi "local"

front-fixer:
	cd $(FOLDER_FRONT) && \
	npm run lint -- --fix

front-serve:
	cd $(FOLDER_FRONT) && \
	npm run dev
