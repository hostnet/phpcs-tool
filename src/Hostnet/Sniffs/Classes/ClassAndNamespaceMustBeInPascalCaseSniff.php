<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Namespaces MUST be written in PascalCase (UpperCamelCase) (i.e. Hostnet/Xml/Formatter).
 * Class names MUST be declared in PascalCase (i.e. XmlFormatter).
 */
class ClassAndNamespaceMustBeInPascalCaseSniff implements Sniff
{
    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_CLASS, T_INTERFACE, T_TRAIT, T_NAMESPACE];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr): void
    {
        $index = 0;
        // Find first string (= name).
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index])
               && $phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr     = $stack_ptr + $index;
        $f_names = [$phpcs_file->getTokens()[$ptr]['content']];

        // If it's a namespace, get all parts.
        $ptr++;
        while ($phpcs_file->getTokens()[$ptr]['type'] === 'T_NS_SEPARATOR') {
            $ptr++;
            $f_names[] = $phpcs_file->getTokens()[$ptr]['content'];
            $ptr++;
        }

        foreach ($f_names as $f_name) {
            if (preg_match('/[A-Z]{2,}/', $f_name)) {
                $type = $phpcs_file->getTokens()[$stack_ptr]['content'];
                $phpcs_file->addError(
                    sprintf('Invalid %1$s name, %1$s name should be in PascalCase.', $type),
                    $ptr,
                    'pascal'
                );
                break;
            }
        }
    }
}
