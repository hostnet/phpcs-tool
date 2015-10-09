<?php

class Hostnet_Sniffs_Commenting_AuthorIsFormattedCorrectlySniff implements \PHP_CodeSniffer_Sniff
{

    public function register()
    {
        return \PHP_CodeSniffer_Tokens::$commentTokens;
    }

    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
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
