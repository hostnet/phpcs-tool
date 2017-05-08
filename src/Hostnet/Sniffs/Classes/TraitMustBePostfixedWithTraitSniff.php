<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The name of traits MUST end with the word 'Trait'.
 */
class TraitMustBePostfixedWithTraitSniff implements Sniff
{
    /**
     * @return int[]
     */
    public function register()
    {
        return [T_TRAIT];
    }

    /**
     * @param File $phpcs_file
     * @param int  $stack_ptr
     *
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        // Search till trait name.
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index]) &&
            $phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr    = $stack_ptr + $index;
        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (preg_match('/Trait$/', $f_name)) {
            return;
        }

        $phpcs_file->addError('Invalid trait name, trait should be postfixed with Trait.', $ptr, 'trait');
    }
}
