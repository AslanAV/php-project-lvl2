install:
	composer install

lint:
	composer exec phpcs -- --standard=PSR12 src tests
	composer exec phpstan -- --level=8 analyse src tests

lint-fix:
	composer exec phpcbf -- --standard=PSR12 src tests

test:
	composer exec phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

validate:
	composer validate