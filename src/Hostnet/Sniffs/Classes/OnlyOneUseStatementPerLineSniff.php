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
        return array(T_USE);
    } // register

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int $stackPtr The position in the stack where the token was found.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // only check for use statements that are before the first class declaration
        // classes can have use statements for traits, for which we are not interested in this sniff
        $first_class_occurence = $phpcsFile->findPrevious([T_CLASS, T_TRAIT], $stackPtr);
        if ($first_class_occurence > 0 && $stackPtr > $first_class_occurence) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Find the end of the current statement
        $next_semicolon = $phpcsFile->findNext([T_SEMICOLON], ($stackPtr + 1));

        // Find the next newline character
        $next_newline = $phpcsFile->findNext([T_WHITESPACE], ($stackPtr + 1), null, false, "\n");

        // look for commas in the current use statement
        $next_comma = $phpcsFile->findNext([T_COMMA], ($stackPtr + 1));
        if ($next_comma > 0 && $next_comma < $next_semicolon) {
            $error = "There should only be one use statement in each line";
            $phpcsFile->addError($error, $stackPtr, 'MultipleUseInLine');
        }

        // look for comments in the middle of use statements
        $next_comment = $phpcsFile->findNext([T_COMMENT], ($stackPtr + 1));
        if ($next_comment > 0 && $next_comment < $next_semicolon) {
            $error = "Inline comments should come after the semicolon";
            $phpcsFile->addError($error, $stackPtr, 'MultipleUseInLine');
        }
    }
}
