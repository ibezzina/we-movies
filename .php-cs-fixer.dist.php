<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude('vendor')
    ->notPath('Kernel.php')
    ->notPath('bootstrap.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@PHP80Migration' => true,
        '@DoctrineAnnotation' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP80Migration:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'blank_line_after_opening_tag' => false,
        'concat_space' => ['spacing' => 'one'],
        'native_function_invocation' => false,
        'phpdoc_summary' => false,
        'phpdoc_types_order' => ['null_adjustment' => 'none', 'sort_algorithm' => 'none'],
        'phpdoc_separation' => false,
        'phpdoc_align' => ['align' => 'left'],
        'yoda_style' => false,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
        'heredoc_to_nowdoc' => false,
        'escape_implicit_backslashes' => ['heredoc_syntax' => false],
        'no_superfluous_elseif' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => ['types' => ['normal']],
        'blank_line_before_statement' => ['statements' => ['continue', 'return', 'try']],
    ])
    ->setFinder($finder)
;
