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
     * @param PHP_CodeSniffer_File $phpcs_file The file where the token was found.
     * @param int $stack_ptr The position in the stack where the token was found.
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

        $filename = $phpcs_file->getFilename();
        if ($this->current_file !== $filename) {
            $this->current_file   = $phpcs_file->getFilename();
            $this->use_statements = array(); // empty the array for every different file
        }

        // Skip whitespace and comments
        $stack_ptr = $phpcs_file->findNext([T_STRING], ($stack_ptr + 1));

        // Construct the full use statement (all the subspaces)
        $this->createAndCheckStatements($phpcs_file, $stack_ptr);
    }

    /**
     * Creates all the use statements until a semicolon is found.
     * Once created, each statement is appended to the $use_statements array
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file where the token was found.
     * @param int $stack_ptr the current position in the stack
     *
     * @return void
     */
    private function createAndCheckStatements(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $tokens           = $phpcs_file->getTokens();
        $current_use_stmt = "";
        while (!in_array($tokens[$stack_ptr]['code'], [T_WHITESPACE, T_SEMICOLON, T_COMMA])) {
            if ($tokens[$stack_ptr]['code'] != T_COMMENT) {
                //the expression: use MySpace\/*comment*/SubSpace;  is valid PHP
                $current_use_stmt .= $tokens[$stack_ptr]['content'];
            }
            $stack_ptr++;
        }
        if (strcasecmp(end($this->use_statements), $current_use_stmt) > 0) {
            $error = "Use statement $current_use_stmt should be ordered before " . end($this->use_statements);
            $phpcs_file->addError($error, $stack_ptr, 'UnorderedUseStatement');
        }
        array_push($this->use_statements, $current_use_stmt);
        if ($tokens[$stack_ptr]['code'] == T_COMMA) {
            $stack_ptr = $phpcs_file->findNext([T_STRING], ($stack_ptr + 1));
            $this->createAndCheckStatements($phpcs_file, $stack_ptr);
        } elseif ($tokens[$stack_ptr]['code'] == T_WHITESPACE) {
            $stack_ptr = $phpcs_file->findNext([T_AS, T_SEMICOLON], ($stack_ptr + 1));
            if ($tokens[$stack_ptr]['code'] == T_AS) {
                $stack_ptr = $phpcs_file->findNext([T_COMMA, T_SEMICOLON], ($stack_ptr + 1));
                if ($tokens[$stack_ptr]['code'] == T_COMMA) {
                    $stack_ptr = $phpcs_file->findNext([T_STRING], ($stack_ptr + 1));
                    $this->createAndCheckStatements($phpcs_file, $stack_ptr);
                }
            }
        }
    }
}
