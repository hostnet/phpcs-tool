<?php

/**
 * The name of traits MUST end with the word 'Trait'.
 *
 * https://wiki.hostnetbv.nl/Coding_Standards#3.1.9
 */
class Hostnet_Sniffs_Classes_TraitMustBePostfixedWithTraitSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * @return string[]
     */
    public function register()
    {
        return [T_TRAIT];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpcs_file
     * @param int                   $stack_ptr
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // Search till trait name.
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index]) &&
            $phpcs_file->getTokens()[$stack_ptr + ($index)]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr    = $stack_ptr + $index;
        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (preg_match('/Trait$/', $f_name)) {
            return;
        }

        $phpcs_file->addError('Invalid trait name, trait should be postfixed with Trait.', $ptr);

        return;
    }
}
