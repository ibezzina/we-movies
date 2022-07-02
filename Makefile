.PHONY: start stop test
DOCKER=docker exec -ti $(shell docker ps --filter name=php_fpm -q)

ci-analyse:
	vendor/bin/phpstan analyse src tests --memory-limit=1G

ci-cs:
	vendor/bin/php-cs-fixer fix --allow-risky yes --dry-run --verbose

install: start ## Install dependencies
	$(DOCKER) composer install
	$(DOCKER) npm install
	$(DOCKER) npm run dev

test: ## launch complete test
	$(DOCKER) vendor/bin/php-cs-fixer fix --dry-run --stop-on-violation --diff
	$(DOCKER) sh -c "APP_ENV=test XDEBUG_MODE=coverage vendor/bin/phpunit"
	$(DOCKER) vendor/bin/phpstan analyse src tests --memory-limit=1G

start: ## Start project
	docker-compose up -d

stop: ## Stop the project
	docker-compose down

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
