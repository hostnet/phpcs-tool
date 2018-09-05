<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\PhpUnit;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPUnit\Framework\TestCase;

/**
 * PHPUnit 5 deprecated the \PHPUnit_Framework_TestCase class in favour of \PHPUnit\Framework\TestCase.
 * In PHPUnit 6 the old class is removed. Detect and replace usages of the old class.
 */
class NamespaceSniff implements Sniff
{
    private const NON_NAMESPACE_TEST_CLASS = 'PHPUnit_Framework_TestCase';
    private const PARENT_TEST_CLASS        = 'TestCase';
    private const WARNING                  = 'Usage of '
        . self::NON_NAMESPACE_TEST_CLASS
        . ' found, please use '
        . TestCase::class . '.';

    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_EXTENDS];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr): int
    {
        $tokens = $phpcs_file->getTokens();

        // Find the parent class
        $class_ptr = $phpcs_file->findNext(T_STRING, $stack_ptr + 1, null, false, null, true);


        if (false === $class_ptr) {
            return $stack_ptr;
        }

        $class = $tokens[$class_ptr]['content'];

        if (strtolower(self::NON_NAMESPACE_TEST_CLASS) === strtolower($class)) {
            if ($phpcs_file->addFixableWarning(self::WARNING, $class_ptr, 'phpunitNs')) {
                $this->fix($phpcs_file, $class_ptr);
            }
        }

        return $class_ptr;
    }

    private function fix(File $phpcs_file, int $class_ptr): void
    {
        $tokens    = $phpcs_file->getTokens();
        $stack_ptr = $phpcs_file->findNext([T_CLASS, T_USE], 0);

        // If there are no use statements yet, we have to add a newline afterwards.
        if (T_CLASS === $tokens[$stack_ptr]['code']) {
            $previous = $phpcs_file->findPrevious(T_WHITESPACE, $stack_ptr - 1, $stack_ptr - 2, true);

            $previous = $phpcs_file->findPrevious(
                [
                    T_DOC_COMMENT_OPEN_TAG,
                    T_DOC_COMMENT,
                    T_DOC_COMMENT_CLOSE_TAG,
                    T_DOC_COMMENT_STAR,
                    T_DOC_COMMENT_STRING,
                    T_DOC_COMMENT_TAG,
                    T_DOC_COMMENT_WHITESPACE,
                    T_COMMENT,
                ],
                false !== $previous ? $previous - 1 : $stack_ptr - 1,
                0,
                true
            );

            $phpcs_file->fixer->addContent(
                false !== $previous ? $previous : $stack_ptr - 1,
                'use ' . TestCase::class . ';' . PHP_EOL . PHP_EOL
            );
        } else {
            // Add use statement
            $phpcs_file->fixer->addContentBefore($stack_ptr, 'use ' . TestCase::class . ';' . PHP_EOL);
        }

        // Remove the \ before PHPUnit_Framework_TestCase
        if (T_NS_SEPARATOR === $tokens[$class_ptr - 1]['code']) {
            $phpcs_file->fixer->replaceToken($class_ptr - 1, '');
        }

        // Replace PHPUnit_Framework_TestCase and with TestCase
        $phpcs_file->fixer->replaceToken($class_ptr, self::PARENT_TEST_CLASS);
    }
}
