<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Declares;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Each file should declare strict types = 1, to enable PHP7+ strict mode.
 * Fix included.
 */
class StrictSniff implements Sniff
{
    const ERROR = 'declare(strict_types = 1) not found';

    const R_VALUE = [
        T_LNUMBER,                    //integers
        T_STRING,                     //identifiers
        T_NUM_STRING,                 // numeric array index inside string
        T_TRUE,                       // true
        T_FALSE,                      // false
        T_CONSTANT_ENCAPSED_STRING,   // string without variable(s) inside
        T_DOUBLE_QUOTED_STRING,       // double quoted string with variable(s) inside
        T_DNUMBER,                    // floating point numbers
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * The PHP open tag is returned, because we want to find the
     * first statement in the file.
     *
     * @return int[]
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int  $stack_ptr The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return int
     */
    public function process(File $phpcs_file, $stack_ptr): int
    {
        $tokens = $phpcs_file->getTokens();
        $eof    = count($tokens) + 1; // Do not visit this file again for this sniff.

        // Find the next declare.
        $declare = $phpcs_file->findNext(T_DECLARE, $stack_ptr + 1, null, false, null, true);

        // No declare found at start of file.
        if (false === $declare) {
            if ($phpcs_file->addFixableError(self::ERROR, $stack_ptr, 'NoStrict')) {
                $this->addDeclare($phpcs_file, $stack_ptr);
            }

            return $eof;
        }

        // Check type of declare.
        $key = $phpcs_file->findNext(T_STRING, $declare + 1, null, false, 'strict_types', true);
        if (false === $key) {
            if ($phpcs_file->addFixableError(self::ERROR, $declare, 'NoStrict')) {
                $this->addDeclare($phpcs_file, $stack_ptr);
            }

            return $eof;
        }

        // Check if a value assignment exists for this strict_types declare.
        $value = $phpcs_file->findNext(self::R_VALUE, $key + 1, null, false, null, true);
        if (false === $value) {
            if ($phpcs_file->addFixableError(self::ERROR, $key, 'NoStrictValue')) {
                if (false === $phpcs_file->findNext(T_EQUAL, $key, false, null, null, true)) {
                    $phpcs_file->fixer->addContent($key, ' = 1');
                } else {
                    $phpcs_file->fixer->addContent($key, '1');
                }
            }

            return $eof;
        }

        // Check the value for for strict_types === '1'.
        if ('1' !== $tokens[$value]['content']) {
            if ($phpcs_file->addFixableError(self::ERROR, $value, 'StrictTurnedOff')) {
                $phpcs_file->fixer->replaceToken($value, '1');
            }

            return $eof;
        }

        return $eof;
    }

    /**
     * Add a "declare(strict_types = 1);" statement to $phpcs_file at the $stack_ptr position.
     *
     * @param File $phpcs_file
     * @param int  $stack_ptr
     *
     * @return bool
     */
    private function addDeclare(File $phpcs_file, int $stack_ptr): bool
    {
        return $phpcs_file->fixer->addContent($stack_ptr, 'declare(strict_types=1);' . $phpcs_file->eolChar);
    }
}
