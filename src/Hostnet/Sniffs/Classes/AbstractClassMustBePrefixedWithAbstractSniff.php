<?php

/**
 * The name of abstract classes MUST start with the word 'Abstract'.
 *
 * https://wiki.hostnetbv.nl/Coding_Standards#3.1.6
 *
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_AbstractClassMustBePrefixedWithAbstractSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * @return string[]
     */
    public function register()
    {
        return [T_ABSTRACT];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpcs_file
     * @param int                   $stack_ptr
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // Search till class name.
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index]) &&
            $phpcs_file->getTokens()[$stack_ptr + ($index)]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr    = $stack_ptr + $index;
        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (preg_match('/^Abstract/', $f_name)) {
            return;
        }

        $phpcs_file->addError('Invalid class name, abstract class should be prefixed with Abstract.', $ptr);

        return;
    }
}
