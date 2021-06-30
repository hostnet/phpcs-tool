<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * Unit test for ProtectedPropertiesAreNotAllowedSniff
 * @covers \Hostnet\Sniffs\Classes\OnlyOneUseStatementPerLineUnitTest
 */
class OnlyOneUseStatementPerLineUnitTest extends AbstractSniffUnitTest
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
        return [4 => 1, 5 => 1, 7 => 1];
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
