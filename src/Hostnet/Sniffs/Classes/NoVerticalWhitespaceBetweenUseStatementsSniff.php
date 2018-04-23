<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks if there is vertical whitespace between use statements
 */
class NoVerticalWhitespaceBetweenUseStatementsSniff implements Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return [T_USE];
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param File     $phpcs_file The file where the token was found.
     * @param int|bool $stack_ptr The position in the stack where the token was found.
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

        $tokens = $phpcs_file->getTokens();

        // Reach the end of the current statement
        $stack_ptr = $phpcs_file->findNext([T_SEMICOLON], $stack_ptr + 1);
        $end_stmt  = $stack_ptr;

        // if there is another 'use' statement, it should be at $stack_ptr + 1
        $next_use   = $phpcs_file->findNext([T_USE], $stack_ptr + 1);
        $next_class = $phpcs_file->findNext([T_CLASS, T_TRAIT], $stack_ptr + 1);

        //There is a class and the next use statement is after the class definition. skipp it
        if ($next_class && $next_use > $next_class) {
            return;
        }

        //Loop from the end of the use statement (;) until the next use statement
        for ($i = ($end_stmt + 1); $i <= $next_use; $i++) {
            //the current token ($i) contains an end of line
            //And it's on the next line than the end of the use statement
            if ($tokens[$i]['line'] === $tokens[$end_stmt]['line']
                || false === stripos($tokens[$i]['content'], "\n")
            ) {
                continue;
            }

            $this->checkForNewlineOrComments($phpcs_file, $i);
        }
    }

    private function checkForNewlineOrComments(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();
        if ($tokens[$stack_ptr]['code'] === T_COMMENT) {
            $error = "There shouldn't be anything between 'use' statements.";
            $phpcs_file->addError($error, $stack_ptr, 'VerticalWhitespace');
        } elseif (strcmp($tokens[$stack_ptr]['content'], "\n") === 0) {
            $error = 'Newline should not be present here ';
            $fixed = $phpcs_file->addFixableError($error, $stack_ptr, 'VerticalWhitespace');
            if ($fixed) {
                $phpcs_file->fixer->replaceToken($stack_ptr, '');
            }
        }
    }
}
