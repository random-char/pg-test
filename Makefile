build:
	docker build -t pg .

composer:
	docker run --rm --interactive --tty --volume ${PWD}:/app --user $$(id -u):$$(id -g) composer install

run:
	docker run --rm --name pg-running --volume ${PWD}:/app pg

test:
	docker run --rm --name pg-test --volume ${PWD}:/app pg ./vendor/bin/phpunit tests

test-coverage:
	docker run --rm \
		--name pg-test \
		--volume ${PWD}:/app \
		-e XDEBUG_MODE=coverage \
		pg ./vendor/bin/phpunit --coverage-text tests
