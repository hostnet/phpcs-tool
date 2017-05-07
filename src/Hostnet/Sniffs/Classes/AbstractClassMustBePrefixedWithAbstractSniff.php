<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The name of abstract classes MUST start with the word 'Abstract'.
 */
class AbstractClassMustBePrefixedWithAbstractSniff implements Sniff
{
    /**
     * @return string[]
     */
    public function register()
    {
        return [T_ABSTRACT];
    }

    /**
     * @param File $phpcs_file
     * @param int  $stack_ptr
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        // Next should be T_WHITESPACE and then T_CLASS (prevent abstract functions from triggering).
        $index = 2;
        if ($phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_CLASS') {
            return;
        }

        // Then find first string.
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index])
               && $phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr    = $stack_ptr + $index;
        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (0 === strpos($f_name, 'Abstract')) {
            return;
        }

        $phpcs_file->addError(
            'Invalid class name, abstract class name should be prefixed with Abstract.',
            $ptr,
            'abstract'
        );
    }
}
