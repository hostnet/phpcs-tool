<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Commenting;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * Unit test for AuthorIsFormattedCorrectly
 * @author Nico Schoenmaker <nschoenmaker@hostnet.nl>
 */
class AuthorIsFormattedCorrectlyUnitTest extends AbstractSniffUnitTest
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
            case 'AuthorIsFormattedCorrectlyUnitTest.0.inc':
                return array(4 => 1, 9 => 1, 31 => 1);
            case 'AuthorIsFormattedCorrectlyUnitTest.1.inc':
                return array();
        }
        return array();
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
        return array();
    }
}
