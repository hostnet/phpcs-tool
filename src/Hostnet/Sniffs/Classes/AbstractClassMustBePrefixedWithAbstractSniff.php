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
        // Next should be T_WHITESPACE and then T_CLASS (prevent abstract functions from triggering).
        $index = 2;
        if ($phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_CLASS') {
            return;
        }

        // Then find first string.
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

        $phpcs_file->addWarning('Invalid class name, abstract class should be prefixed with Abstract.', $ptr);

        return;
    }
}
