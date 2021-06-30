<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\ClassAndNamespaceMustBeInPascalCaseSniff
 */
class ClassAndNamespaceMustBeInPascalCaseUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $filename
     *
     * @return [int => int]
     */
    public function getErrorList($filename = null)
    {
        switch ($filename) {
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.0.inc':
                return [2 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.1.inc':
                return [4 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.2.inc':
                return [2 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.3.inc':
                return [4 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.4.inc':
                return [];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.5.inc':
                return [3 => 1];
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $filename
     *
     * @return [int => int]
     */
    public function getWarningList($filename = null)
    {
        return [];
    }
}
