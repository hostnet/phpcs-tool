<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\UseStatementsAlphabeticallyOrderedUnitTest
 */
class UseStatementsAlphabeticallyOrderedUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList($filename = null)
    {
        switch ($filename) {
            case 'UseStatementsAlphabeticallyOrderedUnitTest.0.inc':
                return [];
            case 'UseStatementsAlphabeticallyOrderedUnitTest.1.inc':
                return [16 => 1, 18 => 1];
            case 'UseStatementsAlphabeticallyOrderedUnitTest.2.inc':
                return [4 => 1, 11 => 2];
            case 'UseStatementsAlphabeticallyOrderedUnitTest.3.inc':
                return [16 => 1, 18 => 1];
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList()
    {
        return [];
    }
}
