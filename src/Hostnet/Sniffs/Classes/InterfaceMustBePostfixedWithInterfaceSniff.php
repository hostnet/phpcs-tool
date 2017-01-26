<?php
/**
 * @copyright 2015-2017 Hostnet B.V.
 */

/**
 * The name of interfaces MUST end with the word 'Interface'.
 *
 * https://wiki.hostnetbv.nl/Coding_Standards#3.1.7
 *
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_InterfaceMustBePostfixedWithInterfaceSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * @return string[]
     */
    public function register()
    {
        return [T_INTERFACE];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpcs_file
     * @param int                   $stack_ptr
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // Search till interface name.
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index]) &&
            $phpcs_file->getTokens()[$stack_ptr + ($index)]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr    = $stack_ptr + $index;
        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (preg_match('/Interface$/', $f_name)) {
            return;
        }

        $phpcs_file->addError('Invalid interface name, interface should be postfixed with Interface.', $ptr);

        return;
    }
}
