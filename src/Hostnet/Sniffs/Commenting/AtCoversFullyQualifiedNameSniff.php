<?php
declare(strict_types = 1);
/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * This Sniff sniffs that all files examined have a correct @covers notation + added a fixer for those cases.
 */
class Hostnet_Sniffs_Commenting_AtCoversFullyQualifiedNameSniff extends PEAR_Sniffs_Commenting_FileCommentSniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param int $stack_ptr The position of the current token in the stack passed in $tokens.
     * @return int returns a stack pointer. The sniff will not be called again on the current file until the returned
     *              stack pointer is reached. Return (count($tokens) + 1) to skip the rest of the file.
     */
    public function process(PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        // Is it a unit test?
        if (strpos($phpcs_file->getFilename(), 'Test.php') === false) {
            // No, skip the rest of it
            return (count($tokens) + 1);
        }
        $content = $tokens[$stack_ptr]['content'];
        $matches = [];

        // Is there a cover tag?
        if (preg_match('/\bcovers\b/', $content, $matches) !== 0 &&
            $tokens[$stack_ptr + 1]['type'] === "T_DOC_COMMENT_WHITESPACE") {

            // Is this the correct covertag which we are looking for?
            $end_doc_block_tag = $phpcs_file->findNext(T_DOC_COMMENT_CLOSE_TAG, $stack_ptr);
            $class_tag = $phpcs_file->findNext(T_CLASS, $end_doc_block_tag);

            if($end_doc_block_tag && $class_tag) {
                /*
                 * Yes, get the content of what is 2 tokens further e.g:
                 * @coverline Hostnet/Test/UnitTest.php
                 *            ^
                 */
                $contents = $tokens[$stack_ptr+2]['content'];

                // Is first character a backslash?
                if (strtolower($contents) != "stdclass" && $contents[0] != "\\") {
                    // No, handle the error
                    $type        = 'AtCoversNeedsFQCN';
                    $comment_msg = trim($contents);
                    $error       = 'Covers annotation should use fully qualified class name (it should start with a "\") "%s"';

                    $fix = $phpcs_file->addFixableError($error, $stack_ptr, $type);
                    if ($fix) {
                        $phpcs_file->fixer->addContentBefore($stack_ptr + 2, "\\");
                    }
                }
            }


            // Found the tag, there is no other reason to keep checking. Skip file..
            return (count($tokens) + 1);
        }
    }
}
