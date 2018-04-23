<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\CopyrightMustBeBeforeDeclareSniff
 */
class CopyrightMustBeBeforeDeclareUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList($filename = null): array
    {
        switch ($filename) {
            // Valid case
            case 'CopyrightMustBeBeforeDeclareUnitTest.0.inc':
                return [];
            // Case without a namespace
            case 'CopyrightMustBeBeforeDeclareUnitTest.1.inc':
                return [];
            // Case without a docblock
            case 'CopyrightMustBeBeforeDeclareUnitTest.2.inc':
                return [];
            // Broken case
            case 'CopyrightMustBeBeforeDeclareUnitTest.3.inc':
                return [2 => 1];
        }

        throw new \LogicException(sprintf('Missing array for filename %s', $filename));
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningList(): array
    {
        return [];
    }
}
