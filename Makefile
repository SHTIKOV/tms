STAN=./vendor/bin/phpstan --memory-limit=1024M
STAN_CONF=app/config/phpstan.neon
STAN_LEVEL=5

.PHONY: analyze
analyze:
	$(STAN) analyse -l $(STAN_LEVEL) -c $(STAN_CONF) index.php Core Entity Controller app

.PHONY: doctrine
doctrine:
	vendor/bin/doctrine

.PHONY: migrations
migrations:
	vendor/bin/doctrine-migrations

.PHONY: server
server:
	php -S localhost:8000
