<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Declares;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * Unit test class for the Strict sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 */
class StrictUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $test_file
     * @return array <int, int>
     */
    public function getErrorList($test_file = '')
    {
        if ($test_file === 'StrictUnitTest.1.inc') {
            return [1 => 1];
        }

        if ($test_file === 'StrictUnitTest.2.inc'
                  || $test_file === 'StrictUnitTest.4.inc'
                  || $test_file === 'StrictUnitTest.5.inc'
                  || $test_file === 'StrictUnitTest.6.inc'
                  || $test_file === 'StrictUnitTest.7.inc'
                  || $test_file === 'StrictUnitTest.8.inc'
                  || $test_file === 'StrictUnitTest.9.inc'
        ) {
            return [2 => 1];
        }

        if ($test_file === 'StrictUnitTest.10.inc') {
            return [9 => 1];
        }

        return [];
    }


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [];
    }
}
