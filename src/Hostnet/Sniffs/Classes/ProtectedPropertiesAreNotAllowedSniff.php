<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks if a property is defined as protected.
 */
class ProtectedPropertiesAreNotAllowedSniff implements Sniff
{
    /**
     * @return int[]
     */
    public function register()
    {
        return [T_PROTECTED];
    }

    /**
     * @param File $phpcs_file
     * @param int  $stack_ptr
     *
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        $original_stack_ptr = $stack_ptr;

        $tokens = $phpcs_file->getTokens();

        // Move the stack pointer to the next token
        $stack_ptr++;

        // Skip whitespace and comments
        while ($tokens[$stack_ptr]['code'] === T_WHITESPACE
               || $tokens[$stack_ptr]['code'] === T_COMMENT) {
            $stack_ptr++;
        }

        // Check if the next token is a variable
        if ($tokens[$stack_ptr]['code'] !== T_VARIABLE) {
            return;
        }

        // If yes: give error
        $error = 'Protected property "' . $tokens[$stack_ptr]['content'] . '" is not allowed.';
        $phpcs_file->addError($error, $original_stack_ptr, 'ProtectedProperty');
    }
}
