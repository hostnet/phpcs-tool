<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks if the use statements are alphabetically ordered.
 */
class UseStatementsAlphabeticallyOrderedSniff implements Sniff
{
    private $initial_use;

    private $end_use;

    private $fixed = false;

    private $use_statements = [];

    private $after_use_statement = [];

    private $current_file;

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return int[]
     */
    public function register()
    {
        return [T_USE, T_CLASS, T_TRAIT];
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
        if (in_array($phpcs_file->getTokens()[$stack_ptr]['code'], [T_CLASS, T_TRAIT], true)) {
            if ($this->initial_use && $this->end_use && $this->fixed) {
                $this->fixUseStatements($phpcs_file);
            }
            $this->use_statements      = [];
            $this->after_use_statement = [];
            $this->initial_use         = null;
            $this->end_use             = null;

            return;
        }
        // only check for use statements that are before the first class declaration
        // classes can have use statements for traits, for which we are not interested in this sniff
        $first_class_occurrence = $phpcs_file->findPrevious([T_CLASS, T_TRAIT], $stack_ptr);
        if ($first_class_occurrence > 0 && $stack_ptr > $first_class_occurrence) {
            return;
        }

        $filename = $phpcs_file->getFilename();
        if ($this->current_file !== $filename) {
            $this->current_file        = $phpcs_file->getFilename();
            $this->initial_use         = $stack_ptr;
            $this->fixed               = false;
            $this->use_statements      = []; // empty the array for every different file
            $this->after_use_statement = [];
        }

        // Skip whitespace and comments
        $stack_ptr = $phpcs_file->findNext([T_STRING], $stack_ptr + 1);

        // Construct the full use statement (all the subspaces)
        $this->createAndCheckStatements($phpcs_file, $stack_ptr);
        // check if we don't have a class or trait inside the file (but we do have use statements here)
        if (!$this->initial_use || !$this->end_use || !$this->fixed || $phpcs_file->findNext([T_CLASS, T_TRAIT], 0)) {
            return;
        }

        $this->fixUseStatements($phpcs_file);
    }

    private function fixUseStatements(File $phpcs_file)
    {
        $phpcs_file->fixer->beginChangeset();

        usort($this->use_statements, 'strcasecmp');

        $content = implode(
            "\n",
            array_map(
                function ($item) {
                    $extra = ';';
                    if (isset($this->after_use_statement[$item])) {
                        $this->after_use_statement[$item] = rtrim($this->after_use_statement[$item], ", \n");
                        if (!empty($this->after_use_statement[$item])) {
                            $extra = $this->after_use_statement[$item] . ';';
                        }
                    }

                    return 'use ' . $item . $extra;
                },
                $this->use_statements
            )
        );

        $phpcs_file->fixer->replaceToken($this->initial_use, $content);
        for ($i = $this->initial_use + 1; $i < $this->end_use; $i++) {
            $phpcs_file->fixer->replaceToken($i, '');
        }
        $phpcs_file->fixer->endChangeset();
    }

    /**
     * Creates all the use statements until a semicolon is found.
     * Once created, each statement is appended to the $use_statements array
     *
     * @param File $phpcs_file The file where the token was found.
     * @param int  $stack_ptr the current position in the stack
     *
     * @return void
     */
    private function createAndCheckStatements(File $phpcs_file, $stack_ptr)
    {
        $tokens           = $phpcs_file->getTokens();
        $current_use_stmt = '';

        while (!in_array($tokens[$stack_ptr]['code'], [T_WHITESPACE, T_SEMICOLON, T_COMMA], true)) {
            if ($tokens[$stack_ptr]['code'] !== T_COMMENT) {
                //the expression: use MySpace\/*comment*/SubSpace;  is valid PHP
                $current_use_stmt .= $tokens[$stack_ptr]['content'];
            }
            $stack_ptr++;
        }
        $this->end_use = $stack_ptr + 1;

        if (end($this->use_statements) && strcasecmp(end($this->use_statements), $current_use_stmt) > 0) {
            $error       = "Use statement $current_use_stmt should be ordered before " . end($this->use_statements);
            $this->fixed = $phpcs_file->addFixableError($error, $stack_ptr, 'UnorderedUseStatement');
        }
        $this->use_statements[] = $current_use_stmt;

        if ($tokens[$stack_ptr]['code'] === T_COMMA) {
            $new_stack_ptr                                = $phpcs_file->findNext([T_STRING], $stack_ptr + 1);
            $this->end_use                                = $new_stack_ptr + 1;
            $this->after_use_statement[$current_use_stmt] = $this->copy($tokens, $stack_ptr, $new_stack_ptr);
            $this->createAndCheckStatements($phpcs_file, $new_stack_ptr);
        } elseif ($tokens[$stack_ptr]['code'] === T_WHITESPACE) {
            $new_stack_ptr = $phpcs_file->findNext([T_AS, T_SEMICOLON], $stack_ptr + 1);
            $this->end_use = $new_stack_ptr + 1;

            $this->after_use_statement[$current_use_stmt] = $this->copy($tokens, $stack_ptr, $new_stack_ptr);

            if ($tokens[$new_stack_ptr]['code'] === T_AS) {
                $new_stack_ptr = $phpcs_file->findNext([T_COMMA, T_SEMICOLON], $new_stack_ptr + 1);
                $this->end_use = $new_stack_ptr + 1;

                $this->after_use_statement[$current_use_stmt] = $this->copy($tokens, $stack_ptr, $new_stack_ptr);

                if ($tokens[$new_stack_ptr]['code'] === T_COMMA) {
                    $new_stack_ptr = $phpcs_file->findNext([T_STRING], $new_stack_ptr + 1);
                    $this->end_use = $new_stack_ptr + 1;

                    $this->after_use_statement[$current_use_stmt] = $this->copy($tokens, $stack_ptr, $new_stack_ptr);
                    $this->createAndCheckStatements($phpcs_file, $new_stack_ptr);
                }
            }
        }
    }

    private function copy(array &$tokens, $initial_ptr, $end_ptr)
    {
        $res = '';
        for ($i = $initial_ptr; $i < $end_ptr; $i++) {
            $res .= $tokens[$i]['content'];
        }

        return $res;
    }
}
