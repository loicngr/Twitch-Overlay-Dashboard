<?php

if (!file_exists(__DIR__ . '/src') || !file_exists(__DIR__ . '/tests')) {
    exit(0);
}

return (new PhpCsFixer\Config())
    ->setRules(
        [
            '@PSR12' => true,
            '@PhpCsFixer' => true,
            '@Symfony' => true,
            '@PHP81Migration' => true,
            'array_syntax' => ['syntax' => 'short'],
            'blank_line_before_statement' => [
                'statements' => [
                    'case',
                    'continue',
                    'default',
                    'do',
                    'exit',
                    'for',
                    'foreach',
                    'goto',
                    'if',
                    'return',
                    'switch',
                    'throw',
                    'try',
                    'while',
                    'yield',
                ],
            ],
            'class_attributes_separation' => [
                'elements' => [
                    'method' => 'one',
                    'property' => 'one',
                ],
            ],
            'concat_space' => ['spacing' => 'one'],
            'doctrine_annotation_array_assignment' => [
                'operator' => ':',
            ],
            'doctrine_annotation_braces' => true,
            'doctrine_annotation_indentation' => [
                'indent_mixed_lines' => true,
            ],
            'doctrine_annotation_spaces' => [
                'after_argument_assignments' => false,
                'before_array_assignments_colon' => false,
                'before_array_assignments_equals' => false,
            ],
            'global_namespace_import' => true,
            'header_comment' => [
                'header' => '',
            ],
            'nullable_type_declaration_for_default_null_value' => true,
            'method_argument_space' => [
                'on_multiline' => 'ensure_fully_multiline',
            ],
            'operator_linebreak' => [
                'position' => 'beginning',
            ],
            'phpdoc_line_span' => [
                'const' => 'single',
                'method' => 'multi',
                'property' => 'single',
            ],
            'phpdoc_tag_casing' => true,
            'simplified_if_return' => true,
            'trailing_comma_in_multiline' => [
                'elements' => [
                    'arrays',
                    'arguments',
                    'parameters',
                ],
            ],
        ],
    )
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->in(__DIR__ . '/migrations')
            ->append([__FILE__]),
    )
;
