default: help

help: ## Show this help
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install composer dependencies
	./tools/composer.phar install
	#mkdir -p public/debugbar
	#cp -rf vendor/maximebf/debugbar/src/DebugBar/Resources/* public/debugbar/

update: ## Update composer dependencies
	./tools/composer.phar update

upgrade: ## Upgrade Tools & Composer major versions
	wget https://phar.phpunit.de/phpunit-9.phar -O tools/phpunit.phar
	chmod +x tools/phpunit.phar
	wget https://github.com/vimeo/psalm/releases/download/4.4.1/psalm.phar -O tools/psalm.phar
	chmod +x tools/psalm.phar

run: ## basic clearing of history and screen of terminal
	php -S localhost:8080 -t public public/index.php

test: ## Run all tests
	make test_unit
	make test_psalm

test_unit: ## run unit tests
	./tools/phpunit.phar -c tests/phpunit.xml

test_psalm: ## run psalm static analysis
	./tools/psalm.phar --config='tests/psalm.xml' --show-info=false

