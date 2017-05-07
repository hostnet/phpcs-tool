<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks if their is only one use statement in every line.
 * e.g. use SomeSpace, SomeOtherSpace;  is not allowed
 * Also looks for inline comments in the middle of use statements
 * e.g. use MySpace\/ * comment * /SubSpace;  is valid PHP
 */
class OnlyOneUseStatementPerLineSniff implements Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return int[]
     */
    public function register()
    {
        return [T_USE];
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param File $phpcs_file The file where the token was found.
     * @param int  $stack_ptr The position in the stack where the token was found.
     *
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        // only check for use statements that are before the first class declaration
        // classes can have use statements for traits, for which we are not interested in this sniff
        $first_class_occurrence = $phpcs_file->findPrevious([T_CLASS, T_TRAIT], $stack_ptr);
        if ($first_class_occurrence > 0 && $stack_ptr > $first_class_occurrence) {
            return;
        }

        // Find the end of the current statement
        $next_semicolon = $phpcs_file->findNext([T_SEMICOLON], $stack_ptr + 1);

        // look for commas in the current use statement
        $next_comma = $phpcs_file->findNext([T_COMMA], $stack_ptr + 1);
        if ($next_comma > 0 && $next_comma < $next_semicolon) {
            $error = 'There should only be one use statement in each line';
            $phpcs_file->addError($error, $stack_ptr, 'MultipleUseInLine');
        }

        // look for comments in the middle of use statements
        $next_comment = $phpcs_file->findNext([T_COMMENT], $stack_ptr + 1);
        if ($next_comment > 0 && $next_comment < $next_semicolon) {
            $error = 'Inline comments should come after the semicolon';
            $phpcs_file->addError($error, $stack_ptr, 'MultipleUseInLine');
        }
    }
}
