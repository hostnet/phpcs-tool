<?php

/**
 * Checks if the use statements are alphabetically ordered
 *
 * @author Nikos Savvidis <nsavvidis@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_UseStatementsAlphabeticallyOrderedSniff implements \PHP_CodeSniffer_Sniff
{

    private $use_statements = array();

    private $current_file = null;

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_USE);
    }

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

        $filename = $phpcsFile->getFilename();
        if ($this->current_file !== $filename) {
            $this->current_file   = $phpcsFile->getFilename();
            $this->use_statements = array(); // empty the array for every different file
        }

        // Skip whitespace and comments
        $stackPtr = $phpcsFile->findNext([T_STRING], ($stackPtr + 1));

        // Construct the full use statement (all the subspaces)
        $this->createAndCheckStatements($phpcsFile, $stackPtr);
    }

    /**
     * Creates all the use statements until a semicolon is found.
     * Once created, each statement is appended to the $use_statements array
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int $stackPtr the current position in the stack
     *
     * @return void
     */
    private function createAndCheckStatements(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens           = $phpcsFile->getTokens();
        $current_use_stmt = "";
        while (!in_array($tokens[$stackPtr]['code'], [T_WHITESPACE, T_SEMICOLON, T_COMMA])) {
            if ($tokens[$stackPtr]['code'] != T_COMMENT) {
                //the expression: use MySpace\/*comment*/SubSpace;  is valid PHP
                $current_use_stmt .= $tokens[$stackPtr]['content'];
            }
            $stackPtr++;
        }
        if (strcasecmp(end($this->use_statements), $current_use_stmt) > 0) {
            $error = "Use statement $current_use_stmt should be ordered before " . end($this->use_statements);
            $phpcsFile->addError($error, $stackPtr, 'UnorderedUseStatement');
        }
        array_push($this->use_statements, $current_use_stmt);
        if ($tokens[$stackPtr]['code'] == T_COMMA) {
            $stackPtr = $phpcsFile->findNext([T_STRING], ($stackPtr + 1));
            $this->createAndCheckStatements($phpcsFile, $stackPtr);
        } elseif ($tokens[$stackPtr]['code'] == T_WHITESPACE) {
            $stackPtr = $phpcsFile->findNext([T_AS, T_SEMICOLON], ($stackPtr + 1));
            if ($tokens[$stackPtr]['code'] == T_AS) {
                $stackPtr = $phpcsFile->findNext([T_COMMA, T_SEMICOLON], ($stackPtr + 1));
                if ($tokens[$stackPtr]['code'] == T_COMMA) {
                    $stackPtr = $phpcsFile->findNext([T_STRING], ($stackPtr + 1));
                    $this->createAndCheckStatements($phpcsFile, $stackPtr);
                }
            }
        }
    }
}
