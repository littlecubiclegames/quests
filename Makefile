ci: test cs phpstan

cs:
	PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src --rules=@PSR2,binary_operator_spaces,blank_line_before_statement,function_typehint_space,no_empty_comment,no_empty_phpdoc,no_empty_statement,no_extra_blank_lines,no_leading_import_slash,no_leading_namespace_whitespace,trailing_comma_in_multiline,space_after_semicolon,single_quote,return_type_declaration,no_unused_imports,declare_strict_types --allow-risky=yes
	PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix tests --rules=@PSR2,binary_operator_spaces,blank_line_before_statement,function_typehint_space,no_empty_comment,no_empty_phpdoc,no_empty_statement,no_extra_blank_lines,no_leading_import_slash,no_leading_namespace_whitespace,trailing_comma_in_multiline,space_after_semicolon,single_quote,return_type_declaration,no_unused_imports,declare_strict_types --allow-risky=yes

test:
	./vendor/bin/phpunit tests

phpstan:
	./vendor/bin/phpstan analyse src tests --level=7 --memory-limit=2G
