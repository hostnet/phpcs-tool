<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * Unit test for ProtectedPropertiesAreNotAllowedSniff
 *
 * @covers \Hostnet\Sniffs\Classes\ProtectedPropertiesAreNotAllowedUnitTest
 */
class ProtectedPropertiesAreNotAllowedUnitTest extends AbstractSniffUnitTest
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
        return [9 => 1, 23 => 1, 25 => 1, 27 => 1, 30 => 1, 32 => 2];
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
