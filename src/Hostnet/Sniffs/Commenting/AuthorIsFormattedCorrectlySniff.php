<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types = 1);

namespace Hostnet\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class AuthorIsFormattedCorrectlySniff implements Sniff
{

    /**
     * @return int[]
     */
    public function register()
    {
        return Tokens::$commentTokens;
    }

    /**
     * @param File $phpcs_file
     * @param int  $stack_ptr
     *
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        $content = $tokens[$stack_ptr]['content'];
        $matches = [];
        if (preg_match('/@author/', $content, $matches) !== 0) {
            $contents = $tokens[$stack_ptr+2]['content'];
            if (preg_match('/([\D]+[\s|-])+([<]{1}[\w\.]+[@]{1}[\w]+[.]{1}[\w]+[>]{1}){1}$/', $contents) === 0) {
                $type        = 'CommentFound';
                $comment_msg = trim($contents);
                $error       = 'Comment refers to Author annotation: Format is incorrect';
                $data        = [$comment_msg];
                if ($comment_msg !== '') {
                    $type   = 'CommentFound';
                    $error .= '  "%s"';
                }
                $phpcs_file->addError($error, $stack_ptr, $type, $data);
            }
        }
    }
}
