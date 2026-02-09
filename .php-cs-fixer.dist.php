<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude([
        '.cache',
        'vendor',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.cache/php-cs-fixer/.php-cs-fixer.cache')
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,

        'array_push' => true,

        'mb_str_functions' => true,
        'modernize_strpos' => true,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'set_type_to_cast' => true,
        'array_syntax' => ['syntax' => 'short'],

        'concat_space' => ['spacing' => 'none'],

        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,

        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters', 'match'],
        ],

        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => false,
            'import_functions' => false,
        ],

        'php_unit_test_class_requires_covers' => true,
        'php_unit_internal_class' => false,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ])
    ->setFinder($finder);
