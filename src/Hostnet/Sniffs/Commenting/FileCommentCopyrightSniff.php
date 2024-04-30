<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This Sniff sniffs that all files examined have a @copyright notation + adds a fixer for those cases.
 */
class FileCommentCopyrightSniff implements Sniff
{
    /**
     * Which years should be noted for the copyright, if not configured the _years is 'calculated'
     *
     * @var string
     */
    public $years;

    /**
     * Who is the legal holder of the copyright, if software is written during working hours it will always fall back to
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
     * The variable holds the used value for the copyright years and is initialized on every execution.
     *
     * @var string
     */
    private $local_years;

    /**
     * Which smells from the parent do we want to filter.
     *
     * @var string[]
     */
    public $filter = ['Hostnet.Commenting.FileCommentCopyright.MissingVersion'];

    /**
     * Returns an array of tokens this test wants to listen for.
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    private function addFixableError(File $phpcs_file): void
    {
        if (false === $phpcs_file->addFixableError('Missing file doc comment lines', 0, 'Missing')) {
            return;
        }

        $ptr = $phpcs_file->findFirstOnLine([T_OPEN_TAG], 0);
        $phpcs_file->fixer->addContent(
            $ptr,
            sprintf("/**\n * %s %s\n */\n", $this->copyright_tag, $this->getCopyrightLine())
        );
    }

    /**
     * Initialize the $local_years variable. If specified used the configured values otherwise use git to investigate
     * the first year the file appeared in the repo.
     *
     * @param string $filename the filename to investigate.
     */
    private function initYears($filename): void
    {
        // Set configured year if set, otherwise calculate.
        if (null !== $this->years) {
            $this->local_years = $this->years;
            return;
        }

        $now_year = date('Y');
        // Try getting the file epoch year from git.
        $start_year = trim(shell_exec(sprintf(
            'git log --reverse --pretty=format:%%ci %s 2> /dev/null |cut -d"-" -f1 | head -n1',
            $filename
        )) ?: $now_year);

        $this->local_years = $start_year . '-present';
    }

    /**
     * Build the <year> <copyright_holder> line used in the doc-block.
     *
     * @return string the combination of year and copyright_holder.
     */
    private function getCopyrightLine(): string
    {
        return $this->local_years . ' ' . $this->copyright_holder;
    }

    /**
     * Processes the file level doc-block tags, this function is executed once the parent class finds a file-level
     * doc-block.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int $stack_ptr The position of the current token in the stack passed in $tokens.
     * @param int $comment_start Position in the stack where the comment started.
     */
    private function processCopyrightTags(File $phpcs_file, $stack_ptr, $comment_start): void
    {
        $tokens = $phpcs_file->getTokens();
        foreach ($tokens[$comment_start]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === $this->copyright_tag) {
                $this->checkForContentInCopyrightTag($phpcs_file, $tag, $tokens[$comment_start]['comment_closer']);

                //Use default PEAR style copyright notation checking.
                $this->processCopyright($phpcs_file, [$tag]);

                return;
            }
        }

        //No @copyright tag because not returned
        $error = 'Missing ' . $this->copyright_tag . ' tag in file doc-block';
        if (false === $phpcs_file->addFixableError($error, $stack_ptr, 'MissingCopyrightTag')) {
            return;
        }

        if ($tokens[$tokens[$comment_start]['comment_closer']]['line'] === $tokens[$comment_start]['line']) {
            $phpcs_file->fixer->replaceToken($tokens[$comment_start]['comment_closer'] - 1, '');
            $phpcs_file->fixer->addContentBefore(
                $tokens[$comment_start]['comment_closer'],
                sprintf("\n * %s %s\n ", $this->copyright_tag, $this->getCopyrightLine())
            );

            return;
        }

        $phpcs_file->fixer->addContentBefore(
            $tokens[$comment_start]['comment_closer'],
            sprintf("* %s %s\n ", $this->copyright_tag, $this->getCopyrightLine())
        );
    }

    /**
     * Check for content in @copyright comment
     *
     * @param File $phpcs_file the tokens and contents of the current file being examined.
     * @param int $copyright_tag_ptr where does the @copyright tag starts?
     * @param int $comment_end_ptr where does the comment ends
     */
    private function checkForContentInCopyrightTag(File $phpcs_file, $copyright_tag_ptr, $comment_end_ptr): void
    {
        $tokens = $phpcs_file->getTokens();
        $ptr    = $phpcs_file->findNext(T_DOC_COMMENT_STRING, $copyright_tag_ptr, $comment_end_ptr);
        if ($ptr !== false && $tokens[$ptr]['line'] === $tokens[$copyright_tag_ptr]['line']) {
            return;
        }

        $error = 'Content missing for ' . $this->copyright_tag . ' tag in File comment';
        if (!$phpcs_file->addFixableError($error, $copyright_tag_ptr, 'Empty ' . $this->copyright_tag . ' Tag')) {
            return;
        }

        $phpcs_file->fixer->addContent($copyright_tag_ptr, ' ' . $this->getCopyrightLine());
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr): int
    {
        //Find the year of the file being examined.
        $this->initYears($phpcs_file->getFilename());

        $tokens = $phpcs_file->getTokens();

        // Find the next non whitespace token.
        $comment_start = $phpcs_file->findNext(T_WHITESPACE, $stack_ptr + 1, null, true);

        // Allow declare() statements at the top of the file.
        if ($tokens[$comment_start]['code'] === T_DECLARE) {
            $semicolon     = $phpcs_file->findNext(T_SEMICOLON, $comment_start + 1);
            $comment_start = $phpcs_file->findNext(T_WHITESPACE, $semicolon + 1, null, true);
        }

        // Ignore vim header.
        if ($tokens[$comment_start]['code'] === T_COMMENT
            && false !== strpos($tokens[$comment_start]['content'], 'vim:')) {
            $comment_start = $phpcs_file->findNext(
                T_WHITESPACE,
                $comment_start + 1,
                null,
                true
            );
        }

        $error_token = ($stack_ptr + 1);
        if (isset($tokens[$error_token]) === false) {
            $error_token--;
        }

        if ($tokens[$comment_start]['code'] === T_CLOSE_TAG) {
            // We are only interested if this is the first open tag.
            return $phpcs_file->numTokens + 1;
        }

        if ($tokens[$comment_start]['code'] === T_COMMENT) {
            $error = 'You must use "/**" style comments for a file comment';
            $phpcs_file->addError($error, $error_token, 'WrongStyle');
            $phpcs_file->recordMetric($stack_ptr, 'File has doc comment', 'yes');

            return $phpcs_file->numTokens + 1;
        }

        if ($comment_start === false
            || $tokens[$comment_start]['code'] !== T_DOC_COMMENT_OPEN_TAG
        ) {
            $this->addFixableError($phpcs_file);
            $phpcs_file->recordMetric($stack_ptr, 'File has doc comment', 'no');

            return $phpcs_file->numTokens + 1;
        }

        $comment_end = $tokens[$comment_start]['comment_closer'];

        $next_token = $phpcs_file->findNext(T_WHITESPACE, $comment_end + 1, null, true);

        $ignore = [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
            T_FUNCTION,
            T_CLOSURE,
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_FINAL,
            T_STATIC,
            T_ABSTRACT,
            T_CONST,
            T_PROPERTY,
        ];

        if (true === \in_array($tokens[$next_token]['code'], $ignore, true)) {
            $phpcs_file->addError('Missing file doc comment', $stack_ptr, 'Missing');
            $phpcs_file->recordMetric($stack_ptr, 'File has doc comment', 'no');

            return $phpcs_file->numTokens + 1;
        }

        $phpcs_file->recordMetric($stack_ptr, 'File has doc comment', 'yes');

        // Check each tag.
        $this->processCopyrightTags($phpcs_file, $stack_ptr, $comment_start);

        // Ignore the rest of the file.
        return $phpcs_file->numTokens + 1;
    }

    /**
     * Process the copyright tags.
     *
     * @param File $phpcs_file
     * @param array $tags
     */
    protected function processCopyright(File $phpcs_file, array $tags): void
    {
        $tokens = $phpcs_file->getTokens();
        foreach ($tags as $tag) {
            if ($tokens[$tag + 2]['code'] !== T_DOC_COMMENT_STRING) {
                // No content.
                continue;
            }

            $content = $tokens[$tag + 2]['content'];
            $matches = [];
            if (preg_match('/^(\d{4})((.{1})(\d{4}|present))? (.+)$/', $content, $matches) === 0) {
                $error = '@copyright tag must contain a year and the name of the copyright holder';
                $phpcs_file->addError($error, $tag, 'IncompleteCopyright');
                continue;
            }

            // Check earliest-latest year order.
            if (! ($matches[3] ?? false)) {
                continue;
            }

            if ($matches[3] !== '-') {
                $error = 'A hyphen must be used between the earliest and latest year';
                $phpcs_file->addError($error, $tag, 'CopyrightHyphen');
            }

            if (\is_int($matches[4] ?? '') && $matches[4] < $matches[1]) {
                $error = sprintf(
                    'Invalid year span "%s%s%s" found; consider "%s%s" instead',
                    $matches[1],
                    $matches[3],
                    $matches[4],
                    $matches[4],
                    $matches[1]
                );

                $phpcs_file->addWarning($error, $tag, 'InvalidCopyright');
            }
        }
    }
}
