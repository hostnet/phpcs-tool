<?php
declare(strict_types = 1);
/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * This Sniff sniffs that all files examined have a @copyright notation + addes a fixer for those cases.
 */
class Hostnet_Sniffs_Commenting_FileCommentCopyrightSniff extends PEAR_Sniffs_Commenting_FileCommentSniff
{
    /**
     * Is it our 'super-class' / 'parent' who found an error or are we the one?
     *
     * @var boolean
     */
    private $replace_error = true;

    /**
     * Which years should be noted for the copyright, if not configured the _years is 'calculated'
     *
     * @var string
     */
    public $years = null;

    /**
     * The variable holds the used value for the copyright years and is initialized on every execution.
     *
     * @var string
     */
    private $local_years;

    /**
     * Who is the legal holder of the copyright, if software is writen during working hours it will always fall back to
     * the company the employee works for.
     *
     * @var string
     */
    public $copyright_holder = 'Hostnet B.V.';

    /**
     * What is the 'name' of the tag to search for? phpDocumenter uses @copyright.
     *
     * @see https://phpdoc.org/docs/latest/references/phpdoc/tags/copyright.html
     * @var string
     */
    public $copyright_tag = '@copyright';

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
        //Find the year of the file being examined.
        $this->initYears($phpcs_file->getFilename());

        //Execute the parent process function
        $ptr = parent::process($phpcs_file, $stack_ptr);

        if ($this->replace_error) {
            $replace = false;
            foreach (array_merge($phpcs_file->getWarnings(), $phpcs_file->getErrors()) as $lines) {
                foreach ($lines as $columns) {
                    foreach ($columns as $reported) {
                        if ($reported['source'] == 'Hostnet.Commenting.FileCommentCopyright.Missing') {
                            $replace = true;
                            break;
                        }
                    }
                }
            }
            if ($replace) {
                $fix = $phpcs_file->addFixableError('Missing file doc comment lines', 0, 'Missing');
                if ($fix) {
                    $ptr = $phpcs_file->findFirstOnLine([T_OPEN_TAG], 0);
                    $phpcs_file->fixer->addContent(
                        $ptr,
                        sprintf(
                            "/**\n" .
                            " * %s %s\n" .
                            " */\n",
                            $this->copyright_tag,
                            $this->getCopyrightLine()
                        )
                    );
                }
            }
        }
        $this->replace_error = true;
        return $ptr;
    }

    /**
     * Processes the file level doc-block tags, this function is executed once the parent class finds a file-level
     * doc-block.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param int $stack_ptr The position of the current token in the stack passed in $tokens.
     * @param int $comment_start Position in the stack where the comment started.
     */
    protected function processTags(PHP_CodeSniffer_File $phpcs_file, $stack_ptr, $comment_start)
    {
        //We are in control, there is a file-level doc-block
        $this->replace_error = false;

        $tokens = $phpcs_file->getTokens();
        foreach ($tokens[$comment_start]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] == $this->copyright_tag) {
                $this->checkForContentInCopyrightTag($phpcs_file, $tag, $tokens[$comment_start]['comment_closer']);

                //Use default PEAR style copyright notation checking.
                $this->processCopyright($phpcs_file, [$tag]);
                return;
            }
        }

        //No @copyright tag because not returned
        $error = 'Missing ' . $this->copyright_tag . ' tag in file doc-block';
        $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'MissingCopyrightTag');
        if ($fix) {
            if ($tokens[$tokens[$comment_start]['comment_closer']]['line'] == $tokens[$comment_start]['line']) {
                $phpcs_file->fixer->replaceToken($tokens[$comment_start]['comment_closer'] - 1, '');
                $phpcs_file->fixer->addContentBefore(
                    $tokens[$comment_start]['comment_closer'],
                    sprintf(
                        "\n * %s %s\n ",
                        $this->copyright_tag,
                        $this->getCopyrightLine()
                    )
                );
            } else {
                $phpcs_file->fixer->addContentBefore(
                    $tokens[$comment_start]['comment_closer'],
                    sprintf(
                        "* %s %s\n ",
                        $this->copyright_tag,
                        $this->getCopyrightLine()
                    )
                );
            }
        }
    }

    /**
     * Check for content in @copyright comment
     *
     * @param PHP_CodeSniffer_File $phpcs_file the tokens and contents of the current file being examined.
     * @param int $copyright_tag_ptr where does the @copyright tag starts?
     * @param int $comment_end_ptr where does the comment ends
     */
    private function checkForContentInCopyrightTag(
        PHP_CodeSniffer_File $phpcs_file,
        $copyright_tag_ptr,
        $comment_end_ptr
    ) {
        $tokens = $phpcs_file->getTokens();
        $ptr    = $phpcs_file->findNext(T_DOC_COMMENT_STRING, $copyright_tag_ptr, $comment_end_ptr);
        if ($ptr === false || $tokens[$ptr]['line'] !== $tokens[$copyright_tag_ptr]['line']) {
            $error = 'Content missing for ' . $this->copyright_tag . ' tag in File comment';
            $fix   = $phpcs_file->addFixableError(
                $error,
                $copyright_tag_ptr,
                'Empty ' . $this->copyright_tag . ' Tag'
            );
            if ($fix) {
                $phpcs_file->fixer->addContent($copyright_tag_ptr, ' ' . $this->getCopyrightLine());
            }
        }
    }

    /**
     * Build the <year> <copyright_holder> line used in the doc-block.
     *
     * @return string the combination of year and copyright_holder.
     */
    private function getCopyrightLine()
    {
        return $this->local_years . ' ' . $this->copyright_holder;
    }

    /**
     * Initialize the $local_years variable. If specified used the configured values otherwise use git to investigate
     * the first year the file appeared in the repo.
     *
     * @param string $filename the filename to investigate.
     */
    private function initYears($filename)
    {
        if (!isset($this->years)) {
            $now_year = date('Y');

            //try git
            $cmd  = sprintf(
                'git log --reverse --pretty=format:%%ci %s 2> /dev/null |cut -d"-" -f1 | head -n1',
                $filename
            );
            $year = trim(`$cmd` ?: '');
            if (empty($year) || $year === '') {
                $year = $now_year;
            }

            if ($year === $now_year) {
                $this->local_years = $now_year;
            } else {
                $this->local_years = $year . '-' . $now_year;
            }
        } else {
            $this->local_years = $this->years;
        }
    }
}
