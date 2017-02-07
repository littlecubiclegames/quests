ci: test cs

cs:
	./vendor/bin/php-cs-fixer fix src
	./vendor/bin/php-cs-fixer fix tests

test:
	./vendor/bin/phpunit tests

