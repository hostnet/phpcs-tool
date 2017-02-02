<?php
declare(strict_types = 1);
/**
 * @copyright 2017 Hostnet B.V.
 */
/**
 * @copyright 2017 Hostnet B.V.
 */

class Hostnet_Sniffs_NamingConventions_MethodNameStartsWithGetIsSniff implements \PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return [
            T_FUNCTION
        ];
    }

    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // search till T_STRING
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index]) &&
            $phpcs_file->getTokens()[$stack_ptr + ($index)]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr = $stack_ptr + $index;

        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (preg_match('~^getIS~', $f_name)) {
            return;
        }

        $matches = [];
        if (preg_match('~^get([Ii]s[A-Z0-9]{1}.*)~', $f_name, $matches)) {
            $suggested = 'i' . substr($matches[0], 4);
            $phpcs_file->addError('Invalid method name to get Boolean value. Suggested: ' . $suggested, $ptr);

            return;
        }

        if (preg_match('~^getis[a-zA-Z0-9]*~', $f_name)) {
            $phpcs_file->addError('Invalid method name, do not use getis(.*)', $ptr);

            return;
        }
    }
}
