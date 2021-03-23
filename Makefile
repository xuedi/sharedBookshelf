export HOST_USER_ID=$(shell id -u)

default: help

# docker
dbContainer=sharedBookshelfDatabase
cliContainer=sharedBookshelfPhp

# paths
TestVolume='/mnt/code/'
TestTools=$(TestVolume)'tools/'

# tools
toolComposer=$(TestTools)'composer.phar'
toolPhpunit=$(TestTools)'phpunit.phar'
toolPsalm=$(TestTools)'psalm.phar'
toolCodeSniffer=$(TestTools)'phpcs.phar'
toolCodeFixer=$(TestTools)'phpcbf.phar'

RUN=docker run --rm --name="$(cliContainer)" --volume $(shell pwd):/mnt/code/ shared-bookshelf/php-cli /bin/ash -c

help: ## Show this help
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: docker_build composer_install update_tools ## install app

update: ## Update composer dependencies
	@echo "++++++++++++++++++++++++++++++ UPDATE ++++++++++++++++++++++++++++++++++++++++"
	$(RUN) "$(toolComposer) update"

update_tools: ## Upgrade Tools & Composer major versions
	@echo "++++++++++++++++++++++++++++++ UPGRADE +++++++++++++++++++++++++++++++++++++++"
	@$(RUN) "$(toolComposer) self-update"
	@$(RUN) "wget https://phar.phpunit.de/phpunit-9.phar -O $(toolPhpunit)"
	@$(RUN) "chmod +x $(toolPhpunit)"
	@$(RUN) "wget https://github.com/vimeo/psalm/releases/download/4.4.1/psalm.phar -O $(toolPsalm)"
	@$(RUN) "chmod +x $(toolPsalm)"
	@$(RUN) "wget https://github.com/vimeo/psalm/releases/download/4.4.1/psalm.phar -O $(toolPsalm)"
	@$(RUN) "chmod +x $(toolPsalm)"
	@$(RUN) "wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar -O $(toolCodeSniffer)"
	@$(RUN) "chmod +x $(toolCodeSniffer)"
	@$(RUN) "wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar -O $(toolCodeFixer)"
	@$(RUN) "chmod +x $(toolCodeFixer)"

run: ## basic clearing of history and screen of terminal
	@echo "++++++++++++++++++++++++++++++ RUN APP +++++++++++++++++++++++++++++++++++++++"
	@$(RUN) "php -S localhost:8080 -t public public/index.php"

process: database_create ## rebuild the eventStore cached projections TODO: use symfony cli and rename cli
	@echo "++++++++++++++++++++++++++++++ REBUILD DB&EVENTS +++++++++++++++++++++++++++++"
	@$(RUN) "php cli/process.php"

database_create: ## generate database from entity information
	@echo "++++++++++++++++++++++++++++++ DATABASE REBUILD ++++++++++++++++++++++++++++++"
	#docker run -d -p 3306:3306 --rm --name="$(dbContainer)" -e MYSQL_ROOT_PASSWORD=admin -e MYSQL_DATABASE=sharedBookshelf shared-bookshelf/database
	@$(RUN) "php ./vendor/bin/doctrine orm:schema-tool:drop --force"
	@$(RUN) "php ./vendor/bin/doctrine orm:schema-tool:create"
	@$(RUN) "php ./config/executeFixtures.php"

docker_build: ## Build docker images
	@echo "++++++++++++++++++++++++++++++ DOCKER BUILD ++++++++++++++++++++++++++++++++++"
	@docker build --tag shared-bookshelf/php-cli --file docker/php-cli.dockerfile .
	@docker build --tag shared-bookshelf/database --file docker/database.dockerfile .

composer_install: ## Install composer dependencies
	@echo "++++++++++++++++++++++++++++++ COMPOSER INSTALL ++++++++++++++++++++++++++++++"
	@$(RUN) "php $(toolComposer) install"

test: test_unit test_psalm test_sniff ## Run all tests

test_unit: ## run unit tests
	@echo "++++++++++++++++++++++++++++++ PHP UNIT ++++++++++++++++++++++++++++++++++++++"
	@$(RUN) "php $(toolPhpunit) -c $(TestVolume)/tests/phpunit.xml"
	@$(RUN) "php tests/badge_generator.php"

test_psalm: ## run psalm static analysis
	@echo "++++++++++++++++++++++++++++++ PSALM +++++++++++++++++++++++++++++++++++++++++"
	@$(RUN) "php $(toolPsalm) --threads=8 --config='tests/psalm.xml' src/"

test_sniff: ## run psalm static analysis
	@echo "++++++++++++++++++++++++++++++ CodeSniffer +++++++++++++++++++++++++++++++++++"
	@$(RUN) "$(toolCodeSniffer) --colors -p --standard=./tests/phpcs.xml --severity=1 --warning-severity=8 src/ tests/unit/"

