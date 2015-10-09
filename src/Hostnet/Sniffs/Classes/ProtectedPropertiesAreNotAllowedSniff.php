<?php

/**
 * Checks if a property is defined as protected.
 *
 * @author Lian Ien Hemminga-Oei <loei@hostnet.nl>
 * @author Rick Prent <rprent@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_ProtectedPropertiesAreNotAllowedSniff implements \PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return [T_PROTECTED];
    }

    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $originalstack_ptr = $stack_ptr;

        $tokens = $phpcs_file->getTokens();

        // Move the stack pointer to the next token
        $stack_ptr++;

        // Skip whitespace and comments
        while ($tokens[$stack_ptr]['code'] == T_WHITESPACE
              || $tokens[$stack_ptr]['code'] == T_COMMENT) {
            $stack_ptr++;
        }

        // Check if the next token is a variable
        if ($tokens[$stack_ptr]['code'] == T_VARIABLE) {
            // If yes: give error
            $error = 'Protected property "' . $tokens[$stack_ptr]['content'] . '" is not allowed.';
            $phpcs_file->addError($error, $originalstack_ptr, 'ProtectedProperty');
        }
    }
}
