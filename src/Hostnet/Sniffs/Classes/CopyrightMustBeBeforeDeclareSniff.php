<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class CopyrightMustBeBeforeDeclareSniff implements Sniff
{
    private const ERROR = 'declare(strict_types=1) should be after the copyright statement';

    /**
     * {@inheritdoc}
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcs_file, $stack_ptr)
    {
        $tokens      = $phpcs_file->getTokens();
        $end_of_file = count($tokens) + 1;

        // Find the first declare.
        $declare = $phpcs_file->findNext(T_DECLARE, $stack_ptr + 1, null, false, null, true);

        // No declare found, abort.
        if (false === $declare) {
            return $end_of_file;
        }

        // Find the namespace declaration
        $namespace = $phpcs_file->findNext(T_NAMESPACE, $declare + 1);

        // There is no namespace, abort!
        if (false === $namespace) {
            return $end_of_file;
        }

        // Find the first docblock between declare and the namespace declaration, if any.
        $doc = $phpcs_file->findNext(T_DOC_COMMENT_OPEN_TAG, $stack_ptr + 1, $namespace);

        // There is no docblock, abort.
        if (false === $doc) {
            return $end_of_file;
        }

        // The docblock is before the declare, abort.
        if ($doc < $declare) {
            return $end_of_file;
        }

        // There is a declare before the docblock
        if ($phpcs_file->addFixableError(self::ERROR, $declare, 'DeclareStrictAtStart')) {
            // Remove the strict_types
            for ($i = $declare; $i <= $doc - 1; $i++) {
                $phpcs_file->fixer->replaceToken($i, '');
            }

            // Re-add after the docblock
            $close_doc = $phpcs_file->findNext(T_DOC_COMMENT_CLOSE_TAG, $doc, $namespace);
            $phpcs_file->fixer->addContent($close_doc + 1, "declare(strict_types=1);$phpcs_file->eolChar");

            return $end_of_file;
        }

        return $end_of_file;
    }
}
