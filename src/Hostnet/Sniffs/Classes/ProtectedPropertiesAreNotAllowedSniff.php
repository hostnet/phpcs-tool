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
        return array(T_PROTECTED);
    }

    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $originalStackPtr = $stackPtr;

        $tokens = $phpcsFile->getTokens();

        // Move the stack pointer to the next token
        $stackPtr++;

        // Skip whitespace and comments
        while ($tokens[$stackPtr]['code'] == T_WHITESPACE
              || $tokens[$stackPtr]['code'] == T_COMMENT) {
            $stackPtr++;
        }

        // Check if the next token is a variable
        if ($tokens[$stackPtr]['code'] == T_VARIABLE) {
            // If yes: give error
            $error = 'Protected property "' . $tokens[$stackPtr]['content'] . '" is not allowed.';
            $phpcsFile->addError($error, $originalStackPtr, 'ProtectedProperty');
        }
    }
}
