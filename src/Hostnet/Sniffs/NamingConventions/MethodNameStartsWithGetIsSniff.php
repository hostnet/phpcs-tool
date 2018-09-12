<?php
/**
 * @copyright 2015-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Accessor methods that return booleans should start with "is" not "getIs".
 */
class MethodNameStartsWithGetIsSniff implements Sniff
{
    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr): void
    {
        // search till T_STRING
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index])
               && $phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr = $stack_ptr + $index;

        $f_name = $phpcs_file->getTokens()[$ptr]['content'];
        if (0 === strpos($f_name, 'getIS')) {
            return;
        }

        $matches = [];
        if (preg_match('~^get([Ii]s[A-Z0-9]{1}.*)~', $f_name, $matches)) {
            $suggested = 'i' . substr($matches[0], 4);
            $phpcs_file->addError('Invalid method name to get Boolean value. Suggested: ' . $suggested, $ptr, 'getIs');

            return;
        }

        if (preg_match('~^getis[a-zA-Z0-9]*~', $f_name)) {
            $phpcs_file->addError('Invalid method name, do not use getis(.*)', $ptr, 'getis');

            return;
        }
    }
}
