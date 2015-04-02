<?php

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
        $init = $stackPtr;
        // only check for use statements that are before the first class declaration
        // classes can have use statements for traits, for which we are not interested in this sniff
        $first_class_occurence = $phpcsFile->findPrevious([T_CLASS, T_TRAIT], $stackPtr);
        if ($first_class_occurence > 0 && $stackPtr > $first_class_occurence) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Reach the end of the current statement
        $stackPtr = $phpcsFile->findNext([T_SEMICOLON], ($stackPtr + 1));
        $end_stmt = $stackPtr;

        // if there is another 'use' statement, it should be at $stackPtr + 1
        $next_use   = $phpcsFile->findNext([T_USE], ($stackPtr + 1));
        $next_class = $phpcsFile->findNext([T_CLASS, T_TRAIT], ($stackPtr + 1));

        //There is a class and the next use statement is afte the class definition. skipp it
        if ($next_class && $next_use > $next_class) {
            return;
        }

        //Loop from the end of the use statement (;) untill the next use statement
        for ($i = ($end_stmt + 1); $i <= $next_use; $i++) {
            //the current token ($i) contains an end of line
            //And it's on the next line than the end of the use satement
            if (stristr($tokens[$i]['content'], "\n") !== false &&
            $tokens[$i]['line'] != $tokens[$end_stmt]['line']
            ) {
                $this->checkForNewlineOrComments($phpcsFile, $i);
            }
        }
    }

    private function checkForNewlineOrComments(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] == T_COMMENT) {
            $error = "There shouldn't be anything between 'use' statements.";
            $phpcsFile->addError($error, $stackPtr, 'VerticalWhitespace');
        } elseif (strcmp($tokens[$stackPtr]['content'], "\n") == 0) {
            $error = "Newline should not be present here ";
            $phpcsFile->addError($error, $stackPtr, 'VerticalWhitespace');
        }
    }
}
