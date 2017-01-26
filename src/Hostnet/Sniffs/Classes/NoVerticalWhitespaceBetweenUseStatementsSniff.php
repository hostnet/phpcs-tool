<?php
/**
 * @copyright 2015-2017 Hostnet B.V.
 */

/**
 * Checks if there is vertical whitespace between use statements
 *
 * @author Nikos Savvidis <nsavvidis@hostnet.nl>
 * @author Stefan Lenselink <slenselink@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_NoVerticalWhitespaceBetweenUseStatementsSniff implements \PHP_CodeSniffer_Sniff
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

        $tokens = $phpcs_file->getTokens();

        // Reach the end of the current statement
        $stack_ptr = $phpcs_file->findNext([T_SEMICOLON], ($stack_ptr + 1));
        $end_stmt  = $stack_ptr;

        // if there is another 'use' statement, it should be at $stack_ptr + 1
        $next_use   = $phpcs_file->findNext([T_USE], ($stack_ptr + 1));
        $next_class = $phpcs_file->findNext([T_CLASS, T_TRAIT], ($stack_ptr + 1));

        //There is a class and the next use statement is afte the class definition. skipp it
        if ($next_class && $next_use > $next_class) {
            return;
        }

        //Loop from the end of the use statement (;) untill the next use statement
        for ($i = ($end_stmt + 1); $i <= $next_use; $i++) {
            //the current token ($i) contains an end of line
            //And it's on the next line than the end of the use satement
            if (stristr($tokens[$i]['content'], "\n") !== false
                && $tokens[$i]['line'] != $tokens[$end_stmt]['line']) {
                $this->checkForNewlineOrComments($phpcs_file, $i);
            }
        }
    }

    private function checkForNewlineOrComments(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();
        if ($tokens[$stack_ptr]['code'] == T_COMMENT) {
            $error = "There shouldn't be anything between 'use' statements.";
            $phpcs_file->addError($error, $stack_ptr, 'VerticalWhitespace');
        } elseif (strcmp($tokens[$stack_ptr]['content'], "\n") == 0) {
            $error = "Newline should not be present here ";
            $fixed = $phpcs_file->addFixableError($error, $stack_ptr, 'VerticalWhitespace');
            if ($fixed) {
                $phpcs_file->fixer->replaceToken($stack_ptr, "");
            }
        }
    }
}
