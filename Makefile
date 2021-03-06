install:
	composer install
lint:
	composer exec phpcs -- --standard=PSR12 src bin tests
lint-fix:
	composer exec phpcbf -- --standard=PSR12 src bin tests
	
test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml