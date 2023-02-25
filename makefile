back-reset:
	@cd back; \
	bin/console d:d:d --force; \
	bin/console d:d:c; \
	bin/console d:m:m --no-interaction; \
	cd ..; \
	make back-load-fixtures

back-load-fixtures:
	@cd back; bin/console d:f:load