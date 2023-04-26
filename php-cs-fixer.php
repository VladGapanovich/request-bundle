<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/config');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '&' => null,
                '|' => null,
            ],
        ],
        'date_time_immutable' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'ordered_class_elements' => [
            'order' => ['use_trait', 'case', 'constant', 'property', 'construct', 'destruct', 'magic', 'phpunit', 'method'],
        ],
        'ordered_imports' => true,
        'declare_strict_types' => true,
        'single_import_per_statement' => false,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'phpdoc',
                'return',
                'throw',
                'try',
            ],
        ],
        'global_namespace_import' => [
            'import_classes' => true,
        ],
        'phpdoc_align' => false,
        'single_trait_insert_per_statement' => false,
        'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['arguments', 'parameters']],
        'php_unit_mock' => ['target' => 'newest'],
        'php_unit_namespaced' => ['target' => 'newest'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_set_up_tear_down_visibility' => true,
    ]);
