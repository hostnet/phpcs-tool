<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\AbstractClassMustBePrefixedWithAbstractSniff
 */
class AbstractClassMustBePrefixedWithAbstractUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $filename
     */
    public function getErrorList($filename = null): array
    {
        switch ($filename) {
            case 'AbstractClassMustBePrefixedWithAbstractUnitTest.0.inc':
                return [];
            case 'AbstractClassMustBePrefixedWithAbstractUnitTest.1.inc':
                return [];
            case 'AbstractClassMustBePrefixedWithAbstractUnitTest.2.inc':
                return [3 => 1];
            case 'AbstractClassMustBePrefixedWithAbstractUnitTest.3.inc':
                return [];
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $filename
     */
    public function getWarningList($filename = null): array
    {
        return [];
    }
}
