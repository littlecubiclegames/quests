ci: test cs phpstan

cs:
	./vendor/bin/php-cs-fixer fix src --rules=@PSR2,binary_operator_spaces,blank_line_before_return,function_typehint_space,no_empty_comment,no_empty_phpdoc,no_empty_statement,no_extra_consecutive_blank_lines,no_leading_import_slash,no_leading_namespace_whitespace,trailing_comma_in_multiline_array,space_after_semicolon,single_quote,return_type_declaration,no_unused_imports,declare_strict_types --allow-risky=yes
	./vendor/bin/php-cs-fixer fix tests --rules=@PSR2,binary_operator_spaces,blank_line_before_return,function_typehint_space,no_empty_comment,no_empty_phpdoc,no_empty_statement,no_extra_consecutive_blank_lines,no_leading_import_slash,no_leading_namespace_whitespace,trailing_comma_in_multiline_array,space_after_semicolon,single_quote,return_type_declaration,no_unused_imports,declare_strict_types --allow-risky=yes

test:
	./vendor/bin/phpunit tests

phpstan:
	./vendor/bin/phpstan analyse src tests --level=7
