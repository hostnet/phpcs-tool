<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\FileCommentSniff;

/**
 * Checks if a unit test has @covers defined for a class with the same namespace structure.
 */
class AtCoversCounterPartSniff extends FileCommentSniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_OPEN_TAG];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        if (false === strpos($phpcs_file->getFilename(), 'Test.php')) {
            return count($tokens) + 1;
        }

        $start_of_class    = $phpcs_file->findNext([T_CLASS, T_TRAIT], 0);
        $end_of_header_doc = $phpcs_file->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $start_of_class);
        $namespaces        = [];
        if ($end_of_header_doc !== false) {
            $namespaces = $this->extractCoverageNamespaces($phpcs_file, $end_of_header_doc);
        }

        $test_namespace            = $this->getNamespaceFromFile($phpcs_file->getFilename());
        $counter_part_namespace    = str_replace('Tests\\', '', $test_namespace);
        $counter_part_namespace    = rtrim($counter_part_namespace, 'Test');
        $expected_covers_namespace = "\\$counter_part_namespace";
        if (!\class_exists($counter_part_namespace) || \in_array($expected_covers_namespace, $namespaces, true)) {
            return count($tokens) + 1;
        }

        $fix           = <<<FIX
/**
 * @covers $expected_covers_namespace
 */
FIX;
        $fix          .= PHP_EOL;
        $fix_position  = $start_of_class;
        // The class doc should be within +/- 5 tokens of the class token.
        if ($end_of_header_doc !== false && $end_of_header_doc > ($start_of_class - 5)) {
            $fix          = sprintf("* @covers %s\n ", $expected_covers_namespace);
            $fix_position = $end_of_header_doc;
        }

        if ($phpcs_file->addFixableError(
            sprintf('Test class is missing "@covers %s".', $expected_covers_namespace),
            $start_of_class,
            'MissingAtCoversForCounterPart'
        )) {
            $expected_namespace_length = \strlen($expected_covers_namespace);
            foreach ($namespaces as $position => $namespace) {
                if (stripos($namespace, $expected_covers_namespace) === false) {
                    continue;
                }

                if (\strlen($namespace) > $expected_namespace_length
                    && $namespace[$expected_namespace_length] === '\\') {
                    continue;
                }

                $phpcs_file->fixer->replaceToken($position, $expected_covers_namespace);

                return count($tokens) + 1;
            }

            $phpcs_file->fixer->addContentBefore($fix_position, $fix);
        }

        return count($tokens) + 1;
    }

    private function extractCoverageNamespaces(File $phpcs_file, int $close_tag_position): array
    {
        $coverage_namespaces = [];
        $tokens              = $phpcs_file->getTokens();
        $open_tag_position   = $phpcs_file->findPrevious(T_DOC_COMMENT_OPEN_TAG, $close_tag_position);

        for ($i = $open_tag_position; $i < $close_tag_position; $i++) {
            if ($tokens[$i]['content'] !== '@covers') {
                continue;
            }

            $coverage_namespaces[$i + 2] = $tokens[$i + 2]['content'];
        }

        return $coverage_namespaces;
    }

    private function getNamespaceFromFile(string $path_to_file): string
    {
        $namespace = '';
        $class     = '';

        $getting_namespace = false;
        $getting_class     = false;

        foreach (token_get_all(file_get_contents($path_to_file)) as $token) {
            // If this token is the namespace declaring, then flag that the next tokens will be the namespace name.
            if (\is_array($token) && $token[0] === T_NAMESPACE) {
                $getting_namespace = true;
            }

            // If this token is the class declaring, then flag that the next tokens will be the class name.
            if (\is_array($token) && ($token[0] === T_CLASS || $token[0] === T_TRAIT)) {
                $getting_class = true;
            }

            if ($getting_namespace) {
                // If the token is a string or the namespace separator.
                if (\is_array($token) && \in_array($token[0], [T_STRING, T_NS_SEPARATOR], true)) {
                    // Append the token's value to the name of the namespace.
                    $namespace .= $token[1];
                } elseif ($token === ';') {
                    $getting_namespace = false;
                }
            }

            if (!$getting_class) {
                continue;
            }

            // If the token is a string, it's the name of the class.
            if (!\is_array($token) || $token[0] !== T_STRING) {
                continue;
            }

            // Store the token's value as the class name.
            $class = $token[1];
            break;
        }

        return $namespace ? $namespace . '\\' . $class : $class;
    }
}
