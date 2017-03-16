<?php
declare(strict_types = 1);
/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * This Sniff sniffs that all files examined have a @copyright notation + addes a fixer for those cases.
 */
class Hostnet_Sniffs_Commenting_UnitTestCommentCoversSniff extends PEAR_Sniffs_Commenting_FileCommentSniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        // commentTokens is not defined in our code
        return \PHP_CodeSniffer_Tokens::$commentTokens;
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
        // Is it a unit test?
        if (strpos($phpcs_file->getFilename(), 'Test.php') === false) {
            // No, skip the rest of it
            return $stack_ptr;
        }
        $tokens = $phpcs_file->getTokens();

        $content = $tokens[$stack_ptr]['content'];
        $matches = [];

        // Is there a cover tag?
        if (preg_match('/@covers/', $content, $matches) !== 0) {
            /*
             * Yes, get the content that is 2 tokens further e.g:
             * @coverline Hostnet/Test/UnitTest.php
             *            ^
             */
            $contents = $tokens[$stack_ptr+2]['content'];

            // Is first character a backslash?
            if ($contents[0] != "\\") {
                // No, handle the error
                $type        = 'SlashNotFound';
                $comment_msg = trim($contents);
                $error       = 'Comment refers to Covers annotation: Cover is missing first backslash';
                $data        = [$comment_msg];
                if ($comment_msg !== '') {
                    $type   = 'SlashNotFound';
                    $error .= '  "%s"';
                }

                $fix = $phpcs_file->addFixableError($error, $stack_ptr, $type, $data);
                if ($fix) {
                    $phpcs_file->fixer->replaceToken(
                        $stack_ptr + 2,
                        "\\" . $contents
                    );
                }
            }

            // Found the tag, there is no other reason to keep checking.
            return $stack_ptr;
        }
    }
}
