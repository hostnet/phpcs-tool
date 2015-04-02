<?php

class Hostnet_Sniffs_Commenting_AuthorIsFormattedCorrectlySniff implements \PHP_CodeSniffer_Sniff
{

    public function register()
    {
        return \PHP_CodeSniffer_Tokens::$commentTokens;
    }

    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {

        $tokens = $phpcsFile->getTokens();

        $content = $tokens[$stackPtr]['content'];
        $matches = array();
        if (preg_match('/@author/', $content, $matches) !== 0) {
            $contents = $tokens[$stackPtr+2]['content'];
            if (preg_match('/([\D]+[\s|-])+([<]{1}[\w\.]+[@]{1}[\w]+[.]{1}[\w]+[>]{1}){1}$/', $contents) === 0) {
                $type        = 'CommentFound';
                $comment_msg = trim($contents);
                $error       = 'Comment refers to Author annotation: Format is incorrect';
                $data        = array($comment_msg);
                if ($comment_msg !== '') {
                    $type   = 'CommentFound';
                    $error .= '  "%s"';
                }
                $phpcsFile->addError($error, $stackPtr, $type, $data);
            }
        }
    }
}
