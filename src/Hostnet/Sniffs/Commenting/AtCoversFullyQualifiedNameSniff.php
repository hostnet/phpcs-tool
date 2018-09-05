<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\FileCommentSniff;

/**
 * This Sniff sniffs that all files examined have a correct @covers notation + added a fixer for those cases.
 */
class AtCoversFullyQualifiedNameSniff extends FileCommentSniff
{
    const ERROR_TYPE    = 'AtCoversNeedsFQCN';
    const ERROR_MESSAGE = 'Covers annotation should use fully qualified class name (it should start with a "\") "%s"';

    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_DOC_COMMENT_TAG];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        // Is it a unit test?
        if (false === strpos($phpcs_file->getFilename(), 'Test.php')) {
            // No, skip the rest of it
            return count($tokens) + 1;
        }

        // The tag i found is it a @covers tag?
        if ('@covers' !== $tokens[$stack_ptr]['content']) {
            return $stack_ptr;
        }

        $class_name_ptr = $phpcs_file->findNext(T_DOC_COMMENT_STRING, $stack_ptr + 1, null, false, null, true);

        // Did i find a string after the @covers tag?
        if (false === $class_name_ptr) {
            return $stack_ptr;
        }

        $class_name = $tokens[$class_name_ptr]['content'];

        // Does the class name start with a backslash?
        if ('\\' === $class_name[0]) {
            return $stack_ptr;
        }

        // Handle error
        if ($phpcs_file->addFixableError(self::ERROR_MESSAGE, $class_name_ptr, self::ERROR_TYPE)) {
            $phpcs_file->fixer->addContentBefore($class_name_ptr, '\\');
        }

        return $stack_ptr;
    }
}
