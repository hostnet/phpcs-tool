<?php

/**
 * Checks if their is only one use statement in every line.
 * e.g. use SomeSpace, SomeOtherspace;  is not allowed
 * Also looks for inline comments in the middle of use statements
 * e.g. use MySpace\/ * comment * /SubSpace;  is valid PHP
 *
 * @author Nikos Savvidis <nsavvidis@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_OnlyOneUseStatementPerLineSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return [T_USE];
    } // register

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file where the token was found.
     * @param int                  $stack_ptr  The position in the stack where the token was found.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // only check for use statements that are before the first class declaration
        // classes can have use statements for traits, for which we are not interested in this sniff
        $first_class_occurence = $phpcs_file->findPrevious([T_CLASS, T_TRAIT], $stack_ptr);
        if ($first_class_occurence > 0 && $stack_ptr > $first_class_occurence) {
            return;
        }

        // Find the end of the current statement
        $next_semicolon = $phpcs_file->findNext([T_SEMICOLON], ($stack_ptr + 1));

        // look for commas in the current use statement
        $next_comma = $phpcs_file->findNext([T_COMMA], ($stack_ptr + 1));
        if ($next_comma > 0 && $next_comma < $next_semicolon) {
            $error = "There should only be one use statement in each line";
            $phpcs_file->addError($error, $stack_ptr, 'MultipleUseInLine');
        }

        // look for comments in the middle of use statements
        $next_comment = $phpcs_file->findNext([T_COMMENT], ($stack_ptr + 1));
        if ($next_comment > 0 && $next_comment < $next_semicolon) {
            $error = "Inline comments should come after the semicolon";
            $phpcs_file->addError($error, $stack_ptr, 'MultipleUseInLine');
        }
    }
}
