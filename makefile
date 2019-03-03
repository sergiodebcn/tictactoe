all: help

##___  _____     _____ __  __
## | |/   |  /\ /   | /  \|_
## | |\__ | /--\\__ | \__/|__
##
## Available commands:

.PHONY : help
help : Makefile
	@sed -n 's/^##//p' $<

##  install-git-hooks:        	This will install git pre-commit and pre-push hooks for php
##  install-advanced-git-hooks: This will install more complex analysis code tools for pre-commit and pre-push
##  tests:                      Runs unit tests
##  tests-filter:               Runs unit tests with the filter in the inside 'f' argument
##  tests-coverage:             Runs unit tests with coverage and open file
##  static-analyzer:            Runs phpstan code analyzer
##  metrics:          			Show project metrics
##  fix-php:          			Runs php-cs-fixer to src and tests
##  refresh-project:          	Refresh project
##  reload-project:          	Reload docker
##  composer-install-container: Launch composer install

.PHONY: install-git-hooks
install-git-hooks:
	rm -rf .git/hooks/pre-commit.d/*
	rm -rf .git/hooks/pre-push.d/*
	cp -a docs/hooks/prepared_hooks/. .git/hooks/
	chmod +x .git/hooks/pre-commit
	chmod +x .git/hooks/commit-msg
	chmod -R +x .git/hooks/pre-commit.d/
	chmod +x .git/hooks/pre-push
	chmod -R +x .git/hooks/pre-push.d/
	rm .git/hooks/pre-commit.d/*advanced*
	rm .git/hooks/pre-push.d/*advanced*
	.git/hooks/pre-commit
	.git/hooks/pre-push
	$(call print, HOOKS INSTALLED)

.PHONY: install-advanced-git-hooks
install-advanced-git-hooks:
	rm -rf .git/hooks/pre-commit.d/*
	rm -rf .git/hooks/pre-push.d/*
	cp -a docs/hooks/prepared_hooks/. .git/hooks/
	chmod +x .git/hooks/pre-commit
	chmod +x .git/hooks/commit-msg
	chmod -R +x .git/hooks/pre-commit.d/
	chmod +x .git/hooks/pre-push
	chmod -R +x .git/hooks/pre-push.d/
	.git/hooks/pre-commit
	.git/hooks/pre-push
	$(call print, ADVANCED HOOKS INSTALLED)

.PHONY: tests
tests:
	docker exec tic-tac-toe_dev ./bin/phpunit

.PHONY: tests-filter
tests-filter:
	docker exec tic-tac-toe_dev ./bin/phpunit --filter $(f)

.PHONY: tests-coverage
tests-coverage:
	docker exec tic-tac-toe_dev ./bin/phpunit --coverage-html coverage/ tests
	open coverage/index.html

.PHONY: static-analyzer
static-analyzer:
	bin/phpstan analyse -l 7 --no-progress -c config/phpstan/phpstan.neon src/ tests/

.PHONY: metrics
metrics:
	bin/phpmetrics --report-html=metrics src
	open metrics/index.html

.PHONY: fix-php
fix-php:
	php bin/php-cs-fixer fix --using-cache=no --rules=@PSR1,@PSR2,@Symfony --verbose tests
	php bin/php-cs-fixer fix --using-cache=no --rules=@PSR1,@PSR2,@Symfony --verbose src

.PHONY: refresh-project
refresh-project:
	$(MAKE) reload-project
	$(MAKE) composer-install-container

.PHONY: reload-project
reload-project:
	docker-compose down
	docker-compose up --build -d

.PHONY: refresh-sync
refresh-sync:
	docker-sync-stack clean
	docker-compose up --build -d
	docker-sync start
	$(MAKE) composer-install-container

.PHONY: composer-install-container
composer-install-container:
	docker exec tic-tac-toe_dev ./composer.phar install
