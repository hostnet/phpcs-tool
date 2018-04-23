<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\NoVerticalWhitespaceBetweenUseStatementsUnitTest
 */
class NoVerticalWhitespaceBetweenUseStatementsUnitTest extends AbstractSniffUnitTest
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
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.0.inc':
                return [5 => 1, 12 => 1, 14 => 1, 15 => 1, 16 => 1, 18 => 1];
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.1.inc':
                return [5 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1];
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.2.inc':
                return [5 => 1];
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.3.inc':
                return [4 => 1,6 => 1];
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
