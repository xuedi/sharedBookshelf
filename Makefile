default: help

toolComposer='./tools/composer.phar'
toolPhpunit='./tools/phpunit.phar'
toolPsalm='./tools/psalm.phar'
toolCodeSniffer='./tools/phpcs.phar'
toolCodeFixer='./tools/phpcbf.phar'

help: ## Show this help
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install composer dependencies
	$(toolComposer) install

update: ## Update composer dependencies
	$(toolComposer) update

update_tools: ## Upgrade Tools & Composer major versions
	$(toolComposer) self-update
	wget https://phar.phpunit.de/phpunit-9.phar -O $(toolPhpunit)
	chmod +x $(toolPhpunit)
	wget https://github.com/vimeo/psalm/releases/download/4.4.1/psalm.phar -O $(toolPsalm)
	chmod +x $(toolPsalm)
	wget https://github.com/vimeo/psalm/releases/download/4.4.1/psalm.phar -O $(toolPsalm)
	chmod +x $(toolPsalm)
	wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar -O $(toolCodeSniffer)
	chmod +x $(toolCodeSniffer)
	wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar -O $(toolCodeFixer)
	chmod +x $(toolCodeFixer)

run: ## basic clearing of history and screen of terminal
	php -S localhost:8080 -t public public/index.php

database_info: ## get entity information
	php ./vendor/bin/doctrine orm:info

database_generate: ## generate database from entity information
	php ./vendor/bin/doctrine orm:generate-entities

test: test_unit test_psalm test_sniff## Run all tests

test_unit: ## run unit tests
	@$(toolPhpunit) -c tests/phpunit.xml
	@php tests/badge_generator.php

test_psalm: ## run psalm static analysis
	@$(toolPsalm) --config='tests/psalm.xml' --show-info=false

test_sniff: ## run psalm static analysis
	@$(toolCodeSniffer) src/

