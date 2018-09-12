<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The name of interfaces MUST end with the word 'Interface'.
 */
class InterfaceMustBePostfixedWithInterfaceSniff implements Sniff
{
    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_INTERFACE];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcs_file, $stack_ptr): void
    {
        // Search till interface name.
        $index = 0;
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index])
            && $phpcs_file->getTokens()[$stack_ptr + $index]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr = $stack_ptr + $index;
        if (preg_match('/Interface$/', $phpcs_file->getTokens()[$ptr]['content'])) {
            return;
        }

        $phpcs_file->addError(
            'Invalid interface name, interface should be postfixed with Interface.',
            $ptr,
            'interface'
        );
    }
}
